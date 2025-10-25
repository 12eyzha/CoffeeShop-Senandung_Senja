<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('transactions')->delete();
        DB::table('menus')->delete();
        DB::table('categories')->delete();
        DB::table('users')->delete();

        // Create default admin user
        DB::table('users')->insert([
            'name' => 'Admin Senandung Senja',
            'email' => 'admin@senandungsenja.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create kasir user
        DB::table('users')->insert([
            'name' => 'Kasir Coffee Shop',
            'email' => 'kasir@senandungsenja.com',
            'password' => Hash::make('kasir123'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Coffee', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non-Coffee', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Food', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Snack', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('categories')->insert($categories);

        // Create menus - Coffee Category (ID: 1)
        $menus = [
            // Coffee (Category ID: 1)
            [
                'name' => 'Espresso', 
                'description' => 'Kopi espresso murni dengan rasa bold dan kuat', 
                'price' => 18000, 
                'category_id' => 1, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Latte', 
                'description' => 'Espresso dengan steamed milk creamy', 
                'price' => 25000, 
                'category_id' => 1, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cappuccino', 
                'description' => 'Espresso dengan foam milk yang lembut', 
                'price' => 23000, 
                'category_id' => 1, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Americano', 
                'description' => 'Espresso dengan air panas, rasa kopi yang clean', 
                'price' => 20000, 
                'category_id' => 1, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Macchiato', 
                'description' => 'Espresso dengan sedikit foam milk', 
                'price' => 22000, 
                'category_id' => 1, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cold Brew', 
                'description' => 'Kopi seduhan dingin dengan rasa smooth', 
                'price' => 28000, 
                'category_id' => 1, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Non-Coffee (Category ID: 2)
            [
                'name' => 'Chocolate Ice', 
                'description' => 'Minuman coklat dingin dengan whipped cream', 
                'price' => 22000, 
                'category_id' => 2, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Green Tea Latte', 
                'description' => 'Teh hijau premium dengan susu steamed', 
                'price' => 24000, 
                'category_id' => 2, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Red Velvet Latte', 
                'description' => 'Latte dengan rasa red velvet yang unik', 
                'price' => 26000, 
                'category_id' => 2, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lemon Tea', 
                'description' => 'Teh lemon segar dan menyegarkan', 
                'price' => 18000, 
                'category_id' => 2, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Food (Category ID: 3)
            [
                'name' => 'Sandwich', 
                'description' => 'Roti isi sayur segar dan daging smoke beef', 
                'price' => 28000, 
                'category_id' => 3, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Croissant', 
                'description' => 'Croissant butter original dengan tekstur flaky', 
                'price' => 15000, 
                'category_id' => 3, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bagel', 
                'description' => 'Bagel dengan cream cheese dan smoked salmon', 
                'price' => 32000, 
                'category_id' => 3, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Quiche', 
                'description' => 'Pie gurih dengan isi sayuran dan keju', 
                'price' => 25000, 
                'category_id' => 3, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // Snack (Category ID: 4)
            [
                'name' => 'Chocolate Chip Cookie', 
                'description' => 'Cookie dengan chocolate chips yang melimpah', 
                'price' => 12000, 
                'category_id' => 4, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Brownies', 
                'description' => 'Brownies coklat rich dan moist', 
                'price' => 15000, 
                'category_id' => 4, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Muffin Blueberry', 
                'description' => 'Muffin dengan blueberry segar', 
                'price' => 18000, 
                'category_id' => 4, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Banana Bread', 
                'description' => 'Roti pisang lembut dengan kacang walnut', 
                'price' => 20000, 
                'category_id' => 4, 
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        
        DB::table('menus')->insert($menus);

        // Create sample transactions
        $transactions = [
            [
                'transaction_code' => 'TRX-' . time() . '-001',
                'items' => json_encode([
                    ['id' => 1, 'name' => 'Espresso', 'price' => 18000, 'quantity' => 1],
                    ['id' => 11, 'name' => 'Sandwich', 'price' => 28000, 'quantity' => 1]
                ]),
                'total_amount' => 46000,
                'payment_method' => 'cash',
                'user_id' => 1,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2)
            ],
            [
                'transaction_code' => 'TRX-' . time() . '-002',
                'items' => json_encode([
                    ['id' => 2, 'name' => 'Latte', 'price' => 25000, 'quantity' => 2],
                    ['id' => 15, 'name' => 'Chocolate Chip Cookie', 'price' => 12000, 'quantity' => 1]
                ]),
                'total_amount' => 62000,
                'payment_method' => 'qris',
                'user_id' => 2,
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1)
            ],
            [
                'transaction_code' => 'TRX-' . time() . '-003',
                'items' => json_encode([
                    ['id' => 6, 'name' => 'Cold Brew', 'price' => 28000, 'quantity' => 1],
                    ['id' => 12, 'name' => 'Croissant', 'price' => 15000, 'quantity' => 2],
                    ['id' => 16, 'name' => 'Brownies', 'price' => 15000, 'quantity' => 1]
                ]),
                'total_amount' => 73000,
                'payment_method' => 'cash',
                'user_id' => 1,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30)
            ],
        ];

        DB::table('transactions')->insert($transactions);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin Login: admin@senandungsenja.com / password123');
        $this->command->info('Kasir Login: kasir@senandungsenja.com / kasir123');
    }
}