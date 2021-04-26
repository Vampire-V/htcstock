<?php

namespace App\Services\IT\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function all(): Builder;

    public function find(int $id): Model;

    public function update(array $attributes, int $id): bool;

    public function delete(int $id): bool;

    public function create(array $attributes): Model;
    public function dropdownNotIn(array $username): Collection;
    public function dropdown(): Collection;
    public function filter(Request $request);
    public function email(string $email);

    public function division(...$division_id): Collection;

    public function user($id): User;

    public function listOfTeamsOfEvaluate($department, $period): Collection;
    public function evaluationOfYear(string $year): Collection;
}
