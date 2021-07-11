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
            <td>{{round($rules->sum('cal'),2)}} %</td>
        </tr>
    </tfoot>
</table>
@php
$cal_result[] = $rules->sum('cal')
@endphp
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
            @if (isset($cal_result[$key]))
            @php
                $cal = ($cal_result[$key] * $weight_group[$key]) / 100;
                $total[] = $cal;
            @endphp
            <td>{{round($cal,2)}} %</td>
            @else
            <td>0.00 %</td>
            @endif
            
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