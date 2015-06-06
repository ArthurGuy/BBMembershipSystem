<?php

namespace BB\Listeners;

use BB\Events\ExpenseWasApproved;
use BB\Repo\PaymentRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddApprovedExpenseToBalance
{
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * Create the event listener.
     *
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Handle the event.
     *
     * @param  ExpenseWasApproved  $event
     * @return void
     */
    public function handle(ExpenseWasApproved $event)
    {
        $this->paymentRepository->recordPayment('balance', $event->expense->user_id, 'reimbursement', $event->expense->id, ($event->expense->amount / 100));
    }
}
