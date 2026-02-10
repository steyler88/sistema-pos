<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    /**
     * Cache key prefix
     */
    const CACHE_PREFIX = 'setting_';
    const CACHE_DURATION = 3600; // 1 hora

    /**
     * Obtener un valor de configuración
     * 
     * @param string $key Clave de la configuración
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember(self::CACHE_PREFIX . $key, self::CACHE_DURATION, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Establecer un valor de configuración
     * 
     * @param string $key Clave
     * @param mixed $value Valor
     * @param string $group Grupo (general, business, security)
     * @param string $type Tipo (string, boolean, integer, json)
     * @param string|null $description Descripción
     * @return Setting
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'string', ?string $description = null)
    {
        // Limpiar caché
        Cache::forget(self::CACHE_PREFIX . $key);

        // Convertir el valor al formato de almacenamiento
        $storedValue = self::prepareValue($value, $type);

        // Actualizar o crear
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $storedValue,
                'group' => $group,
                'type' => $type,
                'description' => $description,
            ]
        );

        return $setting;
    }

    /**
     * Verificar si existe una configuración
     * 
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Eliminar una configuración
     * 
     * @param string $key
     * @return bool
     */
    public static function remove(string $key): bool
    {
        Cache::forget(self::CACHE_PREFIX . $key);
        return self::where('key', $key)->delete();
    }

    /**
     * Obtener todas las configuraciones de un grupo
     * 
     * @param string $group
     * @return \Illuminate\Support\Collection
     */
    public static function getGroup(string $group)
    {
        return self::where('group', $group)->get()->mapWithKeys(function ($setting) {
            return [$setting->key => self::castValue($setting->value, $setting->type)];
        });
    }

    /**
     * Limpiar toda la caché de configuraciones
     */
    public static function clearCache(): void
    {
        // Obtener todas las keys y limpiar su caché
        self::all()->each(function ($setting) {
            Cache::forget(self::CACHE_PREFIX . $setting->key);
        });
    }

    /**
     * Convertir el valor según su tipo antes de almacenar
     * 
     * @param mixed $value
     * @param string $type
     * @return string
     */
    protected static function prepareValue($value, string $type): string
    {
        return match($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) intval($value),
            'json' => json_encode($value),
            default => (string) $value,
        };
    }

    /**
     * Convertir el valor almacenado al tipo correcto
     * 
     * @param string|null $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue(?string $value, string $type)
    {
        if ($value === null) {
            return null;
        }

        return match($type) {
            'boolean' => $value === '1' || $value === 'true',
            'integer' => intval($value),
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Boot del modelo - Limpiar caché al guardar/eliminar
     */
    protected static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget(self::CACHE_PREFIX . $setting->key);
        });

        static::deleted(function ($setting) {
            Cache::forget(self::CACHE_PREFIX . $setting->key);
        });
    }
}
