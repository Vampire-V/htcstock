<?php

namespace App\Services\KPI\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EvaluateDetailServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function find($id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy($id);

    public function dropdown(): Collection;

    public function updateForEvaluate(array $datas, int $evaluate,int $rule_id);
}
