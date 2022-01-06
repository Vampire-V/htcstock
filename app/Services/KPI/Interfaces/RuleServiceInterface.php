<?php

namespace App\Services\KPI\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface RuleServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function insert(array $attributes);
    public function find($id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy($id);

    public function dropdown($group = null): Collection;

    public function filter(Request $request);

    public function isName(string $var): bool;
    public function rulesInEvaluationReport(Collection $year ,Request $request);
    public function rule_excel(): array;
}
