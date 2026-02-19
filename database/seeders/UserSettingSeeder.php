<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserSetting;

class UserSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            [
                'key' => 'empresa_nombre',
                'value' => 'Pharma-Sync',
                'type' => 'string',
                'description' => 'Nombre de la empresa'
            ],
            [
                'key' => 'empresa_descripcion',
                'value' => 'Sistema de Farmacia',
                'type' => 'string',
                'description' => 'Descripción de la empresa'
            ],
            [
                'key' => 'iva_global',
                'value' => '15',
                'type' => 'float',
                'description' => 'IVA global por defecto (%)'
            ],
            [
                'key' => 'margen_ganancia_global',
                'value' => '30',
                'type' => 'float',
                'description' => 'Margen de ganancia global por defecto (%)'
            ],
            [
                'key' => 'moneda',
                'value' => 'USD',
                'type' => 'string',
                'description' => 'Moneda del sistema'
            ],
            [
                'key' => 'stock_minimo',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Stock mínimo de alerta'
            ],
            [
                'key' => 'dias_alerta_vencimiento',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Días de alerta de vencimiento'
            ],
            [
                'key' => 'tema',
                'value' => 'light',
                'type' => 'string',
                'description' => 'Tema visual del sistema'
            ]
        ];

        foreach ($defaultSettings as $setting) {
            UserSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
