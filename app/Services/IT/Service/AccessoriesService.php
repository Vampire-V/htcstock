<?php

namespace App\Services\IT\Service;

use App\Models\IT\Accessories;
use App\Services\BaseService;
use App\Services\IT\Interfaces\AccessoriesServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AccessoriesService extends BaseService implements AccessoriesServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param Accessories $model
     */
    public function __construct(Accessories $model)
    {
        parent::__construct($model);
    }

    public function all(): Builder
    {
        try {
            return Accessories::query();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function filter(Request $request)
    {
        return Accessories::filter($request)->noRemove()->orderBy('created_at', 'desc')->paginate(30);
    }

    public function sumAccessories()
    {
        try {
            return Accessories::addSelect(['totals' => function ($query) {
                $query->selectRaw('SUM(transactions.qty) as total')
                    ->from('transactions')
                    ->whereColumn('access_id', 'accessories.access_id');
            }]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function dropdown(): Collection
    {
        try {
            return Accessories::noRemove()->get();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function remove($id)
    {
        try {
            $item = Accessories::where('access_id',$id)->first();
            $item->remove = true;
            $item->save();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
