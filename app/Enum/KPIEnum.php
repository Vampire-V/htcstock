<?php

namespace App\Enum;

abstract class KPIEnum extends BasicEnum
{
    // Status
    const new = 'New';
    const ready = 'Ready';
    const draft = 'Draft';
    const submit = 'Submitted';
    const on_process = 'On Process';
    const approved = 'Approved';

    // calculate_type
    const positive = 'Positive';
    const negative = 'Negative';
    const zero_oriented_kpi = 'Zero Oriented KPI';

    // User Degree
    const one = 'N-1';
    const two = 'N-2';
    const tree = 'N-3';
    const four = 'N-4';

    // calculate_for quarter
    const average = 'Average';
    const last_month = 'Last Month';
    const sum = 'Sum';

    // steo approve
    const assign = 'step-assign-user';
    const set_value = 'step-set-value';
    const approve = 'step-approve';

    // Category
    const KPI = 'kpi';
    const KEY = 'key-task';
    const OMG = 'omg';

    public static $status = [self::new, self::ready, self::draft, self::submit, self::on_process, self::approved];
    public static $category = [self::KPI, self::KEY, self::OMG];
    public static $calculates = [self::average, self::last_month, self::sum];
    public static $calculate_types = [self::positive, self::negative, self::zero_oriented_kpi];
    public static $steps = [self::assign, self::set_value, self::approve];
    public static $degree = [self::one, self::two, self::tree, self::four];
}
