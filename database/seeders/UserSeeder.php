<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@example.com';
        $user->password = Hash::make('12345678');
        $user->save();

        Store::create([
            'user_id' => $user->id
        ]);

        $user->roles()->attach([1,2]);

        $user = new User();
        $user->name = 'Client';
        $user->email = 'client@example.com';
        $user->password = Hash::make('12345678');
        $user->save();

        Store::create([
            'user_id' => $user->id
        ]);

        $user->roles()->attach(2);
    }
}
