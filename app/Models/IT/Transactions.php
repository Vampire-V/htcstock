<?php

namespace App\Models\IT;

use App\Http\Filters\IT\TransactionManagementFilter;
use App\Relations\TransactionsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use TransactionsTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_id', 'qty', 'trans_type', 'trans_by', 'trans_desc', 'ir_no', 'ir_date', 'po_no', 'invoice_no', 'unit_cost', 'vendor_id', 'ref_no', 'created_by'
    ];

    protected $dates = ['ir_date'];
    
    public function scopeFilter(Builder $builder, $request)
    {
        return (new TransactionManagementFilter($request))->filter($builder);
    }
}
