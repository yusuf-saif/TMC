<?php

namespace Tests\Feature;

use App\Console\Commands\TmcCreateAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        $this->artisan('migrate');
    }

    public function test_non_admin_user_gets_403_on_admin_panel(): void
    {
        $user = User::factory()->create(['status' => 'approved']);
        $this->actingAs($user);

        $this->get('/admin')->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_panel(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($admin);

        $this->get('/admin')->assertOk();
    }
}
