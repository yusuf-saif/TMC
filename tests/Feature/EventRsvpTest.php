<?php

namespace Tests\Feature;

use App\Jobs\SendEventReminderJob;
use App\Mail\RsvpConfirmationMail;
use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\User;
use App\Services\EventService;
use App\Services\RsvpService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EventRsvpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        $this->artisan('migrate');
    }

    protected function makeEvent(array $overrides = []): Event
    {
        $admin = User::factory()->create(['is_admin' => true, 'status' => 'approved', 'email_verified_at' => now()]);
        $data = array_merge([
            'title' => 'Test Event',
            'slug' => 'test-event',
            'event_date' => Carbon::now()->addDays(2),
            'end_date' => Carbon::now()->addDays(2)->addHours(2),
            'location_type' => 'online',
            'location_detail' => 'Zoom link',
            'status' => 'draft',
        ], $overrides);
        $event = app(EventService::class)->create($data, $admin);
        app(EventService::class)->publish($event, $admin);
        return $event->fresh();
    }

    public function test_event_creation_and_publishing(): void
    {
        $event = $this->makeEvent();
        $this->assertEquals('published', $event->status);
        $this->assertDatabaseHas('audit_logs', ['action' => 'published_event']);
    }

    public function test_member_can_rsvp_and_get_confirmation(): void
    {
        Mail::fake(); Queue::fake();
        $event = $this->makeEvent();
        $user = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);

        app(RsvpService::class)->register($event, $user);

        $this->assertDatabaseHas('event_rsvps', ['event_id' => $event->id, 'user_id' => $user->id, 'status' => 'registered']);
        Mail::assertQueued(RsvpConfirmationMail::class);
        Queue::assertPushed(SendEventReminderJob::class);
    }

    public function test_duplicate_rsvp_prevented(): void
    {
        $event = $this->makeEvent();
        $user = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
        app(RsvpService::class)->register($event, $user);
        app(RsvpService::class)->register($event, $user);
        $this->assertEquals(1, EventRsvp::where('event_id',$event->id)->where('user_id',$user->id)->count());
    }

    public function test_rsvp_cancellation(): void
    {
        $event = $this->makeEvent();
        $user = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
        app(RsvpService::class)->register($event, $user);
        app(RsvpService::class)->cancel($event, $user);
        $this->assertDatabaseHas('event_rsvps', ['event_id' => $event->id, 'user_id' => $user->id, 'status' => 'cancelled']);
    }

    public function test_dashboard_shows_upcoming_events(): void
    {
        $event = $this->makeEvent(['title' => 'Visible Event']);
        $user = User::factory()->create(['status' => 'approved', 'email_verified_at' => now()]);
        $this->actingAs($user);
        $this->get('/dashboard')->assertSee('Visible Event');
    }
}
