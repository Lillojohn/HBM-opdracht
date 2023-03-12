<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserServices;
use App\Services\UserVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServicesTest extends TestCase
{
    use RefreshDatabase;

    private const EMAIL =  'test@mail.nl';

    public function testCreateUserSuccessful()
    {
        $userVerificationService = new UserVerificationService();
        $userServices = new UserServices($userVerificationService);

        $user = $userServices->createUser([
            'name' => 'test',
            'password' => 'test',
            'email' => self::EMAIL
        ]);

        $this->assertEquals(self::EMAIL, $user->email);
    }


    public function testCreateUserNotSuccessfulDueToDuplicate()
    {
        $userVerificationService = new UserVerificationService();
        $userServices = new UserServices($userVerificationService);

        $expectedUser = User::factory()->create();

        $user = $userServices->createUser([
            'name' => 'test',
            'password' => 'test',
            'email' => $expectedUser->email
        ]);

        $this->assertNull($user);
    }

}
