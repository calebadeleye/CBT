<x-mail::message>
# Dear {{ $name }},

<p>Congratulations! You've received ₦{{ number_format($amount, 2) }} as 
    a reward for being one of the top scorers in last month's test challenges.
</p>

<p>Keep taking tests to earn more. God bless you!</p>

Best regards,<br>
{{ config('app.name') }} Team<br>
</x-mail::message>
