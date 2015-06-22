<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class ExpensePresenter extends Presenter
{

    public function category()
    {
        switch ($this->entity->category) {
            case 'consumables':
                return 'Consumables';
            case 'food':
                return 'Food';
            case 'equipment-repair':
                return 'Equipment Repair';
            case 'tools':
                return 'Tools';
            case 'infrastructure':
                return 'Infrastructure';
            case 'promotion':
                return 'Promotional Materials';
            default:
                return $this->entity->category;
        }
    }

    public function expense_date()
    {
        return $this->entity->expense_date->toFormattedDateString();
    }

    public function amount()
    {
        return '&pound;' . number_format($this->entity->amount / 100, 2);
    }

    public function file()
    {
        return 'https://s3-eu-west-1.amazonaws.com/' . env('S3_BUCKET', '') . '/' . $this->entity->file;
    }

} 