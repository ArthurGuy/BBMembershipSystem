@if (!Auth::guest())
    <div class="feedbackWidgetButtonWrap hidden-sm hidden-xs">
        <div id="feedbackWidgetButton" data-toggle="modal" data-target="#feedbackWidgetModal">
            <span class="glyphicon glyphicon-bullhorn"></span> Feedback
        </div>
    </div>
    <div class="modal fade" id="feedbackWidgetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Member System Feedback</h4>
                </div>
                {{ Form::open(array('method'=>'POST', 'route' => ['feedback.store'], 'class'=>'js-feedbackModalForm js-ajaxForm', 'data-successFunction'=>'feedbackFormSuccess', 'data-errorFunction'=>'feedbackFormError')) }}
                <div class="modal-body">
                    <p>
                        If you have any comments or thoughts or ideas about the operation of the BBMS system we would love to hear them.<br />
                        If you have an account related query please email the trustees at <a href="mailto:trustees@buildbrighton.com">trustees@buildbrighton.com</a>
                    </p>

                    <div class="form-group js-field-comments">
                        {{ Form::label('comments', 'Your Thoughts') }}
                        {{ Form::textarea('comments', null, ['class'=>'form-control', ''=>'']) }}
                        <span class="help-block js-errorMessages"></span>
                    </div>

                </div>
                <div class="modal-footer">
                    {{ Form::button('Close', ['class'=>'btn btn-default', 'data-dismiss'=>'modal']) }}
                    {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endif

