<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Employee;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting safe database seeder...');

        /**
         * 🟤 1. Tambah kategori dasar (kalau belum ada)
         */
        $categories = ['Coffee', 'Non-Coffee', 'Food', 'Snack'];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
        $this->command->info('✅ Default categories checked/created.');

        /**
         * 🟤 2. Tambah akun admin default (kalau belum ada)
         *     — supaya temanmu bisa login langsung
         */
        if (!Employee::where('email', 'admin@senandungsenja.com')->exists()) {
            Employee::create([
                'name' => 'Admin Senandung Senja',
                'email' => 'admin@senandungsenja.com',
                'password' => 'admin123', // 🔒 otomatis di-hash dari model
                'position' => 'admin',
                'status' => 'active',
            ]);
            $this->command->info('👤 Default admin created: admin@senandungsenja.com / admin123');
        } else {
            $this->command->warn('⚠️ Admin already exists, skipped.');
        }

        $this->command->info('✅ Seeder completed safely — no data loss!');
    }
}
