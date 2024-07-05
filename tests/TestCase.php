<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public $prefix = '/api/';
    public $headers = [
        'Accept' => 'application/json',
    ];

    public $user;
    /**
     * Set up the environment before running a test.
     *
     * This method is called before running each test method in the class.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();


        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
        ]);
    }
    
    /**
     * Clean up the environment after running a test.
     *
     * This method is called after each test method in the class.
     *
     * @return void
     */
    public  function tearDown(): void
    {
        // Eliminar el usuario después de cada prueba
        if ($this->user) {
            $this->user->delete();
        }

        parent::tearDown();
    }
}
