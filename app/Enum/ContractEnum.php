<?php

namespace App\Enum;

abstract class ContractEnum extends BasicEnum
{
    const D = 'draft';
    const RQ = 'request';
    const RJ = 'reject';
    const CK = 'checking';
    const P = 'providing';
    const CP = 'complete';

    public static $types = [self::D, self::RQ, self::RJ, self::CK, self::P, self::CP];
}
