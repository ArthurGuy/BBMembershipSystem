<?php namespace BB\Repo;

use BB\Entities\Expense;
use BB\Entities\Notification;
use BB\Events\ExpenseWasApproved;
use BB\Events\ExpenseWasDeclined;

class ExpenseRepository extends DBRepository
{

    /**
     * @var Expense
     */
    protected $model;

    /**
     * @param Expense $model
     */
    public function __construct(Expense $model)
    {
        $this->model = $model;
        $this->perPage = 10;
    }

    /**
     * @param integer $id
     * @param integer $userId
     */
    public function approveExpense($id, $userId)
    {
        $expense = $this->model->findOrFail($id);
        $expense->approved = true;
        $expense->declined = false;
        $expense->approved_by_user = $userId;
        $expense->save();

        $message = 'Your expense was approved';
        $notificationHash = $expense->id . '-expense_approved';
        Notification::logNew($expense->user_id, $message, 'expense_approved', $notificationHash);

        //Fire an event - this will create the payment
        event(new ExpenseWasApproved($expense));
    }

    /**
     * @param integer $id
     * @param integer $userId
     */
    public function declineExpense($id, $userId)
    {
        $expense = $this->model->findOrFail($id);
        $expense->approved = false;
        $expense->declined = true;
        $expense->approved_by_user = $userId;
        $expense->save();

        $message = 'Your expense was declined';
        $notificationHash = $expense->id . '-expense_declined';
        Notification::logNew($expense->user_id, $message, 'expense_declined', $notificationHash);

        //This event currently doesn't do anything
        event(new ExpenseWasDeclined($expense));
    }


} 