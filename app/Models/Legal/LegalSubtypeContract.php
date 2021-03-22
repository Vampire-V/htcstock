<?php

namespace App\Models\Legal;

use App\Relations\LegalSubtypeContractTrait;
use Illuminate\Database\Eloquent\Model;

class LegalSubtypeContract extends Model
{
    use LegalSubtypeContractTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'agreement_id'
    ];
}
