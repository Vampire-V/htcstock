@component('mail::message')
# KPI evaluate

KPI evaluate review by {{auth()->user()->email}}

@component('mail::button', ['url' => '10.35.10.47:8000'])
Go to KPI Evaluate Review
@endcomponent
<!-- @component('mail::button', ['url' => route('kpi.evaluation.verify',$evaluate->id)])
Go to KPI Evaluate Review
@endcomponent -->
# Comment : {{$evaluate->comment}}
{{-- Thanks,<br> --}}
{{ config('app.name') }}
@endcomponent
