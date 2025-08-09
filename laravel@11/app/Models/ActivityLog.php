<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends BaseModel
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'company_id',
        'action_type',
        'description',
        'related_table',
        'related_id',
        'ip_address',
        'created_at',
    ];

    public $timestamps = false;
}
