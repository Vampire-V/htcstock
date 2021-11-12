<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Haier')
<img src="{{ asset('assets/images/Haier.png') }}" class="logo" alt="No Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
