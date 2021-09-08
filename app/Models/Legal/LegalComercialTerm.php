<?php

namespace App\Models\Legal;

use App\Relations\LegalComercialTermTrait;
use Illuminate\Database\Eloquent\Model;

class LegalComercialTerm extends Model
{
    use LegalComercialTermTrait;
    protected $guarded = [];
    protected $dates = ['dated', 'delivery_date'];
}
