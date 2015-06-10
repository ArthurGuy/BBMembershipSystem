<?php namespace BB\Providers;

use BB\Listeners\EmailMemberAboutDeclinedPhoto;
use BB\Listeners\RecordMemberActivity;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'payment.create' => [
			'BB\Handlers\PaymentEventHandler@onCreate',
		],
        'payment.delete' => [
			'BB\Handlers\PaymentEventHandler@onDelete',
		],
        'payment.cancelled' => [
			'BB\Handlers\PaymentEventHandler@onCancel',
		],
        'payment.paid' => [
			'BB\Handlers\PaymentEventHandler@onPaid',
		],
        'sub-charge.paid' => [
			'BB\Handlers\SubChargeEventHandler@onPaid',
		],
        'sub-charge.processing' => [
			'BB\Handlers\SubChargeEventHandler@onProcessing',
		],
        'sub-charge.payment-failed' => [
			'BB\Handlers\SubChargeEventHandler@onPaymentFailure',
		],
        'BB\Events\NewExpenseSubmitted' => [
            'BB\Listeners\EmailTrusteesAboutExpense',
        ],
        'BB\Events\ExpenseWasApproved' => [
            'BB\Listeners\EmailMemberAboutApprovedExpense',
            'BB\Listeners\AddApprovedExpenseToBalance',
        ],
        'BB\Events\ExpenseWasDeclined' => [
            'BB\Listeners\EmailMemberAboutDeclinedExpense',
        ],
        'BB\Events\MemberPhotoWasDeclined' => [
            EmailMemberAboutDeclinedPhoto::class,
        ],
        'BB\Events\MemberActivity' => [
            RecordMemberActivity::class,
        ],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

		//
	}

}
