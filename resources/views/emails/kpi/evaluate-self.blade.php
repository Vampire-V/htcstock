@component('mail::message')
# KPI evaluate

KPI self evaluate of {{$evaluate->user->email}}

@component('mail::button', ['url' => '10.35.10.47:8000'])
Go to KPI Evaluate Self
@endcomponent
<!-- @component('mail::button', ['url' => route('kpi.evaluation.verify',$evaluate->id)])
Go to KPI Evaluate Self
@endcomponent -->

{{-- Thanks,<br> --}}
{{ config('app.name') }}
@endcomponent
