<?php

namespace App\Services\KPI\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TargetPeriodServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function find($id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy($id);
    public function byYear(string $year);
    public function byYearForEvaluate(string $year, User $staff): Collection;
    public function dropdown(): Collection;
    public function selfApprovedEvaluationOfyear(string $year): Collection;
    public function deptApprovedEvaluationOfyear(string $year): Collection;
}
