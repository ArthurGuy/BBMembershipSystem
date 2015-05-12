
<div class="form-group {{ Notification::hasErrorDetail('name', 'has-error has-feedback') }}">
    {{ Form::label('name', 'Name', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('name', null, ['class'=>'form-control']) }}
        <p class="help-block">Aim for a short but descriptive name, i.e. Metal Bandsaw</p>
        {{ Notification::getErrorDetail('name') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('key', 'has-error has-feedback') }}">
    {{ Form::label('key', 'Key', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('key', null, ['class'=>'form-control']) }}
        <p class="help-block">This is the unique reference for the item, no special characters. i.e. metal-bandsaw or cordless-drill-1</p>
        {{ Notification::getErrorDetail('key') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('family_name', 'has-error has-feedback') }}">
    {{ Form::label('manufacturer', 'Manufacturer', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('manufacturer', null, ['class'=>'form-control']) }}
        {{ Notification::getErrorDetail('manufacturer') }}
    </div>
</div>


<div class="form-group {{ Notification::hasErrorDetail('model_number', 'has-error has-feedback') }}">
    {{ Form::label('model_number', 'Model Number', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('model_number', null, ['class'=>'form-control']) }}
        {{ Notification::getErrorDetail('model_number') }}
    </div>
</div>


<div class="form-group {{ Notification::hasErrorDetail('serial_number', 'has-error has-feedback') }}">
    {{ Form::label('serial_number', 'Serial Number', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('serial_number', null, ['class'=>'form-control']) }}
        {{ Notification::getErrorDetail('serial_number') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('colour', 'has-error has-feedback') }}">
    {{ Form::label('colour', 'Colour', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('colour', null, ['class'=>'form-control']) }}
        <p class="help-block">A rough guide such as grey or blue/green</p>
        {{ Notification::getErrorDetail('colour') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('room', 'has-error has-feedback') }}">
    {{ Form::label('room', 'Room', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::select('room', ['workshop'=>'Dirty Workshop', 'digital-manufacturing'=>'Digital Manufacturing', 'main-room'=>'Main Room'], null, ['class'=>'form-control']) }}
        {{ Notification::getErrorDetail('room') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('detail', 'has-error has-feedback') }}">
    {{ Form::label('detail', 'Detail', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('detail', null, ['class'=>'form-control']) }}
        <p class="help-block">Where in the room is it kept?</p>
        {{ Notification::getErrorDetail('detail') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('device_key', 'has-error has-feedback') }}">
    {{ Form::label('device_key', 'Device Key', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('device_key', null, ['class'=>'form-control']) }}
        <p class="help-block">The id of a ACS device already setup in the database</p>
        {{ Notification::getErrorDetail('device_key') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('description', 'has-error has-feedback') }}">
    {{ Form::label('description', 'Description', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::textarea('description', null, ['class'=>'form-control']) }}
        {{ Notification::getErrorDetail('description') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('help_text', 'has-error has-feedback') }}">
    {{ Form::label('help_text', 'Help Text', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::textarea('help_text', null, ['class'=>'form-control']) }}
        <p class="help-block">A lot of text can go in here, useful for things like safety or maintenance information</p>
        {{ Notification::getErrorDetail('help_text') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('managing_role_id', 'has-error has-feedback') }}">
    {{ Form::label('managing_role_id', 'Managing Group', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::select('managing_role_id', [''=>'']+$roleList, null, ['class'=>'form-control js-advanced-dropdown']) }}
        <p class="help-block">Is a group is responsible for this piece of equipment?</p>
        {{ Notification::getErrorDetail('managing_role_id') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('requires_induction', 'has-error has-feedback') }}">
    {{ Form::label('requires_induction', 'Requires Induction', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::select('requires_induction', [0=>'No', 1=>'Yes'], null, ['class'=>'form-control']) }}
        {{ Notification::getErrorDetail('requires_induction') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('induction_category', 'has-error has-feedback') }}">
    {{ Form::label('induction_category', 'Induction Category', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('induction_category', null, ['class'=>'form-control']) }}
        <p class="help-block">By getting inducted on this piece of equipment they are inducted to this category meaning they have access to any other piece of equipment in the same category. i.e. access to all 3D Printers.</p>
        {{ Notification::getErrorDetail('induction_category') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('working', 'has-error has-feedback') }}">
    {{ Form::label('working', 'Working', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::select('working', [1=>'Yes', 0=>'No'], null, ['class'=>'form-control']) }}
        <p class="help-block">Is the equipment ready for use?</p>
        {{ Notification::getErrorDetail('working') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('permaloan', 'has-error has-feedback') }}">
    {{ Form::label('permaloan', 'Permaloan', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::select('permaloan', [0=>'No', 1=>'Yes'], null, ['class'=>'form-control']) }}
        <p class="help-block">Is this item on <a href="{{ route('resources.policy.view', 'permaloan') }}">permanent loan</a> from a member?</p>
        {{ Notification::getErrorDetail('permaloan') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('permaloan_user_id', 'has-error has-feedback') }}">
    {{ Form::label('permaloan_user_id', 'Permaloan Owner', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::select('permaloan_user_id', [''=>'']+$memberList, null, ['class'=>'form-control js-advanced-dropdown']) }}
        <p class="help-block">If its being loaned who owns it?</p>
        {{ Notification::getErrorDetail('permaloan_user_id') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('access_fee', 'has-error has-feedback') }}">
    {{ Form::label('access_fee', 'Access Fee', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        <div class="input-group">
            <div class="input-group-addon">&pound;</div>
            {{ Form::input('number', 'access_fee', null, ['class'=>'form-control', 'min'=>'0', 'step'=>'1']) }}
        </div>
        <p class="help-block">Is an access fee being charged?</p>
        {{ Notification::getErrorDetail('access_fee') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('usage_cost', 'has-error has-feedback') }}">
    {{ Form::label('usage_cost', 'Usage Cost', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        <div class="input-group">
            <div class="input-group-addon">&pound;</div>
            {{ Form::input('number', 'usage_cost', null, ['class'=>'form-control', 'min'=>'0', 'step'=>'0.01']) }}
            <div class="input-group-addon">
            Per {{ Form::select('usage_cost_per', [''=>'-', 'hour'=>'hour', 'gram'=>'gram'], null, ['class'=>'']) }}
            </div>
        </div>
        <p class="help-block">Does the equipment cost anything to use?</p>
        {{ Notification::getErrorDetail('usage_cost') }}
    </div>
</div>

<div class="form-group {{ Notification::hasErrorDetail('asset_tag_id', 'has-error has-feedback') }}">
    {{ Form::label('asset_tag_id', 'Asset Tag ID', ['class'=>'col-sm-3 control-label']) }}
    <div class="col-sm-9 col-lg-7">
        {{ Form::text('asset_tag_id', null, ['class'=>'form-control']) }}
        <p class="help-block">If an asset tag is being placed onto this piece of equipment whats the ID?</p>
        {{ Notification::getErrorDetail('asset_tag_id') }}
    </div>
</div>


