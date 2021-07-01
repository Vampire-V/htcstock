<?php

namespace App\Models\KPI;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserApprove extends Model
{
    protected $table = 'kpi_users_approve';

    protected $casts = [
        'level' => 'integer'
    ];

    protected $guarded = [];
    protected $with = ['user_approve','created_by','modify_by'];
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

    public function user_approve()
    {
        return $this->belongsTo(User::class,'user_approve')->withDefault();
    }

    public function created_by()
    {
        return $this->belongsTo(User::class,'created_by')->withDefault();
    }

    public function modify_by()
    {
        return $this->belongsTo(User::class,'modify_by')->withDefault();
    }
}
