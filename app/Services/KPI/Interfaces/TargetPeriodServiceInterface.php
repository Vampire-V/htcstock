<?php

namespace App\Services\KPI\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface TargetPeriodServiceInterface
{
    public function query(): Builder;
    public function create(array $attributes): Model;
    public function find($id): Model;
    public function filterIndex(Request $request);
    public function update(array $attributes, int $id): bool;
    public function destroy($id);
    public function byYear(string $year);
    public function byYearForEvaluate($year, $staff): Collection;
    public function dropdown(): Collection;
    public function selfApprovedEvaluationOfyear(string $year): Collection;
    public function deptApprovedEvaluationOfyear(string $year): Collection;
}
