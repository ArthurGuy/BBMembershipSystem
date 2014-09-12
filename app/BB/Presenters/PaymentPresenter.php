<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class PaymentPresenter extends Presenter
{

    public function status()
    {
        switch ($this->entity->status) {
            case 'pending';
                return 'Pending Confirmation';
                break;

            case 'paid';
            case 'withdrawn';
                return 'Paid';
                break;

            default;
                return $this->entity->status;
        }
    }

    public function date()
    {
        return $this->entity->created_at->toFormattedDateString();
    }

    public function method()
    {
        switch ($this->entity->source) {
            case 'gocardless':
                return 'Direct Debit';
                break;

            case 'paypal':
                return 'PayPal';
                break;

            case 'standing-order':
                return 'Standing Order';
                break;

            case 'manual':
                return 'Manual';
                break;

            case 'cash':
                return 'Cash';
                break;

            default;
                return $this->entity->status;
        }
    }
} 