<?php

namespace App\Models\KPI;

use App\Relations\UserApproveTrait;
use Illuminate\Database\Eloquent\Model;

class UserApprove extends Model
{
    use UserApproveTrait;
    protected $table = 'kpi_users_approve';

    protected $casts = [
        'level' => 'integer'
    ];

    protected $guarded = [];
    protected $with = ['approveBy','createdBy','modifyBy'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = auth()->id();
            $query->modify_by = auth()->id();
        });

        static::updating(function ($query) {
            $query->modify_by = auth()->id();
        });
    }
}
