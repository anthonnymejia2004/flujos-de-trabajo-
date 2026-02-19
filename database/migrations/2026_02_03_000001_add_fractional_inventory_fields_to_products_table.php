<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Verificar si las columnas no existen antes de agregarlas
            if (!Schema::hasColumn('products', 'code')) {
                $table->string('code')->nullable()->unique()->after('barcode');
            }
            if (!Schema::hasColumn('products', 'presentation')) {
                $table->string('presentation')->nullable()->after('laboratory');
            }
            if (!Schema::hasColumn('products', 'total_units')) {
                $table->integer('total_units')->default(0)->after('stock_loose');
            }
            if (!Schema::hasColumn('products', 'precio_venta')) {
                $table->decimal('precio_venta', 10, 2)->nullable()->after('sale_price_unit');
            }
            if (!Schema::hasColumn('products', 'precio_venta_unitario')) {
                $table->decimal('precio_venta_unitario', 10, 4)->nullable()->after('precio_venta');
            }
            if (!Schema::hasColumn('products', 'profit_amount_total')) {
                $table->decimal('profit_amount_total', 10, 2)->nullable()->after('profit_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = ['code', 'presentation', 'total_units', 'precio_venta', 'precio_venta_unitario', 'profit_amount_total'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
