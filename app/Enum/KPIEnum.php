<?php

namespace App\Enum;

abstract class KPIEnum extends BasicEnum
{
    // Status
    const new = 'New';
    const ready = 'Ready';
    const draft = 'Draft';
    const submit = 'Submitted';
    const approved = 'Approved';

    // calculate_type
    const percent = 'Percent';
    const amount = 'Amount';
}
