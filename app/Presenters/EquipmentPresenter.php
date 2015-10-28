<?php namespace BB\Presenters;

use Carbon\Carbon;
use BB\Helpers\Presenter;
use Michelf\Markdown;

class EquipmentPresenter extends Presenter
{

    public function livesIn()
    {
        $room = $this->entity->room();
        if (!$room) {
            return null;
        }
        $string = $room->name();
        $string = str_replace('-', ' ', $string);
        $string = ucfirst($string);
        if ($this->entity->roomDetail()) {
            $string .= ' (' . $this->entity->roomDetail() . ')';
        }
        return $string;
    }

    public function manufacturerModel()
    {
        $string = $this->entity->properties()->manufacturer();
        if ($this->entity->properties()->modelNumber()) {
            $string .= ' (' . $this->entity->properties()->modelNumber() . ')';
        }
        return $string;
    }

    public function description()
    {
        return Markdown::defaultTransform($this->entity->description());
    }

    public function help_text()
    {
        return Markdown::defaultTransform($this->entity->helpText());
    }

    public function purchaseDate()
    {
        if ( ! $this->entity->obtainedAt()) {
            return null;
        }
        return $this->entity->obtainedAt()->toFormattedDateString();
    }

    public function accessFee()
    {
        return '&pound' . number_format($this->entity->access_fee, 0);
    }

    public function usageCost()
    {
        if ($this->entity->cost()->usageCost()) {
            return '&pound' . number_format($this->entity->cost()->usageCost(), 2) . ' per ' . $this->entity->cost()->usageCostPer();
        }
    }

    public function ppe()
    {
        $ppeHtml = '';
        foreach ($this->entity->ppe() as $ppe) {
            $ppeHtml .= '<img src="/img/ppe/' . $ppe . '.jpg" height="140" class="ppe-image">';
        }
        $ppeHtml .= '<br /><br />';
        return $ppeHtml;
    }


} 