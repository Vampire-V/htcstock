<?php

namespace App\Models\Legal;

use App\Relations\LegalContractDestTrait;
use Illuminate\Database\Eloquent\Model;

class LegalContractDest extends Model
{
    use LegalContractDestTrait;
    protected $guarded = [];
}
