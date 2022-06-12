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
        $user->name = 'Ayenew Yihune';
        $user->email = 'ayennew@gmail.com';
        $user->password = Hash::make('12345678910');
        $user->save();

        Store::create([
            'user_id' => $user->id
        ]);

        $user->roles()->attach([1,2]);
    }
}
