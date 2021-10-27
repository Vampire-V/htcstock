@isset($rules)
@foreach ($rules as $key => $group)
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Rule Name</th>
            <th>Description</th>
            <th>Base Line %</th>
            <th>Max %</th>
            <th>Weight %</th>
            <th>Target Amount</th>
            <th>Target %</th>
            <th>Actual Amount</th>
            <th>Actual %</th>
            <th>%Ach</th>
            <th>%Cal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($group as $rule)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$rule->rule->name}}</td>
            <td>{{$rule->rule->description}}</td>
            <td>{{Helper::decimal($rule->base_line)}}</td>
            <td>{{Helper::decimal($rule->max_result)}}</td>
            <td>{{Helper::decimal($rule->weight)}}</td>
            <td>{{Helper::decimal($rule->target)}}</td>
            <td>{{Helper::decimal($rule->target_pc)}}</td>
            <td>{{Helper::decimal($rule->actual)}}</td>
            <td>{{Helper::decimal($rule->actual_pc)}}</td>
            <td>{{Helper::decimal($rule->ach)}}</td>
            <td>{{Helper::decimal($rule->cal)}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <td></td>
            <td></td>
            <td></td>
            <td>Weight</td>
            <td>{{Helper::decimal($group->sum('weight'))}} %</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @php
            $reduce = 0;
            $reduce_hod = 0;
            if ($key === 'kpi') {
            $reduce = $evaluate->kpi_reduce;
            $reduce_hod = $evaluate->kpi_reduce_hod;
            }
            if ($key === 'key-task') {
            $reduce = $evaluate->key_task_reduce;
            $reduce_hod = $evaluate->key_task_reduce_hod;
            }
            if ($key === 'omg') {
            $reduce = $evaluate->omg_reduce;
            $reduce_hod = $evaluate->omg_reduce_hod;
            }
            $sum = $group->sum('cal') - ($reduce + ($reduce_hod / 12));
            @endphp
            <td>{{Helper::decimal($sum)}} %</td>
        </tr>
    </tfoot>
</table>
@endforeach
@endisset




{{-- Calculation Summary --}}
@php
$total = [];
@endphp
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Weight%</th>
            <th>Cal%</th>
        </tr>
    </thead>
    <tbody>
        @isset($rules)
        @foreach ($rules as $i => $item)
        @php
        $result = $item->sum('cal');
        if ($i === 'kpi') {
        $result = $result - ($evaluate->kpi_reduce + ($evaluate->kpi_reduce_hod / 12));
        }
        if ($i === 'key-task') {
        $result = $result - ($evaluate->key_task_reduce + ($evaluate->key_task_reduce_hod / 12));
        }
        if ($i === 'omg') {
        $result = $result - ($evaluate->omg_reduce + ($evaluate->omg_reduce_hod / 12));
        }
        $cal = ($result * $quarter_weight[$loop->index]) / 100;
        $total[] = $cal;
        @endphp
        <tr>
            <th>{{$i}}</th>
            <td>{{Helper::decimal($quarter_weight[$loop->index])}} %</td>
            <td>{{Helper::decimal($cal)}} %</td>
        </tr>
        @endforeach
        @endisset
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            <td> {{Helper::decimal(array_reduce($quarter_weight,fn($a,$b) => $b+$a,0))}} %</td>
            <td> {{Helper::decimal(array_reduce($total,fn($a,$b) => $b+$a,0))}} %</td>
        </tr>
    </tfoot>
</table>
