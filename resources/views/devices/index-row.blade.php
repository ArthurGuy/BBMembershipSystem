<tr @if ($device->heartbeatWarning()) class="danger" @endif>
    <td>{{ $device->name }}</td>
    <td>{{ $device->device_id }}</td>
    <td>{{ $device->queued_command }}</td>
    <td>{{ ($device->last_heartbeat->timestamp <= 0) ? 'never': \Carbon\Carbon::now()->diffForHumans($device->last_heartbeat, true) . ' ago' }}</td>
    <td>{{ ($device->last_boot->timestamp <= 0) ? 'never': \Carbon\Carbon::now()->diffForHumans($device->last_boot, true) . ' ago' }}</td>
</tr>