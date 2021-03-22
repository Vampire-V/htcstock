<?php

namespace App\Models;

use App\Relations\DivisionTrait;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use DivisionTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

}
