<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'event',
        'subject',
        'body',
        'variables',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * Get template by event and type
     */
    public static function getTemplate($event, $type = 'email')
    {
        return static::where('event', $event)
            ->where('type', $type)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Render template with variables
     */
    public function render($variables = [])
    {
        $subject = $this->subject;
        $body = $this->body;

        foreach ($variables as $key => $value) {
            $subject = str_replace("{{$key}}", $value, $subject);
            $body = str_replace("{{$key}}", $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    /**
     * Get available variables for this template
     */
    public function getAvailableVariables()
    {
        return $this->variables ?? [];
    }
}
