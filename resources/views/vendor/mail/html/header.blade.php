@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo-v2.1.png" class="logo" alt="Laravel Logo">
@else
<span style="
  font-family: 'Jersey 10', -apple-system, BlinkMacSystemFont, Arial, sans-serif;
  font-size: 32px;
  font-weight: 900;
  letter-spacing: 5px;
  color: #7c3aed;
  text-transform: uppercase;
  text-decoration: none;
">{{ $slot }}</span>
@endif
</a>
</td>
</tr>
