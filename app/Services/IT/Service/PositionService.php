<?php

namespace App\Services\IT\Service;

use App\Models\Position;
use App\Models\User;
use App\Services\BaseService;
use App\Services\IT\Interfaces\PositionServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PositionService extends BaseService implements PositionServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param Position $model
     */
    public function __construct(Position $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return Position::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return Position::all();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdownForUser(): Collection
    {
        try {
            $users = User::select('positions_id')->notResigned()->where('divisions_id', \auth()->user()->divisions_id)->groupBy('positions_id')->get();
            return Position::find($users->pluck('positions_id'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
