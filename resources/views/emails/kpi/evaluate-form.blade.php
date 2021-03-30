@component('mail::message')
# KPI Request evaluate

Step KPI evaluate self.<br>
{{$evaluate->user->email}}

    @component('mail::button', ['url' => route('kpi.evaluation.verify',$evaluate->id)])
    Go to KPI Evaluate Form
    @endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
