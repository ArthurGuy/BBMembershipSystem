<?php namespace BB\Handlers;

use BB\Repo\ExpenseRepository;
use BB\Repo\PaymentRepository;

class ExpenseEventHandler
{
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var ExpenseRepository
     */
    private $expenseRepository;

    /**
     * @param ExpenseRepository $expenseRepository
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(ExpenseRepository $expenseRepository, PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Subscribe to the payment events
     *
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('expense.approved', 'BB\Handlers\ExpenseEventHandler@onApprove');
    }

    /**
     * An expense has been approved, create the balance topup payment
     *
     * @param $expenseId
     */
    public function onApprove($expenseId)
    {
        $expense = $this->expenseRepository->getById($expenseId);
        $amount = $expense->amount / 100;

        $this->paymentRepository->recordPayment('balance', $expense->user_id, 'reimbursement', $expenseId, $amount);
    }
} 