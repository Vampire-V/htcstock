<?php

namespace App\Services\Legal\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PaymentTypeServiceInterface
{
    public function create(array $attributes): Model;
    public function find(int $id): Model;

    public function update(array $attributes, int $id): bool;
    public function destroy(int $id);

    public function dropdown(int $agreement): Collection;
}
