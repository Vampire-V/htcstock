<?php

namespace App\Services\KPI\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface EvaluateServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function find($id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy($id);
    public function byStaff(User $staff);
    public function dropdown(): Collection;
    public function isDuplicate(int $user, int $period);
    public function findKeyEvaluate(int $user, int $period, int $evaluate);
    public function reviewfilter(Request $request);
    public function selfFilter(Request $request);
}
