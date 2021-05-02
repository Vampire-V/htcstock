<?php

namespace App\Services\KPI\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface TemplateServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function find($id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy($id);

    public function dropdown(): Collection;
    public function filter(Request $request);
    public function ruleTemplate($template_id);
}
