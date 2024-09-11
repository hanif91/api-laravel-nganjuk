<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTagihanTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function check_get_tagihan_status(): void
    {
        $response = $this->get('/api/v1/get');

        $response->assertStatus(200);
    }
}
