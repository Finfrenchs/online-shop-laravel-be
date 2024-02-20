<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(9)->create();

        $user = User::factory()->create([
            'name' => 'Admin Kelvin',
            'email' => 'kelvin@admin.com',
            'password' => Hash::make('admin123'),
            'phone' => '089789706789',
            'roles' => 'ADMIN',
        ]);
    }
}
