<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::query()->create([
            "name" => "Adminsitrator",
            "email" => "admin@admin.com",
            "password" => bcrypt("admin"),
        ]);

    }
}
