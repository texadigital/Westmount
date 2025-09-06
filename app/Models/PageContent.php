<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = [
        'page',
        'section', 
        'key',
        'value',
        'type',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get content by page, section, and key
     */
    public static function getContent($page, $section, $key, $default = null)
    {
        $content = static::where('page', $page)
            ->where('section', $section)
            ->where('key', $key)
            ->where('is_active', true)
            ->first();
            
        return $content ? $content->value : $default;
    }

    /**
     * Set content for a specific page, section, and key
     */
    public static function setContent($page, $section, $key, $value, $type = 'text')
    {
        return static::updateOrCreate(
            [
                'page' => $page,
                'section' => $section,
                'key' => $key
            ],
            [
                'value' => $value,
                'type' => $type,
                'is_active' => true
            ]
        );
    }

    /**
     * Get all content for a specific page
     */
    public static function getPageContent($page)
    {
        return static::where('page', $page)
            ->where('is_active', true)
            ->orderBy('section')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');
    }
}
