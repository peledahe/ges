<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RootUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'root@ges.local'],
            [
                'name' => 'Administrador Root',
                'password' => Hash::make('password'),
                'role' => 'root',
                'tenant_id' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
