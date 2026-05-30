<?php

namespace Tests\Feature;

use App\Models\DailyReflection;
use App\Models\User;
use App\Services\DailyReflectionService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyReflectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        $this->artisan('migrate');
    }

    protected function approvedUser(): User
    {
        return User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
    }

    public function test_published_content_visible_on_dashboard(): void
    {
        $user = $this->approvedUser();
        DailyReflection::create([
            'title' => 'Today Title',
            'type' => 'reflection',
            'body' => 'Body A',
            'source' => 'Source A',
            'publish_date' => Carbon::today(),
            'status' => 'published',
        ]);

        $this->actingAs($user);
        $this->get('/dashboard')->assertSee('Today Title')->assertSee('Body A');
    }

    public function test_draft_content_hidden(): void
    {
        $user = $this->approvedUser();
        DailyReflection::create([
            'title' => 'Draft Title',
            'type' => 'reflection',
            'body' => 'Secret Draft',
            'publish_date' => Carbon::today(),
            'status' => 'draft',
        ]);

        $this->actingAs($user);
        $this->get('/dashboard')->assertDontSee('Draft Title')->assertDontSee('Secret Draft');
    }

    public function test_archived_content_hidden(): void
    {
        $user = $this->approvedUser();
        DailyReflection::create([
            'title' => 'Old Title',
            'type' => 'reflection',
            'body' => 'Old Body',
            'publish_date' => Carbon::today(),
            'status' => 'archived',
        ]);

        $this->actingAs($user);
        $this->get('/dashboard')->assertDontSee('Old Title')->assertDontSee('Old Body');
    }

    public function test_latest_published_fallback(): void
    {
        $user = $this->approvedUser();
        DailyReflection::create([
            'title' => 'Yesterday Title',
            'type' => 'reflection',
            'body' => 'Yesterday Body',
            'publish_date' => Carbon::yesterday(),
            'status' => 'published',
        ]);

        $this->actingAs($user);
        $this->get('/dashboard')->assertSee('Yesterday Title');
    }

    public function test_publish_action_archives_same_day_items_and_logs(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'status' => 'approved', 'email_verified_at' => now()]);
        $a = DailyReflection::create([
            'title' => 'A', 'type' => 'reflection', 'body' => 'A', 'publish_date' => Carbon::today(), 'status' => 'published',
        ]);
        $b = DailyReflection::create([
            'title' => 'B', 'type' => 'reflection', 'body' => 'B', 'publish_date' => Carbon::today(), 'status' => 'draft',
        ]);

        app(DailyReflectionService::class)->publish($b, $admin, false);

        $this->assertEquals('archived', $a->fresh()->status);
        $this->assertEquals('published', $b->fresh()->status);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'published_daily_reflection',
        ]);
    }
}
