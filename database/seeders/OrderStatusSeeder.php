<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = new OrderStatus();
        $status->name = 'paid';
        $status->save();

        $status = new OrderStatus();
        $status->name = 'completed';
        $status->save();
    }
}
