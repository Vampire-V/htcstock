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
            <td>{{$rule->base_line}}</td>
            <td>{{round($rule->max_result,2)}}</td>
            <td>{{round($rule->weight,2)}}</td>
            <td>{{round($rule->target,2)}}</td>
            <td>{{round($rule->target_pc,2)}}</td>
            <td>{{round($rule->actual,2)}}</td>
            <td>{{round($rule->actual_pc,2)}}</td>
            <td>{{round($rule->ach, 2)}}</td>
            <td>{{round($rule->cal, 2)}}</td>
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
            <td>{{round($group->sum('weight'),2)}} %</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @php
            $reduce = 0;
            if ($key === 'kpi') {
            $reduce = $evaluate->kpi_reduce;
            }
            if ($key === 'key-task') {
            $reduce = $evaluate->key_task_reduce;
            }
            if ($key === 'omg') {
            $reduce = $evaluate->omg_reduce;
            }
            $sum = round($group->sum('cal'),2) - $reduce;
            @endphp
            <td>{{$sum}} %</td>
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
        $result = $result - $evaluate->kpi_reduce;
        }
        if ($i === 'key-task') {
        $result = $result - $evaluate->key_task_reduce;
        }
        if ($i === 'omg') {
        $result = $result - $evaluate->omg_reduce;
        }
        $cal = ($result * $quarter_weight[$loop->index]) / 100;
        $total[] = $cal;
        @endphp
        <tr>
            <th>{{$i}}</th>
            <td>{{$quarter_weight[$loop->index]}} %</td>
            <td>{{round($cal,2)}} %</td>
        </tr>
        @endforeach
        @endisset
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            <td> {{round(array_reduce($quarter_weight,fn($a,$b) => $b+$a,0),2)}} %</td>
            <td> {{round(array_reduce($total,fn($a,$b) => $b+$a,0),2)}} %</td>
        </tr>
    </tfoot>
</table>