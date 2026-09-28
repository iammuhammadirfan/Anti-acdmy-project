<?php

namespace Tests\Feature;

use App\Models\IetsResult;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminResultsTest extends TestCase
{
    public function test_admin_can_view_results_index(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create(['is_admin' => true]);
        }

        $response = $this->actingAs($user)->get('/admin/iets/results');
        $response->assertStatus(200);
        $response->assertSee('Student Result Cards');
        $response->assertSee('IELTS');
        $response->assertSee('PTE');
        $response->assertSee('TOEFL');
    }

    public function test_admin_can_view_create_page(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/admin/iets/results/create');
        $response->assertStatus(200);
        $response->assertSee('Upload Official Result Card Banner');
        $response->assertSee('Student Name');
        $response->assertSee('IELTS');
        $response->assertSee('PTE');
        $response->assertSee('TOEFL');
    }
}
