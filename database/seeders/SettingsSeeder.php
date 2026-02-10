<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========================================
        // GRUPO: GENERAL (Información de la Empresa)
        // ========================================
        $generalSettings = [
            [
                'key' => 'company_name',
                'value' => 'ELCHEPIZZA',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Nombre del negocio que aparece en los tickets',
            ],
            [
                'key' => 'company_ruc',
                'value' => '10447303766',
                'group' => 'general',
                'type' => 'string',
                'description' => 'RUC de la empresa',
            ],
            [
                'key' => 'company_address',
                'value' => 'Res. Praderas de Pariachi mz G lt 9 ATE',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Dirección completa del negocio',
            ],
            [
                'key' => 'company_phone',
                'value' => '952 208 570',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Teléfono de contacto',
            ],
            [
                'key' => 'company_website',
                'value' => 'www.elchepizza.pe',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Sitio web del negocio',
            ],
            [
                'key' => 'ticket_footer_message',
                'value' => 'GRACIAS CHE !',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Mensaje de despedida en el pie del ticket',
            ],
            [
                'key' => 'timezone',
                'value' => 'America/Lima',
                'group' => 'general',
                'type' => 'string',
                'description' => 'Zona horaria del sistema',
            ],
        ];

        // ========================================
        // GRUPO: BUSINESS (Reglas de Negocio)
        // ========================================
        $businessSettings = [
            [
                'key' => 'enable_multi_pricing',
                'value' => '0', // false por defecto
                'group' => 'business',
                'type' => 'boolean',
                'description' => 'Habilitar múltiples precios por producto (Local, Rappi, Web)',
            ],
            [
                'key' => 'tax_rate',
                'value' => '0.18', // 18% IGV
                'group' => 'business',
                'type' => 'string',
                'description' => 'Tasa de IGV (0.18 = 18%)',
            ],
            [
                'key' => 'default_currency',
                'value' => 'S/',
                'group' => 'business',
                'type' => 'string',
                'description' => 'Símbolo de moneda predeterminado',
            ],
            [
                'key' => 'enable_inventory',
                'value' => '0',
                'group' => 'business',
                'type' => 'boolean',
                'description' => 'Habilitar control de inventario',
            ],
            [
                'key' => 'enable_discounts',
                'value' => '1',
                'group' => 'business',
                'type' => 'boolean',
                'description' => 'Permitir descuentos en órdenes',
            ],
        ];

        // ========================================
        // GRUPO: SECURITY (Seguridad y Usuarios)
        // ========================================
        $securitySettings = [
            [
                'key' => 'require_login',
                'value' => '1',
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Requerir login para acceder al POS',
            ],
            [
                'key' => 'session_timeout',
                'value' => '120', // 120 minutos
                'group' => 'security',
                'type' => 'integer',
                'description' => 'Tiempo de sesión en minutos',
            ],
            [
                'key' => 'enable_audit_log',
                'value' => '1',
                'group' => 'security',
                'type' => 'boolean',
                'description' => 'Registrar log de auditoría de acciones',
            ],
        ];

        // ========================================
        // GRUPO: PRINTING (Configuración de Impresión)
        // ========================================
        $printingSettings = [
            [
                'key' => 'printer_width',
                'value' => '58',
                'group' => 'printing',
                'type' => 'integer',
                'description' => 'Ancho del papel de impresora térmica (mm)',
            ],
            [
                'key' => 'auto_print',
                'value' => '1',
                'group' => 'printing',
                'type' => 'boolean',
                'description' => 'Imprimir automáticamente al finalizar orden',
            ],
            [
                'key' => 'print_copies',
                'value' => '1',
                'group' => 'printing',
                'type' => 'integer',
                'description' => 'Número de copias a imprimir',
            ],
        ];

        // ========================================
        // INSERTAR TODOS LOS SETTINGS
        // ========================================
        $allSettings = array_merge(
            $generalSettings,
            $businessSettings,
            $securitySettings,
            $printingSettings
        );

        foreach ($allSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Settings iniciales creados exitosamente');
        $this->command->info('📊 Total de configuraciones: ' . count($allSettings));
    }
}
