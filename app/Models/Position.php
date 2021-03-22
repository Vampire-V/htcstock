<?php

namespace App\Models;

use App\Relations\PositionTrait;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use PositionTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
