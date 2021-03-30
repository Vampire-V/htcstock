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
    const positive = 'Positive';
    const negative = 'Negative';
    const zero_oriented_kpi = 'Zero Oriented KPI';
}
