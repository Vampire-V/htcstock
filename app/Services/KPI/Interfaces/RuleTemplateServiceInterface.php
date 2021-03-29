<?php

namespace App\Services\KPI\Interfaces;

use App\Models\KPI\Template;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface RuleTemplateServiceInterface
{
    public function all(): Builder;
    public function create(array $attributes): Model;
    public function find($id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy($id);

    public function dropdown(): Collection;
    public function byTemplate(Template $template): Collection;
    public function byTemplateAndGroup(Template $template, string $group): Collection;
    public function filter(Request $request);
}
