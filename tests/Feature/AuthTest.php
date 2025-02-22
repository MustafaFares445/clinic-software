<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Clinic;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    private Clinic $clinic;
    private User $authUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a clinic and authenticated user for tests
        $this->clinic = Clinic::factory()->create();
        $this->authUser = User::factory()->create([
            'clinic_id' => $this->clinic->id
        ]);
    }

    public function test_authenticated_user_can_register_new_user(): void
    {
        Sanctum::actingAs($this->authUser);

        $userData = [
            'fullName' => 'Musatafa Fares',
            'email' => 'mustafa.fares@gmail.com',
            'password' => 'password123',
            'username' => 'mustafa.fares',
            'clinic_id' => $this->clinic->id,
            'role' => 'user'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'accessToken',
                'tokenType',
                'user' => [
                    'id',
                    'fullName',
                    'email',
                    'username'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'username' => $userData['username'],
            'clinic_id' => $this->clinic->id
        ]);
    }

    public function test_unauthenticated_user_cannot_register(): void
    {
        $userData = [
            'fullName' => 'John Doe',
            'email' => 'john.doe@gmail.com',
            'password' => 'password123',
            'username' => 'johndoe'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_cannot_register_with_existing_email(): void
    {
        Sanctum::actingAs($this->authUser);

        // Create a user first
        User::factory()->create([
            'email' => 'john.doe@example.com',
            'username' => 'existinguser',
            'clinic_id' => $this->clinic->id
        ]);

        $userData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'username' => 'johndoe'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_login(): void
    {
        User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
            'is_banned' => false
        ]);

        $response = $this->postJson('/api/auth/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'accessToken',
                'tokenType',
                'user'
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'username' => 'nonexistent',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized']);
    }

    public function test_banned_user_cannot_login(): void
    {
        User::factory()->create([
            'username' => 'banneduser',
            'password' => bcrypt('password123'),
            'is_banned' => true
        ]);

        $response = $this->postJson('/api/auth/login', [
            'username' => 'banneduser',
            'password' => 'password123'
        ]);

        $response->assertStatus(403)
            ->assertJson(['error' => 'Your account is banned. Please contact your administrator.']);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
