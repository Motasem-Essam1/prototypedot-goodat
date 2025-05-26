<tr>
<td class="header">
<a href="{{ url($url) }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('assets/img/logo.png') }}" class="logo" alt="Deft@">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
