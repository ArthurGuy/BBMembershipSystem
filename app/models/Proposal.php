<?php

use Carbon\Carbon;
use Laracasts\Presenter\PresentableTrait;

class Proposal extends Eloquent {

    use PresentableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'proposals';

    protected $presenter = 'BB\Presenters\ProposalPresenter';

    public function getDates()
    {
        return array('created_at', 'updated_at', 'end_date');
    }

    public function votes()
    {
        return $this->hasMany('ProposalVote');
    }

    public function isOpen()
    {
        return $this->end_date->gt(Carbon::now());
    }


} 