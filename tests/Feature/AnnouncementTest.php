<?php

namespace Tests\Feature;

use App\Mail\AnnouncementMail;
use App\Models\Announcement;
use App\Models\User;
use App\Services\AnnouncementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        $this->artisan('migrate');
    }

    protected function admin(): User
    {
        return User::factory()->create(['is_admin'=>true,'status'=>'approved','email_verified_at'=>now()]);
    }

    protected function approved(): User
    {
        return User::factory()->create(['status'=>'approved','email_verified_at'=>now()]);
    }

    public function test_admin_can_create_and_publish_announcement(): void
    {
        $admin = $this->admin();
        $a = app(AnnouncementService::class)->create([
            'title' => 'New Series',
            'slug' => 'new-series',
            'body' => 'Details...',
            'status' => 'draft',
            'send_email' => false,
        ], $admin);

        app(AnnouncementService::class)->publishNow($a, $admin);

        $this->assertEquals('published', $a->fresh()->status);
        $this->assertDatabaseHas('audit_logs', ['action' => 'published_announcement']);
    }

    public function test_scheduled_announcements_publish_automatically(): void
    {
        $admin = $this->admin();
        $a = app(AnnouncementService::class)->create([
            'title' => 'Scheduled',
            'slug' => 'scheduled',
            'body' => 'Soon...',
            'status' => 'scheduled',
            'publish_at' => now()->subMinute(),
            'send_email' => false,
        ], $admin);

        $count = app(AnnouncementService::class)->publishDue();
        $this->assertGreaterThanOrEqual(1, $count);
        $this->assertEquals('published', $a->fresh()->status);
    }

    public function test_dashboard_shows_latest_published(): void
    {
        $admin = $this->admin();
        app(AnnouncementService::class)->create([
            'title' => 'Old', 'slug'=>'old', 'body'=>'A', 'status'=>'published', 'published_at'=>now()->subDay()
        ], $admin);
        app(AnnouncementService::class)->create([
            'title' => 'Latest', 'slug'=>'latest', 'body'=>'B', 'status'=>'published', 'published_at'=>now()
        ], $admin);

        $user = $this->approved();
        $this->actingAs($user);
        $this->get('/dashboard')->assertSee('Latest');
    }

    public function test_email_dispatched_when_enabled(): void
    {
        Mail::fake();
        $admin = $this->admin();
        $a = app(AnnouncementService::class)->create([
            'title' => 'Email Me', 'slug'=>'email-me', 'body'=>'E', 'status'=>'draft', 'send_email'=>true
        ], $admin);
        app(AnnouncementService::class)->publishNow($a, $admin);
        Mail::assertQueued(AnnouncementMail::class);
    }

    public function test_archived_hidden_and_member_routes(): void
    {
        $admin = $this->admin();
        $a = app(AnnouncementService::class)->create([
            'title' => 'Archive Me', 'slug'=>'archive-me', 'body'=>'X', 'status'=>'archived'
        ], $admin);

        $user = $this->approved();
        $this->actingAs($user);

        $this->get('/announcements')->assertDontSee('Archive Me');
        $this->get('/announcements/archive-me')->assertStatus(404);
    }
}
