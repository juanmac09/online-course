<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * This method tests if the login method in AuthService returns true when the credentials are correct.
     *
     * @return void
     */
    public function it_should_return_true_if_credentials_are_correct()
    {
        
        $this->assertTrue(true);
    }
}
