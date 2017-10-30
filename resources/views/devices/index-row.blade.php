<tr @if ($device->heartbeatWarning()) class="danger" @endif>
    <td>{{ $device->name }}</td>
    <td>{{ $device->device_id }}</td>
    <td>{{ $device->api_key }}</td>
    <td>{{ $device->entry_device? 'Yes': '' }}</td>
    <td>{{ ($device->last_heartbeat) ? \Carbon\Carbon::now()->diffForHumans($device->last_heartbeat, true) . ' ago': 'never' }}</td>
    <td>{{ ($device->last_boot) ? \Carbon\Carbon::now()->diffForHumans($device->last_boot, true) . ' ago': 'never' }}</td>
    <td>
        {!! Form::open(array('method' => 'DELETE', 'route' => ['devices.destroy', $device->id], 'class'=>'form-horizontal', 'files'=>true)) !!}
        {!! Form::submit('Delete', array('class'=>'btn btn-primary', 'onclick'=>'return confirm("Are you sure?")')) !!}
        {!! Form::close() !!}
    </td>
</tr>
