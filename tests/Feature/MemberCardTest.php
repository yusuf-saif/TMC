<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberCardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        $this->artisan('migrate');
    }

    public function test_approved_users_can_access_member_card(): void
    {
        $user = User::factory()->create([
            'status' => 'approved',
            'email_verified_at' => now(),
            'membership_number' => 'TMC-2026-0001',
        ]);

        $this->actingAs($user);

        $this->get(route('member-card'))
            ->assertOk()
            ->assertSee('Legacy Member')
            ->assertSee('TMC-2026-0001');
    }

    public function test_pending_users_blocked_from_member_card(): void
    {
        $user = User::factory()->create([
            'status' => 'pending',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $this->get(route('member-card'))
            ->assertRedirect(route('pending-approval'));
    }

    public function test_suspended_users_blocked_from_member_card(): void
    {
        $user = User::factory()->create([
            'status' => 'suspended',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $this->get(route('member-card'))
            ->assertRedirect(route('pending-approval'));
    }

    public function test_rejected_users_blocked_from_member_card(): void
    {
        $user = User::factory()->create([
            'status' => 'rejected',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $this->get(route('member-card'))
            ->assertRedirect(route('pending-approval'));
    }
}
