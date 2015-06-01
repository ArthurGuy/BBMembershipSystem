<?php namespace BB\Presenters;

use Carbon\Carbon;
use Laracasts\Presenter\Presenter;

class EquipmentLogPresenter extends Presenter
{

    public function reason()
    {
        return ucfirst($this->entity->reason);
    }

    public function started()
    {
        return $this->entity->started->format('d/m/Y H:i:s');
    }

    public function timeUsed()
    {
        $delta = $this->entity->finished->diffInSeconds($this->entity->started);

        $hours = (int)($delta / 3600);
        $seconds = $delta % 3600; //seconds after hours taken away
        $minutes = (int)($seconds / 60);
        $seconds = $seconds % 60;

        $txt = '';

        if ($hours > 0) {
            $txt .= $hours . ' hour';
            $txt .= $hours == 1 ? '' : 's';
            $txt .= ' ';
        }

        if ($minutes > 0) {
            $txt .= $minutes . ' minute';
            $txt .= $minutes == 1 ? '' : 's';
            $txt .= ' ';
        }

        if ($seconds > 0) {
            $txt .= $seconds . ' second';
            $txt .= $seconds == 1 ? '' : 's';
            $txt .= ' ';
        }

        return trim($txt);
    }

} 