<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class SubscriptionChargePresenter extends Presenter
{

    public function status()
    {
        switch ($this->entity->status) {

            case 'pending';
                return 'Pending';

            case 'due';
                return 'Due';

            case 'processing';
                return 'Processing';

            case 'paid';
                return 'Paid';

            case 'cancelled';
                return 'Cancelled';

            default;
                return $this->entity->status;
        }
    }

    public function rowClass()
    {
        switch ($this->entity->status) {

            case 'pending';
                return '';

            case 'due';
                return 'warning';

            case 'processing';
                return 'info';

            case 'paid';
                return 'success';

            case 'cancelled';
                return 'text-muted';

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
        if (($this->entity->status == 'processing') || ($this->entity->status == 'paid')) {
            return '&pound;' . $this->entity->amount;
        }
        return '';
    }
} 