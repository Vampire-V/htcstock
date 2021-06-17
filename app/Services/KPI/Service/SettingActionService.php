<?php

namespace App\Services\KPI\Service;

use App\Enum\KPIEnum;
use App\Models\KPI\SettingAction;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SettingActionService extends BaseService
{
    protected $periodService;
    /**
     * UserService constructor.
     *
     * @param SettingAction $model
     */
    public function __construct(SettingAction $model)
    {
        parent::__construct($model);
    }

    public function query(): Builder
    {
        try {
            return SettingAction::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return SettingAction::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function isNextStep($slug)
    {
        $result = true;
        try {
            $action = $this->findAction($slug);
            if ((date('d') - $action->end) > 0) {
                $result = $action->users->contains(fn($item) => $item->id === \auth()->id());
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $result;
    }

    private function findAction(string $slug)
    {
        try {
            return SettingAction::where('slug',$slug)->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
