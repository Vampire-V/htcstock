<?php

namespace App\Models\Legal;

use App\Relations\LegalComercialListTrait;
use Illuminate\Database\Eloquent\Model;

class LegalComercialList extends Model
{
    use LegalComercialListTrait;

    protected $casts = [
        'qty' => 'float',
        'unit_price' => 'float',
        'price' => 'float',
        'discount' => 'float',
        'amount' => 'float',
        "road" => 'int',
        "building" => 'int',
        "toilet" => 'int',
        "canteen" => 'int',
        "washing" => 'int',
        "water" => 'int',
        "mowing" => 'int',
        "general" => 'int',
    ];

    protected $guarded = [];
}
