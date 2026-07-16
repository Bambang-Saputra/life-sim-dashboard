<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Memastikan setiap halaman tetap sehat saat database kosong dan
 * menampilkan empty state bergambar (komponen <x-empty-art>).
 * Berjalan di sqlite in-memory, tidak menyentuh data asli.
 */
class EmptyStateTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): User
    {
        return User::factory()->create();
    }

    public function test_quests_page_shows_illustrated_empty_states(): void
    {
        $response = $this->actingAs($this->owner())->get('/quests');

        $response->assertOk()
            ->assertSee('empty-art', false)
            ->assertSee('NO QUESTS YET')
            ->assertSee('BELUM ADA HABIT');
    }

    public function test_finance_page_shows_illustrated_empty_states(): void
    {
        $response = $this->actingAs($this->owner())->get('/finance');

        $response->assertOk()
            ->assertSee('empty-art', false)
            ->assertSee('NO ENTRIES')
            ->assertSee('BELUM ADA BUDGET')
            ->assertSee('BELUM ADA LANGGANAN')
            ->assertSee('BELUM ADA TABUNGAN')
            ->assertSee('Belum ada expense untuk periode');
    }

    public function test_dashboard_shows_illustrated_empty_states(): void
    {
        $response = $this->actingAs($this->owner())->get('/dashboard');

        $response->assertOk()
            ->assertSee('empty-art', false)
            ->assertSee('Belum ada quest aktif.')
            ->assertSee('Belum ada transaksi.')
            ->assertSee('Belum ada koleksi.');
    }

    public function test_library_page_shows_illustrated_empty_state_when_apis_return_nothing(): void
    {
        Http::fake(['*' => Http::response([], 200)]);

        $response = $this->actingAs($this->owner())->get('/library');

        $response->assertOk()
            ->assertSee('empty-art', false);
    }
}
