<?php namespace BB\Presenters;

use Carbon\Carbon;
use Laracasts\Presenter\Presenter;

class EquipmentPresenter extends Presenter
{

    public function livesIn()
    {
        $string = $this->entity->room;
        if ($this->entity->detail) {
            $string .= ' (' . $this->entity->detail . ')';
        }
        return $string;
    }

    public function manufacturerModel()
    {
        $string = $this->entity->manufacturer;
        if ($this->entity->model_number) {
            $string .= ' (' . $this->entity->model_number . ')';
        }
        return $string;
    }

    public function description()
    {
        return nl2br($this->entity->description);
    }

    public function purchaseDate()
    {
        if (!$this->entity->obtained_at) {
            return null;
        }
        return $this->entity->obtained_at->toFormattedDateString();
    }

    public function accessFee()
    {
        return '&pound' . number_format($this->entity->access_fee, 0);
    }


} 