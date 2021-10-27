@php
$cal_result = [];
@endphp
@isset($evaluate_detail)
@foreach ($evaluate_detail as $rules)
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
        @foreach ($rules as $key => $item)
        <tr>
            <td>{{$key + 1}}</td>
            <td>{{$item->rules->name}}</td>
            <td>{{$item->rules->description}}</td>
            <td>{{$item->base_line}}</td>
            <td>{{$item->max_result}}</td>
            <td>{{$item->weight}}</td>
            <td>{{$item->target}}</td>
            <td>{{round($item->target_pc,2)}}</td>
            <td>{{$item->actual}}</td>
            <td>{{round($item->actual_pc,2)}}</td>
            <td>{{round($item->ach, 2)}}</td>
            <td>{{round($item->cal, 2)}}</td>
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
            <td>{{round($rules->sum('weight'),2)}} %</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @php
            $reduce = 0;
            $reduce_hod = 0;
            if ($rules->first()->rule->category->name === 'kpi') {
            $reduce = $evaluate->kpi_reduce;
            $reduce_hod = $evaluate->kpi_reduce_hod;
            }
            if ($rules->first()->rule->category->name === 'key-task') {
            $reduce = $evaluate->key_task_reduce;
            $reduce_hod = $evaluate->key_task_reduce_hod;
            }
            if ($rules->first()->rule->category->name === 'omg') {
            $reduce = $evaluate->omg_reduce;
            $reduce_hod = $evaluate->omg_reduce_hod;
            }
            $total = round($rules->sum('cal'),2) - ($reduce + $reduce_hod);
            @endphp
            <td>{{$total}} %</td>
        </tr>
    </tfoot>
</table>
@php
$cal_result[] = $total;
@endphp
@endforeach
@endisset
@php
if (count($cal_result) < 3 ) {
    array_push($cal_result,0);
}
@endphp




{{-- Calculation Summary --}}
@php
$total = [];
@endphp
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Weight</th>
            <th>%Cal</th>
        </tr>
    </thead>
    <tbody>
        @isset($category)
        @foreach ($category as $key => $item)
        <tr>
            <th>{{$item->name}}</th>
            <td>{{$weight_group[$key] ?? 0.00}} %</td>

            @isset($cal_result[$key])
            @php
            $cal = ($cal_result[$key] * $weight_group[$key]) / 100 ;
            $total[] = $cal;
            @endphp
            @endisset
            <td>{{round($cal,2)}} %</td>
        </tr>
        @endforeach
        @endisset
    </tbody>
    <tfoot>
        <tr>
            <th>Total</th>
            <td>{{round(array_reduce($weight_group,fn($a,$b) => $a+$b),2)}} %</td>
            <td>{{round(array_reduce($total,fn($a,$b) => $a+$b),2)}} %</td>
        </tr>
    </tfoot>
</table>
