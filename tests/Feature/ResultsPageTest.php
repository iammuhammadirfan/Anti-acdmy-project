<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResultsPageTest extends TestCase
{
    public function test_home_page_renders_with_results_slider(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Student Result');
        $response->assertSee('IELTS');
        $response->assertSee('PTE');
        $response->assertSee('TOEFL');
    }

    public function test_iets_results_page_renders(): void
    {
        $response = $this->get('/iets/results');
        $response->assertStatus(200);
        $response->assertSee('Student');
        $response->assertSee('Hall of Fame');
        $response->assertSee('IELTS');
        $response->assertSee('PTE');
        $response->assertSee('TOEFL');
    }
}
