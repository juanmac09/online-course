<?php

namespace Tests\Feature\Role;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleGetTest extends TestCase
{
    /**
     * Test that all valid roles are retrieved.
     *
     * @return void
     */
    public function test_all_valid_role(): void
    {
        $data = [
            'perPage' => rand(1, 50),
            'page' => rand(1, 10),
        ];

        $url = $this->prefix . 'role/all?' . http_build_query($data);

        $response = $this->actingAs($this->user, 'api')->withHeaders($this->headers)->get($url, $data);

        $response->assertStatus(200);
    }



    /**
     * Test that all invalid roles are not retrieved.
     *
     * This test checks if the API endpoint for retrieving all roles returns a 422 status code when no parameters are provided.
     *
     * @return void
     */
    public function test_all_invalid_role(): void
    {
        $data = [];

        $url = $this->prefix . 'role/all?' . http_build_query($data);

        $response = $this->actingAs($this->user, 'api')->withHeaders($this->headers)->get($url, $data);

        $response->assertStatus(422);
    }
}
