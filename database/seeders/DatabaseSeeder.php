<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            "user_name" => "admin",
            "user_type" => 1,
            "org_id" => 8,
            "password" => Hash::make('12345678'),
            "first_name" => "Admin"
        ]);
    }
}
