<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class SubscriptionChargePresenter extends Presenter
{

    public function status()
    {
        switch ($this->entity->status) {
            case 'draft';
                return 'Draft';

            case 'pending';
                return 'Pending';

            case 'paid';
                return 'Paid';

            case 'cancelled';
                return 'Cancelled';

            default;
                return $this->entity->status;
        }
    }

    public function charge_date()
    {
        return $this->entity->charge_date->toFormattedDateString();
    }

    /**
     * @return null|string
     */
    public function payment_date()
    {
        if ($this->entity->status == 'paid') {
            return $this->entity->payment_date->toFormattedDateString();
        }
        return null;
    }

    public function amount()
    {
        return '&pound;'.$this->entity->amount;
    }
} 