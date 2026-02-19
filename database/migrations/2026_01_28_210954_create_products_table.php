<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('barcode')->unique(); // Para tu pistola de códigos
            $table->string('name');
            $table->string('laboratory')->nullable(); // Ej: "Bayer"
            
            // Inventario: Cajas y Unidades
            $table->integer('stock_boxes')->default(0); // Columna "Stock (Cajas)"
            $table->integer('units_per_box')->default(1); // Capacidad de la caja
            $table->integer('stock_units')->default(0); // Columna "Stock (Unidades)"
            
            // Precios e IVA Híbrido
            $table->decimal('cost_price', 10, 2); // Precio Costo
            $table->decimal('iva_percentage', 5, 2)->nullable(); // IVA Individual (si es null, usa Global)
            $table->decimal('profit_margin', 5, 2)->default(25); // Tu margen de ganancia
            
            $table->date('expiration_date')->nullable(); // Para alertas de vencimiento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
