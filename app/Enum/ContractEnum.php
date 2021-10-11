<?php

namespace App\Enum;

abstract class ContractEnum extends BasicEnum
{
    const RQ = 'request';
    const RJ = 'reject';
    const CK = 'checking';
    const P = 'providing';
    const CP = 'complete';
}
