<?php

namespace App\Models\Legal;

use App\Relations\LegalPaymentTypeTrait;
use Illuminate\Database\Eloquent\Model;

class LegalPaymentType extends Model
{
    use LegalPaymentTypeTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'agreement_id'
    ];
}
