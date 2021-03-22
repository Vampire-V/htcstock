<?php

namespace App\Models\Legal;

use App\Relations\LegalComercialTermTrait;
use Illuminate\Database\Eloquent\Model;

class LegalComercialTerm extends Model
{
    use LegalComercialTermTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'scope_of_work', 'location', 'purchase_order_no', 'quotation_no', 'dated', 'contract_period', 'untill',
        'delivery_date', 'to_manufacture', 'of', 'working_day', 'working_time', 'number_of_cook', 'comercial_ot',
        'number_of_doctor', 'number_of_sercurity_guard', 'number_of_subcontractor', 'number_of_agent', 'route', 'to',
        'dry_container_size', 'the_number_of_truck', 'purpose', 'promote_a_product',
        'road', 'building', 'toilet', 'canteen', 'washing', 'water', 'mowing', 'general'
    ];
    protected $dates = ['dated', 'contract_period', 'untill', 'delivery_date'];
}
