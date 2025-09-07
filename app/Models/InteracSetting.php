<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteracSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'security_question',
        'security_answer',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active Interac setting
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get Interac information for display
     */
    public function getInfo()
    {
        return [
            'email' => $this->email,
            'security_question' => $this->security_question,
            'security_answer' => $this->security_answer,
            'instructions' => $this->instructions,
        ];
    }
}
