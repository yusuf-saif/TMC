<?php

namespace Tests\Feature;

use App\Mail\MemberApprovedMail;
use App\Models\User;
use App\Services\MembershipService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Use sqlite memory for tests
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        $this->artisan('migrate');
    }

    public function test_registration_defaults_to_pending_status(): void
    {
        $user = User::factory()->create();
        $this->assertEquals('pending', $user->status);
    }

    public function test_admin_can_approve_member_and_assign_number_and_send_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['is_admin' => true, 'status' => 'approved']);
        $user = User::factory()->create(['status' => 'pending']);

        app(MembershipService::class)->approve($user, $admin);

        $user->refresh();
        $this->assertEquals('approved', $user->status);
        $this->assertNotNull($user->approved_at);
        $this->assertNotNull($user->membership_number);

        Mail::assertSent(MemberApprovedMail::class, function ($mailable) use ($user) {
            return $mailable->user->is($user);
        });
    }

    public function test_admin_can_reject_member_and_store_reason(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'status' => 'approved']);
        $user = User::factory()->create(['status' => 'pending']);

        app(MembershipService::class)->reject($user, $admin, 'Incomplete information');
        $user->refresh();

        $this->assertEquals('rejected', $user->status);
        $this->assertNotNull($user->rejected_at);
        $this->assertEquals('Incomplete information', $user->rejection_reason);
    }

    public function test_admin_can_suspend_and_reactivate(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'status' => 'approved']);
        $user = User::factory()->create(['status' => 'approved']);

        app(MembershipService::class)->suspend($user, $admin, 'Violation');
        $user->refresh();
        $this->assertEquals('suspended', $user->status);

        app(MembershipService::class)->reactivate($user, $admin);
        $user->refresh();
        $this->assertEquals('approved', $user->status);
    }

    public function test_only_approved_members_can_access_dashboard(): void
    {
        $user = User::factory()->create(['status' => 'pending']);
        $this->actingAs($user);

        $this->get('/dashboard')->assertRedirect(route('pending-approval'));

        $user->status = 'approved';
        $user->save();

        $this->get('/dashboard')->assertOk();
    }
}
