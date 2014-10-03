<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class PaymentPresenter extends Presenter
{

    public function reason()
    {
        switch ($this->entity->reason) {
            case 'subscription';
                return 'Subscription';

            case 'unknown';
                return 'Unknown';

            case 'induction';
                return 'Equipment Induction';

            case 'door-key';
                return 'Key Deposit';

            case 'storage-box';
                return 'Storage Box Deposit';

            default;
                return $this->entity->reason;
        }
    }

    public function status()
    {
        switch ($this->entity->status) {
            case 'pending';
                return 'Pending Confirmation';

            case 'paid';
            case 'withdrawn';
                return 'Paid';

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

            case 'paypal':
                return 'PayPal';

            case 'standing-order':
                return 'Standing Order';

            case 'manual':
                return 'Manual';

            case 'cash':
                return 'Cash';

            case 'other':
                return 'Other';

            default;
                return $this->entity->source;
        }
    }

    public function amount()
    {
        return '&pound;'.$this->entity->amount;
    }
} 