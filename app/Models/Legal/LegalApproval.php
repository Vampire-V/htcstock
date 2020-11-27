<?php

namespace App\Models\Legal;

use App\Models\IT\Department;
use App\User;
use Illuminate\Database\Eloquent\Model;

class LegalApproval extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'levels','user_id','department_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'contract_id');
    }
}