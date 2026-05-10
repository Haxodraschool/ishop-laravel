<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,       // users trước (orders cần users_id)
            CategorySeeder::class,   // categories trước (products cần categories_id)
            ProductSeeder::class,    // products trước (order_items cần products_id)
            OrderSeeder::class,      // orders trước (order_items cần orders_id)
            OrderItemSeeder::class,  // order_items sau cùng
            RoleSeeder::class,       // roles & permissions sau khi user đã có
        ]);
    }
}
