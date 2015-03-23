<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

/**
 * @property string monthlySubscription
 * @property string paymentMethod
 */
class UserPresenter extends Presenter
{

    public function paymentMethod()
    {
        switch ($this->entity->payment_method) {
            case 'gocardless':
                return 'Direct Debit';

            case 'gocardless-variable':
                return 'Direct Debit';

            case 'paypal':
                return 'PayPal';

            case 'standing-order':
                return 'Standing Order';

            case '':
                return '-';
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

    public function cashBalance()
    {
        return '&pound;'.number_format(($this->entity->cash_balance / 100), 2);
    }

    public function monthlySubscription()
    {
        return '&pound;'.number_format(round($this->entity->monthly_subscription));
    }

    public function subscriptionDetailLine()
    {
        if ($this->entity->status == 'setting-up') {
            return '';
        }
        return ''.$this->monthlySubscription.' a month by '.$this->paymentMethod.'';
    }

} 