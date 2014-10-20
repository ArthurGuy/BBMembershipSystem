


    <div class="col-xs-12">
        {{ Form::open(array('route' => 'statement-import.store', 'class'=>'', 'files'=>true, 'method'=>'POST')) }}

        <div class="row">
            <div class="col-xs-12">
                <h1>Upload a statement</h1>
                <p>
                    Upload a bank statement containing subscriber payments.<br />
                    Make sure you only upload the monthly csv statement
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="form-group {{ $errors->has('statement') ? 'has-error has-feedback' : '' }}">
                    {{ Form::label('statement', 'CSV Statement', ['class'=>'control-label']) }}
                    {{ Form::file('statement', null, ['class'=>'form-control']) }}
                    {{ Form::checkbox('test', 1) }}
                    {{ Form::label('test', 'Test Process', ['class'=>'control-label']) }}
                    {{ $errors->first('statement', '<span class="help-block">:message</span>') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}
                <p></p>
            </div>
        </div>

        {{ Form::close() }}
    </div>
