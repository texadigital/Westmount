<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_name',
        'field_type',
        'rules',
        'custom_messages',
        'is_required',
        'min_length',
        'max_length',
        'pattern',
        'help_text',
        'is_active',
    ];

    protected $casts = [
        'rules' => 'array',
        'custom_messages' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get validation rules for a specific field
     */
    public static function getRulesForField($fieldName)
    {
        $setting = static::where('field_name', $fieldName)
            ->where('is_active', true)
            ->first();

        if (!$setting) {
            return [];
        }

        $rules = $setting->rules ?? [];
        
        // Add length rules if specified
        if ($setting->min_length) {
            $rules[] = 'min:' . $setting->min_length;
        }
        
        if ($setting->max_length) {
            $rules[] = 'max:' . $setting->max_length;
        }
        
        // Add pattern rule if specified
        if ($setting->pattern) {
            $rules[] = 'regex:' . $setting->pattern;
        }

        return $rules;
    }

    /**
     * Get custom messages for a specific field
     */
    public static function getMessagesForField($fieldName)
    {
        $setting = static::where('field_name', $fieldName)
            ->where('is_active', true)
            ->first();

        return $setting ? ($setting->custom_messages ?? []) : [];
    }

    /**
     * Get help text for a specific field
     */
    public static function getHelpTextForField($fieldName)
    {
        $setting = static::where('field_name', $fieldName)
            ->where('is_active', true)
            ->first();

        return $setting ? $setting->help_text : null;
    }
}
