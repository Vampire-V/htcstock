<?php

namespace App\Models\IT;

use App\Http\Filters\IT\AccessoriesManagementFilter;
use App\Relations\AccessoriesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Accessories extends Model
{
    use AccessoriesTrait;
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'access_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_name', 'unit', 'image', 'created_at'
    ];
    
    public function scopeFilter(Builder $builder, $request)
    {
        return (new AccessoriesManagementFilter($request))->filter($builder);
    }
}
