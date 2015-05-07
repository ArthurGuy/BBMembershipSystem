<?php 

class FeedbackController extends \BaseController
{


    /**
     * @var \BB\Validators\FeedbackValidator
     */
    private $feedbackValidator;

    public function __construct(\BB\Validators\FeedbackValidator $feedbackValidator)
    {
        $this->feedbackValidator = $feedbackValidator;
    }

    public function store()
    {
        $this->feedbackValidator->validate(Request::only('comments'));

        $memberName = Auth::user()->name;
        \Mail::queue('emails.feedback', ['memberName'=>$memberName, 'comments'=>Request::get('comments')], function($message) {
            $message->to('arthur@arthurguy.co.uk', 'Arthur Guy')->subject('BBMS Feedback');
        });
        return Response::json(['success'=>1]);
    }
} 