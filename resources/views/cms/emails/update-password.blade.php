<x-mail::message>
# Password Change Notification

Dear, {{ $full_name }}

<x-mail::panel>
Your password has been changed, if not by you call support
</x-mail::panel>

<x-mail::button :url="''">
Call Support
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
