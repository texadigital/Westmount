<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = [
        'page',
        'title',
        'content',
        'meta_title',
        'meta_description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get content for a specific page
     */
    public static function getPageContent($page)
    {
        return static::where('page', $page)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get all active pages
     */
    public static function getAllPages()
    {
        return static::where('is_active', true)
            ->orderBy('page')
            ->get();
    }
}

