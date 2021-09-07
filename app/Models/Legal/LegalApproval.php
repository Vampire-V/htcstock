<?php

namespace App\Models\Legal;

use App\Relations\LegalApprovalTrait;
use Illuminate\Database\Eloquent\Model;

class LegalApproval extends Model
{
    use LegalApprovalTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
