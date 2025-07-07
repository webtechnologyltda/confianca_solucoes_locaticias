@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('img/logo.png') }}" class="logo" alt="{{ config('app.name') }} Logo">
        </a>
    </td>
</tr>
