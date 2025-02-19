<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Http\Requests\LoginUserRequest;
use Tests\TestCase;
use App\Interfaces\UserInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use App\Models\User;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private $mockUserRepository;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockUserRepository = Mockery::mock(UserRepository::class);
        $this->app->instance(UserInterface::class, $this->mockUserRepository);
    }

    public function test_user_registration_success()
    {
        $data = [
            'name' => 'Ayaan K',
            'email' => 'ayaan@gmail.com',
            'password' => 'test@123',
            'password_confirmation' => 'test@123',
        ];

        $this->mockUserRepository->shouldReceive('createUser')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn(new User($data));

        $response = $this->postJson(route('register'), $data);

        $response->assertStatus(201);
    }

    public function test_user_registration_failure()
    {
        $this->mockUserRepository->shouldReceive('createUser')
            ->once()
            ->andThrow(new Exception('Something went wrong'));

        $response = $this->postJson(route('register'), [
            'name' => 'Ayaan K',
            'email' => 'ayaan@gmail.com',
            'password' => 'test@123',
            'password_confirmation' => 'test@123',
        ]);

        $response->assertStatus(500)
            ->assertJson([
            'status' => 'error',
            'message' => 'Something went wrong',
            ]);
    }

    public function test_user_registration_validation_failure()
    {
        $data = [
            'name' => 'Ayaan K',
            'email' => 'ayaan@gmail.com',
            'password' => 'test@123',
        ];

        $response = $this->postJson(route('register'), $data);

        $response->assertStatus(422);
    }

    public function test_user_login_success()
    {
        $data = [
            'email' => 'johndoe@example.com',
            'password' => 'password123'
        ];

        Sanctum::actingAs(User::factory()->create());

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
        ]);

        $request = Mockery::mock(LoginUserRequest::class);
        $request->shouldReceive('email')->andReturn('johndoe@example.com');
        $request->shouldReceive('password')->andReturn('password123');

        $user = Mockery::mock(User::class);
        $user->shouldReceive('where')
            ->with('email', 'johndoe@example.com')
            ->andReturnSelf();
        $user->shouldReceive('getAttribute')
            ->andReturn('mocked-token');
        $user->shouldReceive('first')
            ->andReturn($user);
        $user->shouldReceive('createToken')
            ->andReturnSelf();

        $this->mockUserRepository->shouldReceive('getUserByEmail')
            ->once()
            ->with($data['email'])
            ->andReturn($user);

        $response = $this->postJson(route('login'), [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'status' => 'success',
            'message' => 'Login successfull!',
            'data' => ['token' => 'mocked-token'],
        ]);
    }
}
