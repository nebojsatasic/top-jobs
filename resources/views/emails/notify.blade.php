<x-mail::message>
# Congratulations!

<p>You are now a premium user.</p>
<p>Your purchase details:</p>
<p>Plan: {{ $plan }}</p>
<p>Your plan ends on: {{ $billingEnds }}</p>
<x-mail::button :url="''">
Post a job
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
