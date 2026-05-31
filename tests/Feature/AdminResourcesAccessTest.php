<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResourcesAccessTest extends TestCase
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
        return User::factory()->create([
            'is_admin' => true,
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_can_access_all_admin_resources_pages(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        $this->get('/admin')->assertStatus(200);
        $this->get('/admin/members')->assertStatus(200);
        $this->get('/admin/events')->assertStatus(200);
        $this->get('/admin/announcements')->assertStatus(200);
        $this->get('/admin/daily-reflections')->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_resources(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $this->get('/admin')->assertStatus(403);
    }
}
