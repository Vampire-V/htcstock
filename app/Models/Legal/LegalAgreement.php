<?php

namespace App\Models\Legal;

use App\Relations\LegalAgreementTrait;
use Illuminate\Database\Eloquent\Model;

class LegalAgreement extends Model
{
    use LegalAgreementTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
