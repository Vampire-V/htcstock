<?php

namespace App\Services\Legal\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ContractRequestServiceInterface
{
    public function all();
    public function getByCreated();
    public function create(array $attributes): Model;
    public function find(int $id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy(int $id);
    public function filter(Request $request);
    public function totalpromised(): int;
    public function ownpromised(User $user): int;
    public function countStatus(string $status): int;
    public function notRequest(string $request);
    public function filterForAdmin(Request $request);
    public function filterRequest(Request $request);
    public function filterChecking(Request $request);
    public function filterProviding(Request $request);
    public function filterComplete(Request $request);
    public function requestorInSystem();

}
