<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter
{

    public function paymentMethod()
    {
        switch ($this->entity->payment_method) {
            case 'gocardless':
                return 'Direct Debit';

            case 'paypal':
                return 'PayPal';

            case 'standing-order':
                return 'Standing Order';
        }
        return $this->entity->payment_method;
    }

    public function subscriptionExpiryDate()
    {
        if ($this->entity->subscription_expires && $this->entity->subscription_expires->year > 0)
            return $this->entity->subscription_expires->toFormattedDateString();
        else
            return '-';

    }
} 