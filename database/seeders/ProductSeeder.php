<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Asegúrate de que el modelo exista más adelante
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'barcode' => '7501234567890',
                'name' => 'Paracetamol 500mg',
                'laboratory' => 'Bayer',
                'stock_boxes' => 15,
                'units_per_box' => 20,
                'stock_units' => 300,
                'cost_price' => 45.00,
                'iva_percentage' => 0.00, // IVA Individual (Exento)
                'profit_margin' => 40.00,
                'expiration_date' => '2026-08-14',
            ],
            [
                'barcode' => '7509876543210',
                'name' => 'Ibuprofeno 400mg',
                'laboratory' => 'Genfar',
                'stock_boxes' => 3, // Saldrá en "Stock Bajo"
                'units_per_box' => 10,
                'stock_units' => 30,
                'cost_price' => 12.50,
                'iva_percentage' => null, // Usará el IVA GLOBAL del .env
                'profit_margin' => 30.00,
                'expiration_date' => '2026-03-19',
            ],
        ]);
    }
}