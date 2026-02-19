<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number')->unique(); // Número de venta único
            $table->decimal('subtotal', 10, 2);
            $table->decimal('iva', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('total_items'); // Cantidad de productos diferentes
            $table->integer('total_units'); // Cantidad total de unidades vendidas
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Usuario que realizó la venta
            $table->json('items'); // Detalles de los productos vendidos
            $table->timestamps();
            
            // Índices para mejorar rendimiento en reportes
            $table->index('created_at');
            $table->index(['created_at', 'total']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
