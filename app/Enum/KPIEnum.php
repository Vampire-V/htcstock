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

    // User Degree
    const one = 'N-1';
    const two = 'N-2';
    const tree = 'N-3';

    // calculate_for quarter
    const average = 'Average';
    const last_month = 'Last Month';
    const sum = 'Sum';
}
