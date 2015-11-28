<tr>
    <td class="profilePhotoCol hidden-sm hidden-xs">
        {!! HTML::memberPhoto($user->profile, $user->hash, 100, 'img-circle profilePhoto') !!}
    </td>
    <td>
        {{ $user->name }}
        <br />
        {{ $user->email }}
    </td>
    <td>
        {!! Form::open(array('method'=>'PUT', 'class'=>'form-inline', 'route' => ['account.induction.approve', $user->id], 'onSubmit' => 'return window.confirm("Are you sure?")')) !!}
        {!! Form::hidden('inducted_by', 'true') !!}
        {!! Form::submit('Confirm member induction', array('class'=>'btn btn-primary')) !!}
        {!! Form::close() !!}
    </td>
</tr>