<?php

namespace App\Enum;

abstract class TransactionTypeEnum extends BasicEnum
{
    const B = 'Buy';
    const CB = 'CancelBuy';
    const L = 'Lend';
    const CL = 'CancelLend';
    const R = 'Requisition';
    const CR = 'CancelRequisition';

    public static $type = [self::B, self::CB, self::L, self::CL, self::R, self::CR];
}
