@component('mail::message')
# Legal Reauest Contract

Step alert contract

@component('mail::button', ['url' => route('legal.approval.verify',[$user->id,$contract->id])])
Go to Contract
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
