<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Agregar campo para tipo de envase
            $table->string('package_type')->default('Caja')->after('laboratory');
            
            // Hacer presentation nullable ya que ahora se genera automáticamente
            $table->string('presentation')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('package_type');
        });
    }
};
