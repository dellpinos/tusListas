@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
{{-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo"> --}} {{-- Logo Laravel original --}}

@else
<img src="{{ asset('img/LogoSinFondo.png') }}" class="logo" alt="Logo Tus Listas">
{{-- {{ $slot }} --}}
@endif
</a>
</td>
</tr>
