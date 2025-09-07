<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'user_type',
        'user_id',
        'ip_address',
        'user_agent',
        'description',
        'metadata',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        if ($this->user_type === 'admin') {
            return $this->belongsTo(User::class, 'user_id');
        } elseif ($this->user_type === 'member') {
            return $this->belongsTo(Member::class, 'user_id');
        }
        
        return null;
    }

    /**
     * Get the model that was affected
     */
    public function model()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        
        return null;
    }

    /**
     * Log an action
     */
    public static function log($event, $model = null, $oldValues = null, $newValues = null, $user = null, $description = null, $metadata = null)
    {
        $userType = null;
        $userId = null;
        
        if ($user) {
            if ($user instanceof User) {
                $userType = 'admin';
                $userId = $user->id;
            } elseif ($user instanceof Member) {
                $userType = 'member';
                $userId = $user->id;
            }
        }

        return static::create([
            'event' => $event,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'user_type' => $userType,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Get formatted changes
     */
    public function getFormattedChanges()
    {
        if (!$this->old_values || !$this->new_values) {
            return [];
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            $oldValue = $this->old_values[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }
}
