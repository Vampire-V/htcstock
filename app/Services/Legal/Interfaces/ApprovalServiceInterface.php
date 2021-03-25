<?php

namespace App\Services\Legal\Interfaces;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ApprovalServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function find(int $id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy(int $id);

    public function approvalByDepartment(Department $department): Collection;
    public function dropdown(): Collection;
    public function approvalLevelLess(int $levels,int $department) : Model;
    public function approvalLevelOver(int $levels,int $department) : Model;
    public function approvalLevelAllOver(int $levels,int $department) : Collection;
}
