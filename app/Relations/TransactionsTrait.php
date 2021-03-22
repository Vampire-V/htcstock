<?php

namespace App\Relations;

use App\Models\IT\Accessories;
use App\Models\User;

trait TransactionsTrait
{
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'trans_by');
    }
    public function transactionCreated()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function accessorie()
    {
        return $this->hasOne(Accessories::class, 'access_id', 'access_id');
    }
}
