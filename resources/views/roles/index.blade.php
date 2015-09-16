@extends('layouts.main')

@section('meta-title')
Member Roles and Groups
@stop

@section('page-title')
Member Roles and Groups
@stop

@section('content')

<p>
    Update group names and descriptions.<br />
    Assign members to specific roles in order to control how much access they have and what they can do
</p>

    @foreach($roles as $role)
        <hr />

        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-8">
                {!! Form::open(array('method'=>'PUT', 'route' => ['roles.update', $role->id], 'class'=>'')) !!}
                <div class="form-group">
                    {!! Form::label('Title') !!}
                    {!! Form::text('title', $role->title, ['class'=>'form-control input-lg', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Description') !!}
                    {!! Form::textarea('description', $role->description, ['class'=>'form-control', 'rows'=>2, 'placeholder'=>'Short description']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Public Email') !!}
                    {!! Form::text('public_email', $role->public_email, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Private Email') !!}
                    {!! Form::text('private_email', $role->private_email, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Slack Channel') !!}
                    {!! Form::text('slack_channel', $role->slack_channel, ['class'=>'form-control']) !!}
                </div>
                {!! Form::submit('Save', array('class'=>'btn btn-default')) !!}
                {!! Form::close() !!}
                <small>{{ $role->name }}</small>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <table class="table">
                @foreach($role->users as $user)
                    <tr>
                        <td width="50%">{{ $user->name }}</td>
                        <td>
                        {!! Form::open(array('method'=>'DELETE', 'route' => ['roles.users.destroy', $role->id, $user->id], 'class'=>'form-inline')) !!}
                        {!! Form::submit('Remove', array('class'=>'btn btn-default btn-xs')) !!}
                        {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                    <tr>
                        {!! Form::open(array('method'=>'POST', 'route' => ['roles.users.store', $role->id], 'class'=>'form-inline')) !!}
                        <td>{!! Form::select('user_id', [''=>'Add a member']+$memberList, null, ['class'=>'form-control js-advanced-dropdown']) !!}</td>
                        <td>
                        {!! Form::submit('Add', array('class'=>'btn btn-default btn-sm')) !!}
                        </td>
                        {!! Form::close() !!}
                    </tr>
                </table>
            </div>
        </div>

    @endforeach

@stop