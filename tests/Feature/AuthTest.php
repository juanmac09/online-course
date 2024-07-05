<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{

    /**
     * Test the login functionality with valid credentials.
     *
     * @return void
     */
    public function test_login_with_valid_credentials()
    {
        $response = $this->post($this -> prefix.'auth/login', [
            'email' => 'test@example.com',
            'password' => 'secret',
            'auth_type' => 1,
        ]);

        $response->assertStatus(200);
    }



    public function test_login_with_invalid_credentials(){
        
        $response = $this -> post($this -> prefix.'auth/login',[
            'email' => 'testwrong@example.com',
            'password' => 'wrong',
            'auth_type' => 1,
        ]);

        $response -> assertStatus(401);
    }
}
