<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nombre'     => 'Admin',
            'ap_paterno' => 'DPL',
            'ap_materno' => 'Vitae',
            'email'      => 'admin@dpl.com',
            'password'   => Hash::make('password'),
        ]);
    }
}
