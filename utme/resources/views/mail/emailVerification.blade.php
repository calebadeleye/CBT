<x-mail::message>
# Dear {{ $name }},

Thank you for registering with Utme.com.ng To complete your registration, please verify your email address by clicking the link below:

<x-mail::button :url="$fullPathUrl" color="success">
Verify Your Email Address
</x-mail::button>

<x-mail::panel>
If the link above does not work, please copy and paste the following URL into your browser:
<a href="$fullPathUrl">{{$fullPathUrl}}</a>
</x-mail::panel>

Best regards,<br>
{{ config('app.name') }} Team<br>
</x-mail::message>
