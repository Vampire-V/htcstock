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

    // Priority
    const H = 'High';
    const M = 'Medium';
    const L = 'Low';

    public static $types = [self::D, self::RQ, self::RJ, self::CK, self::P, self::CP];
    public static $priority = [self::H,self::M,self::L];
}
