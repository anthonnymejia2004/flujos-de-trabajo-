<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Stock suelto (unidades de una caja abierta)
            $table->integer('stock_loose')->default(0)->after('units_per_box');
            
            // Precio de venta de la caja completa
            $table->decimal('sale_price_box', 10, 2)->nullable()->after('profit_amount');
            
            // Precio de venta unitario (por pastilla/sobre)
            $table->decimal('sale_price_unit', 10, 4)->nullable()->after('sale_price_box');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_loose', 'sale_price_box', 'sale_price_unit']);
        });
    }
};
