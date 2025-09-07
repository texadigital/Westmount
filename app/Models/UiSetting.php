<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UiSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'accent_color',
        'background_color',
        'text_color',
        'logo_url',
        'favicon_url',
        'site_title',
        'site_description',
        'contact_email',
        'contact_phone',
        'footer_text',
        'custom_css',
        'is_active',
    ];

    protected $casts = [
        'custom_css' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active UI setting
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get CSS variables for the UI
     */
    public function getCssVariables()
    {
        return [
            '--primary-color' => $this->primary_color,
            '--secondary-color' => $this->secondary_color,
            '--accent-color' => $this->accent_color,
            '--background-color' => $this->background_color,
            '--text-color' => $this->text_color,
        ];
    }

    /**
     * Get custom CSS as string
     */
    public function getCustomCssString()
    {
        if (!$this->custom_css) {
            return '';
        }

        $css = '';
        foreach ($this->custom_css as $selector => $rules) {
            $css .= $selector . ' {';
            foreach ($rules as $property => $value) {
                $css .= $property . ': ' . $value . ';';
            }
            $css .= '}';
        }

        return $css;
    }
}
