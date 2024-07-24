<x-mail::message>
# Dear {{ $name }},

Here is your PIN below. Please do not disclose your PIN to anyone. Also, note that this PIN can be used only 10 times.

<x-mail::panel>
{{ $pin }}
</x-mail::panel>

If you have any trouble using this PIN, please send us an email at <a href="mailto:support@utme.com.ng">support@utme.com.ng</a>

<x-mail::button :url="$fullPathUrl" color="success">
	My Board
</x-mail::button>


Best regards,<br>
{{ config('app.name') }} Team<br>
</x-mail::message>


