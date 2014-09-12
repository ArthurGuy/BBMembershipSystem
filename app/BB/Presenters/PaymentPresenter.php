<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class PaymentPresenter extends Presenter
{

    public function reason()
    {
        switch ($this->entity->reason) {
            case 'subscription';
                return 'Subscription';
                break;

            case 'unknown';
                return 'Unknown';
                break;

            case 'induction';
                return 'Equipment Induction';
                break;

            case 'door-key';
                return 'Key Deposit';
                break;

            case 'storage-box';
                return 'Storage Box Deposit';
                break;

            default;
                return $this->entity->reason;
        }
    }

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

    public function amount()
    {
        return '&pound;'.$this->entity->amount;
    }
} 