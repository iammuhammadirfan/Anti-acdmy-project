<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;
    public function test_forgot_password_page_loads_successfully(): void
    {
        $response = $this->get('/admin/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Forgot Password?');
        $response->assertSee('Send Password Reset Link');
    }

    public function test_user_can_request_password_reset_link(): void
    {
        $user = User::factory()->create([
            'email' => 'staff_pw_test@example.com',
            'password' => Hash::make('OldPassword123!'),
            'is_active' => true,
        ]);

        $response = $this->post('/admin/forgot-password', [
            'email' => 'staff_pw_test@example.com',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'staff_pw_test@example.com',
        ]);
    }

    public function test_reset_password_page_loads_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'valid_token_user@example.com',
            'is_active' => true,
        ]);

        $token = 'test_token_1234567890abcdefghijklmnopqrstuvwxyz';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $response = $this->get("/admin/reset-password/{$token}?email={$user->email}");

        $response->assertStatus(200);
        $response->assertSee('Set New Password');
        $response->assertSee($user->email);
    }

    public function test_user_can_reset_password_and_login_with_new_password(): void
    {
        $user = User::factory()->create([
            'email' => 'reset_success_user@example.com',
            'password' => Hash::make('OldPassword123!'),
            'is_active' => true,
        ]);

        $token = 'secure_reset_token_test_abc123';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $response = $this->post('/admin/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewSecretPassword999!',
            'password_confirmation' => 'NewSecretPassword999!',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHas('success');

        // Token should be removed from database
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);

        // User password should be updated
        $user->refresh();
        $this->assertTrue(Hash::check('NewSecretPassword999!', $user->password));

        // User can now log in with the new password
        $loginResponse = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'NewSecretPassword999!',
        ]);
        $loginResponse->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_expired_token_is_rejected(): void
    {
        $user = User::factory()->create([
            'email' => 'expired_user@example.com',
            'is_active' => true,
        ]);

        $token = 'expired_token_777';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()->subMinutes(65)] // 65 mins old (> 60)
        );

        $response = $this->get("/admin/reset-password/{$token}?email={$user->email}");

        $response->assertRedirect('/admin/forgot-password');
        $response->assertSessionHas('error');
    }

    public function test_admin_can_reset_staff_password_from_users_panel(): void
    {
        $adminRole = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Administrator']
        );

        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->roles()->sync([$adminRole->id]);

        $staff = User::factory()->create([
            'name' => 'Staff Member',
            'email' => 'staff_direct_reset@example.com',
            'password' => Hash::make('OldPassword111!'),
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post("/admin/users/{$staff->id}/reset-password", [
            'password' => 'DirectAdminResetPass2026!',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $staff->refresh();
        $this->assertTrue(Hash::check('DirectAdminResetPass2026!', $staff->password));
    }
}
