<tr class="js-notification-row @if ($notification->unread) success @endif" data-id="{{ $notification->id }}">
    <td>{{ $notification->message }}</td>
    <td>{{ $notification->type }}</td>
    <td>{{ $notification->created_at }}</td>
    <td class="js-unread">@if ($notification->unread) <span class="glyphicon glyphicon-ok" title="Unread"></span> @endif</td>
</tr>