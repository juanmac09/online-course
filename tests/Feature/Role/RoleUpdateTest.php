<?php

namespace Tests\Feature\Role;

use App\Models\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleUpdateTest extends TestCase
{

    protected $role;

    public function setUp(): void
    {
        parent::setUp();


        $this->role = Role::create([
            'name' => 'Test Role',
        ]);
    }

    public function tearDown(): void
    {
        $this->role->delete();
        parent::tearDown();
    }
    /**
     * Test updating a valid role.
     */
    public function test_update_valid_role(): void
    {

        $data = [
            'id' => $this->role->id,
            'name' => 'New Test Role',
        ];

        $response = $this->actingAs($this->user, 'api')
            ->withHeaders($this->headers)
            ->put($this->prefix . 'role/update', $data);


        $response->assertStatus(200);
    }


    public function test_update_invalid_role(){
        $data = [
            'id' => 9999999999999999,
        ];

        $response = $this->actingAs($this->user, 'api')
            ->withHeaders($this->headers)
            ->put($this->prefix.'role/update', $data);

        $response->assertStatus(422);
    }
}
