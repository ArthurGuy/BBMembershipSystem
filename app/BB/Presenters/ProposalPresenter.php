<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class ProposalPresenter extends Presenter {

    public function end_date()
    {
        return $this->entity->end_date->toFormattedDateString();
    }

    public function created_at()
    {
        return $this->entity->created_at->toFormattedDateString();
    }

    public function description()
    {
        return nl2br($this->entity->description);
    }

    public function status()
    {
        if ($this->entity->isOpen()) {
            return 'Open';
        } else {
            return 'Closed';
        }
    }

    public function outcome()
    {
        if ($this->entity->isOpen()) {
            return null;
        } elseif ($this->entity->processed) {
            $outputText = null;
            if ($this->entity->result > 0) {
                $outputText .= '<strong>Passed</strong>: ';
            } else {
                $outputText .= '<strong>Failed</strong>: ';
            }
            $outputText .= $this->entity->votes_for.' for, '.$this->entity->votes_against.' against';
            if ($this->entity->abstentions > 0) {
                $outputText .= ', '.$this->entity->abstentions.' abstentions';
            }
            return $outputText;
        } else {
            return 'Waiting on a final vote count';
        }
    }

} 