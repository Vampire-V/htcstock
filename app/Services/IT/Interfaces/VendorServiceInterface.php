<?php

namespace App\Services\IT\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface VendorServiceInterface
{
    public function all(): Builder;
    public function filter(Request $request);
    public function create(array $attributes): Model;
    // public function findStr(string $code): Model;

    // public function updateStr(array $attributes, string $code): bool;
    // public function destroyStr(string $code);

    public function dropdown(): Collection;
}
