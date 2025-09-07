<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bank_name',
        'account_holder',
        'account_number',
        'transit_number',
        'institution_number',
        'swift_code',
        'routing_number',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active bank setting
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get bank information for display
     */
    public function getInfo()
    {
        return [
            'bank_name' => $this->bank_name,
            'account_holder' => $this->account_holder,
            'account_number' => $this->account_number,
            'transit_number' => $this->transit_number,
            'institution_number' => $this->institution_number,
            'swift_code' => $this->swift_code,
            'routing_number' => $this->routing_number,
            'instructions' => $this->instructions,
        ];
    }
}
