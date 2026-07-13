<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_can_be_rendered_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'company_name' => 'Test Company',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertNotNull(auth()->user()->trial_started_at);
        $this->assertNotNull(auth()->user()->trial_ends_at);
        $this->assertNotNull(auth()->user()->license_started_at);
        $this->assertNotNull(auth()->user()->license_ends_at);
        $this->assertSame('user', auth()->user()->role);
        $response->assertRedirect(route('verification.notice', absolute: false));
        $response->assertSessionHas('success', 'Kayıt başarılı. Devam etmek için e-posta adresinizi doğrulayın.');
    }
}
