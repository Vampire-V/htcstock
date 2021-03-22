<?php

namespace App\Models\Legal;

use App\Relations\LegalActionTrait;
use Illuminate\Database\Eloquent\Model;

class LegalAction extends Model
{
    use LegalActionTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

}
