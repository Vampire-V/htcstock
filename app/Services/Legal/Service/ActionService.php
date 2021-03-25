<?php

namespace App\Services\Legal\Service;

use App\Models\Legal\LegalAction;
use App\Services\BaseService;
use App\Services\Legal\Interfaces\ActionServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ActionService extends BaseService implements ActionServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param LegalAction $model
     */
    public function __construct(LegalAction $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return LegalAction::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return LegalAction::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
