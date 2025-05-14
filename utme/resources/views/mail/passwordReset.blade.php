<x-mail::message>
# Dear {{ $name }},

Here is your password reset link. Please click the link below to reset your password:

<x-mail::button :url="$fullPathUrl" color="success">
Reset Password
</x-mail::button>

<x-mail::panel>
If the link above does not work, please copy and paste the following URL into your browser:
<a href="{{ $fullPathUrl }}">{{$fullPathUrl}}</a>
</x-mail::panel>

Best regards,<br>
{{ config('app.name') }} Team<br>
</x-mail::message>
