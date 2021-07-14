<?php

namespace App\Models\Legal;

use App\Http\Filters\Legal\ContractRequestFilter;
use App\Models\User;
use App\Relations\LegalContractTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LegalContract extends Model
{
    use LegalContractTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'comment', 'action_id', 'agreement_id', 'company_name', 'company_cer', 'representative', 'representative_cer', 'address',
        'contract_dest_id', 'requestor_by', 'checked_by', 'created_by', 'trash'
    ];

    protected $with = ['legalContractDest','approvalDetail','createdBy','LegalAction','legalAgreement'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = auth()->id();
            $query->checked_by = User::where('email', 'pratchaya.g@haier.co.th')->first()->id;
        });
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new ContractRequestFilter($request))->filter($builder);
    }
}
