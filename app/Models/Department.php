<?php

namespace App\Models;

use App\Relations\DepartmentTrait;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use DepartmentTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
