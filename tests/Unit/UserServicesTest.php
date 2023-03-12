<?php

namespace Tests\Unit;

use App\Services\UserServices;
use Tests\TestCase;

class UserServicesTest extends TestCase
{
    public function testCreateUserSuccessful()
    {
        $stub = $this->createStub(UserServices::class);

        $stub->method('checkIfEmailIsAlreadyInUse')
            ->willReturn(true);
    }

}
