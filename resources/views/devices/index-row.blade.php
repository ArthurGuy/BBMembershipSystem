<tr @if ($device->heartbeatWarning()) class="danger" @endif>
    <td>{{ $device->name }}</td>
    <td>{{ $device->device_id }}</td>
    <td>{{ $device->api_key }}</td>
    <td>{{ ($device->last_heartbeat) ? \Carbon\Carbon::now()->diffForHumans($device->last_heartbeat, true) . ' ago': 'never' }}</td>
    <td>{{ ($device->last_boot) ? \Carbon\Carbon::now()->diffForHumans($device->last_boot, true) . ' ago': 'never' }}</td>
</tr>