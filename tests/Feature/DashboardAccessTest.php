<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        $this->artisan('migrate');
    }

    public function test_pending_users_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'pending', 'email_verified_at' => now()]);
        $this->actingAs($user);
        $this->get('/dashboard')->assertRedirect(route('pending-approval'));
    }

    public function test_rejected_users_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'rejected', 'email_verified_at' => now()]);
        $this->actingAs($user);
        $this->get('/dashboard')->assertRedirect(route('pending-approval'));
    }

    public function test_suspended_users_cannot_access_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'suspended', 'email_verified_at' => now()]);
        $this->actingAs($user);
        $this->get('/dashboard')->assertRedirect(route('pending-approval'));
    }

    public function test_approved_users_can_access_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
        $this->actingAs($user);
        $this->get('/dashboard')->assertOk();
    }
}
