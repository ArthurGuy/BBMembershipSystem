@extends('layouts.main')

@section('meta-title')
Member Roles
@stop
@section('page-title')
Member Roles
@stop

@section('content')

<p>
Assign members to specific roles in order to control how much access they have and what they can do
</p>
<table class="table">
<thead>
    <tr>
        <th>Role</th>
        <th>Members</th>
    </tr>
</thead>
<tbody>
    @foreach($roles as $role)
        <tr>
            <td>{{ $role->title }} ({{ $role->name }})</td>
            <td>
                <table class="table">
                @foreach($role->users as $user)
                    <tr>
                        <td width="50%">{{ $user->name }}</td>
                        <td>
                        {{ Form::open(array('method'=>'DELETE', 'route' => ['roles.users.destroy', $role->id, $user->id], 'class'=>'form-inline')) }}
                        {{ Form::submit('Remove', array('class'=>'btn btn-default btn-xs')) }}
                        {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
                    <tr>
                        {{ Form::open(array('method'=>'POST', 'route' => ['roles.users.store', $role->id], 'class'=>'form-inline')) }}
                        <td>{{ Form::select('user_id', [''=>'Add a member']+$memberList, null, ['class'=>'form-control js-advanced-dropdown']) }}</td>
                        <td>
                        {{ Form::submit('Add', array('class'=>'btn btn-default btn-sm')) }}
                        </td>
                        {{ Form::close() }}
                    </tr>
                </table>
            </td>
        </tr>
    @endforeach
</tbody>
</table>

@stop