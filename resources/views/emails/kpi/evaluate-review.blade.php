@component('mail::message')
# KPI evaluate

KPI evaluate review by {{$manager->email}}

@component('mail::button', ['url' => route('kpi.evaluation.verify',$evaluate->id)])
Go to KPI Evaluate Review
@endcomponent
# Comment : {{$evaluate->comment}}
{{-- Thanks,<br> --}}
{{ config('app.name') }}
@endcomponent
