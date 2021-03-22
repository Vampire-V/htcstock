<?php

namespace App\Models\Legal;

use App\Relations\LegalComercialListTrait;
use Illuminate\Database\Eloquent\Model;

class LegalComercialList extends Model
{
    use LegalComercialListTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description','unit_price','discount','amount','road','building','toilet',
        'canteen','washing','water','mowing','general','contract_dests_id'
    ];
}
