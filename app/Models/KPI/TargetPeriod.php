<?php

namespace App\Models\KPI;

use App\Http\Filters\KPI\PeriodFilter;
use App\Relations\TargetPeriodTrait;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TargetPeriod extends Model
{
    use TargetPeriodTrait;
    protected $table = 'kpi_target_periods';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'year'
    ];

    protected $guarded = [];

    // service เรียกใช้ Filter
    public function scopeFilter(Builder $builder, $request)
    {
        return (new PeriodFilter($request))->filter($builder);
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        $dateObj = DateTime::createFromFormat('!m', \intval($value));
        return $dateObj->format('F');
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = date("m", mktime(0, 0, 0, $value, date('d'), date('Y')));
    }
}
