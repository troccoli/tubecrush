@component('mail::message')
# New message from {{ config('app.name') }} site

{{ $name }} has contacted you via the site.

@component('mail::panel')
{{ $message }}
@endcomponent

Their email address is {{ $email }}.
@endcomponent
