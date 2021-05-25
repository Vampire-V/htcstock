<?php

namespace App\Models\KPI;

use App\Http\Filters\KPI\TemplateFilter;
use App\Models\Department;
use App\Relations\TemplateTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use TemplateTrait;
    protected $table = 'kpi_templates';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'department_id'
    ];

    protected $casts = [
        'weight_kpi' => 'float',
        'weight_key_task' => 'float',
        'weight_omg' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->user_created = auth()->id();
        });
    }

    // service เรียกใช้ Filter
    public function scopeFilter(Builder $builder, $request)
    {
        return (new TemplateFilter($request))->filter($builder);
    }
}
