
<div class="form-group {{ Notification::hasErrorDetail('name', 'has-error has-feedback') }}">
    {!! Form::label('name', 'Name', ['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-9 col-lg-7">
        {!! Form::text('name', null, ['class'=>'form-control']) !!}
        <p class="help-block">An optional display name.</p>
        {!! Notification::getErrorDetail('name') !!}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('device_id', 'has-error has-feedback') }}">
    {!! Form::label('device_id', 'Device ID', ['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-9 col-lg-7">
        {!! Form::text('device_id', null, ['class'=>'form-control']) !!}
        <p class="help-block">A device ID - Used in the api requests to identify the device being controlled</p>
        {!! Notification::getErrorDetail('device_id') !!}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('api_key', 'has-error has-feedback') }}">
    {!! Form::label('api_key', 'API Key', ['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-9 col-lg-7">
        {!! Form::text('api_key', null, ['class'=>'form-control']) !!}
        <p class="help-block">The key you will use to identify yourself when making requests.</p>
        {!! Notification::getErrorDetail('api_key') !!}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('monitor_heartbeat', 'has-error has-feedback') }}">
    {!! Form::label('monitor_heartbeat', 'Monitor device uptime?', ['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-9 col-lg-7">
        {!! Form::select('monitor_heartbeat', [0 => 'No', 1 => 'Yes'], null, ['class'=>'form-control']) !!}
        <p class="help-block">If the device fails to check-in for over an hour an alter will be raised.</p>
        {!! Notification::getErrorDetail('monitor_heartbeat') !!}
    </div>
</div>
<div class="form-group {{ Notification::hasErrorDetail('entry_device', 'has-error has-feedback') }}">
    {!! Form::label('entry_device', 'Is this a door or other entry device?', ['class'=>'col-sm-3 control-label']) !!}
    <div class="col-sm-9 col-lg-7">
        {!! Form::select('entry_device', [0 => 'No', 1 => 'Yes'], null, ['class'=>'form-control']) !!}
        <p class="help-block">Entry devices wont start a period of activity</p>
        {!! Notification::getErrorDetail('entry_device') !!}
    </div>
</div>
