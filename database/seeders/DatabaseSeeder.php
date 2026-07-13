<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed demo lengkap (akun demo@example.com / demo1234 + data semua modul).
     */
    public function run(): void
    {
        $this->call(DemoSeeder::class);
    }
}
