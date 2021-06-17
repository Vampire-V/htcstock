<?php

namespace App\Models\KPI;

use App\Relations\SettingActionTrait;
use Illuminate\Database\Eloquent\Model;

class SettingAction extends Model
{
    use SettingActionTrait;

    protected $table = 'kpi_setting_actions';
    protected $guarded = [];
}
