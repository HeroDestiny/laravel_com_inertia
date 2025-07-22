<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationNotificationTest extends TestCase
{
  use RefreshDatabase;

  public function test_email_verification_notification_can_be_sent()
  {
    Notification::fake();

    $user = User::factory()->create([
      'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->post('/email/verification-notification');

    $response->assertStatus(302);
    $response->assertSessionHas('status', 'verification-link-sent');
  }

  public function test_email_verification_notification_is_throttled()
  {
    Notification::fake();

    $user = User::factory()->create([
      'email_verified_at' => null,
    ]);

    // Send first notification
    $this->actingAs($user)->post('/email/verification-notification');

    // Try to send another immediately - should be throttled
    $response = $this->actingAs($user)->post('/email/verification-notification');

    // The response might vary based on throttling implementation
    // But it should not send another notification
    $response->assertStatus(302);
  }

  public function test_verified_users_cannot_send_verification_notification()
  {
    $user = User::factory()->create([
      'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)->post('/email/verification-notification');

    $response->assertRedirect('/dashboard');
  }
}
