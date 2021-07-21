@isset($rules)
@foreach ($rules as $group)
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
        @foreach ($group as $key => $rule)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$rule->rule->name}}</td>
            <td>{{$rule->rule->description}}</td>
            <td>{{$rule->base_line}}</td>
            <td>{{$rule->max_result}}</td>
            <td>{{$rule->weight}}</td>
            <td>{{$rule->target}}</td>
            <td>{{round($rule->target_pc,2)}}</td>
            <td>{{$rule->actual}}</td>
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
            <td>{{round($group->sum('cal'),2)}} %</td>
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
        @foreach ($rules as $key => $item)
        @php
        $cal = ($item->sum('cal') * $quarter_weight[$loop->index]) / 100;
        $total[] = $cal;
        @endphp
        <tr>
            <th>{{$key}}</th>
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