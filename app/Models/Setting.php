<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'general', string $description = null): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
                'is_active' => true,
            ]
        );
    }

    /**
     * Get all settings by group
     */
    public static function getGroup(string $group): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('group', $group)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get bank payment settings
     */
    public static function getBankSettings(): array
    {
        return [
            'bank_name' => static::get('bank_name', 'Association Westmount'),
            'bank_account' => static::get('bank_account', '1234567890'),
            'bank_transit' => static::get('bank_transit', '00123'),
            'bank_swift' => static::get('bank_swift', ''),
            'bank_address' => static::get('bank_address', ''),
            'bank_instructions' => static::get('bank_instructions', 'Veuillez inclure votre numéro de membre dans la référence du virement.'),
        ];
    }
}