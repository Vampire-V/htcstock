<?php

namespace App\Models\Legal;

use App\Relations\LegalApprovalDetailTrait;
use Illuminate\Database\Eloquent\Model;

class LegalApprovalDetail extends Model
{
    use LegalApprovalDetailTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'legal_approval_details';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contract_id', 'user_id', 'status', 'levels', 'comment'
    ];
}
