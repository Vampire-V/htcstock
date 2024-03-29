<?php

namespace App\Models\Legal;

use App\Enum\ContractEnum;
use App\Http\Filters\Legal\ContractRequestFilter;
use App\Models\User;
use App\Relations\LegalContractTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LegalContract extends Model
{
    use LegalContractTrait;

    protected $guarded = [];

    protected $with = ['legalContractDest', 'approvalDetail', 'createdBy', 'LegalAction', 'legalAgreement'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = auth()->id();
            $query->level = 0;
            $created = User::find(auth()->id());
            $query->checked_by = $created->department->legalApprove->where('levels',1)->first()->user_id;
        });

        static::updating(function ($query) {
            if ($query->status === ContractEnum::P) {
                $query->providing_at = \now();
            }
        });
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new ContractRequestFilter($request))->filter($builder);
    }
}
