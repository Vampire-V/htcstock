<?php

namespace App\Services\KPI\Interfaces;

use App\Models\KPI\Evaluate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface EvaluateServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function find($id): Model;
    // public function findId($id);

    public function update(array $attributes, int $id): bool;
    public function destroy($id);
    public function dropdown(): Collection;
    public function isDuplicate(int $user, int $period);
    public function findKeyEvaluate(int $user, int $period, int $evaluate);
    public function reviewFilter(Request $request);
    public function selfFilter(Request $request);
    public function editEvaluateFilter(Request $request): Collection;
    public function scoreFilter(Request $request): Collection;
    public function forQuarterYear($user, $quarter, $year): Collection;
    public function forYear($user, $year): Collection;
    public function history(Evaluate $evaluate): Collection;
    public function employee_score_filter(Request $request,$employee);
}
