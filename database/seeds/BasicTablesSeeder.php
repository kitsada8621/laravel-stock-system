<?php

use App\User;
use App\Users\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BasicTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'd_name' => 'คอมพิวเตอร์'
        ]);

        User::create([
            'name' => 'test test',
            'email' => 'test@example.com',
            'username' => 'test123',
            'password' => Hash::make('123456789'),
            'd_id' => 1,
            'role' => true,
        ]);
    }
}
