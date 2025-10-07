<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Building;
use App\Models\Room;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Petugas User
        User::create([
            'name' => 'Petugas Kemahasiswaan',
            'nim' => 'P001',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // Create Mahasiswa User
        User::create([
            'name' => 'John Doe',
            'nim' => '2024001',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        // Create Buildings
        $buildings = [
            'Gedung A',
            'Gedung B',
            'Gedung C',
            'Perpustakaan',
            'Kantin',
        ];

        foreach ($buildings as $buildingName) {
            $building = Building::create(['name' => $buildingName]);
            
            // Create rooms for each building
            for ($i = 1; $i <= 5; $i++) {
                Room::create([
                    'building_id' => $building->id,
                    'name' => "Ruang {$i}",
                ]);
            }
        }

        // Create Categories
        $categories = [
            'Elektronik',
            'Alat Tulis',
            'Pakaian',
            'Aksesoris',
            'Kendaraan',
            'Dokumen',
            'Lainnya',
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }
    }
}
