<?php namespace BB\Presenters;

use Carbon\Carbon;
use Laracasts\Presenter\Presenter;

class EquipmentLogPresenter extends Presenter
{

    public function reason()
    {
        switch ($this->entity->reason) {
            case 'subscription';
                return 'Subscription';

            case 'unknown';
                return 'Unknown';

            case 'induction';
                return 'Equipment Access Fee';

            case 'door-key';
                return 'Key Deposit';

            case 'storage-box';
                return 'Storage Box Deposit';

            case 'balance';
                return 'Credit Top Up';

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

    public function started()
    {
        return $this->entity->started->format('d/m/Y H:i:s');
    }

    public function timeUsed()
    {
        $delta = $this->entity->finished->diffInSeconds($this->entity->started);

        // a little weeks per month, 365 days per year... good enough!!
        $divs = array(
            'second' => Carbon::SECONDS_PER_MINUTE,
            'minute' => Carbon::MINUTES_PER_HOUR,
            'hour' => Carbon::HOURS_PER_DAY,
            'day' => Carbon::DAYS_PER_WEEK,
            'week' => 30 / Carbon::DAYS_PER_WEEK,
            'month' => Carbon::MONTHS_PER_YEAR
        );

        $unit = 'year';

        foreach ($divs as $divUnit => $divValue) {
            if ($delta < $divValue) {
                $unit = $divUnit;
                break;
            }

            $delta = $delta / $divValue;
        }

        $delta = (int) $delta;

        if ($delta == 0) {
            $delta = 1;
        }

        $txt = $delta . ' ' . $unit;
        $txt .= $delta == 1 ? '' : 's';

        return $txt . '';
    }

} 