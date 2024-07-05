<?php

namespace Tests\Feature\Role;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleCreateTest extends TestCase
{
    /**
     * Test creating a valid role.
     *
     * @return void
     */
    public function test_create_valid_role(): void
    {
       
        $data = [
            'name' => 'Test Role',
        ];
        $response = $this->actingAs($this -> user, 'api') -> withHeaders($this -> headers )->post($this->prefix . 'role/create', $data);

        $response->assertStatus(200);
        
        $role = Role::where('name', 'Test Role')->first();
        $this->assertNotNull($role);
        $role -> delete();
    }


    /**
     * Test creating an invalid role.
     *
     * This test checks if the API returns a 422 status code when an invalid role is created.
     *
     * @return void
     */
    public function test_create_invalid_role(): void
    {
       
        $data = [
           'name' => '',
        ];
        $response = $this->actingAs($this ->user, 'api') -> withHeaders($this -> headers ) ->post($this->prefix.'role/create', $data);
        $response->assertStatus(422);

        $data['name'] = 'administrator';
        $response = $this->actingAs($this ->user, 'api') -> withHeaders($this -> headers ) ->post($this->prefix.'role/create', $data);
        $response->assertStatus(422);
        
    }
}
