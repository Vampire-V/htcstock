<?php

namespace App\Relations;

use App\Models\IT\Transactions;

trait AccessoriesTrait
{

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'access_id', 'access_id')->withDefault();
    }
}
