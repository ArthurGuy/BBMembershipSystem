<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class ProfileDataPresenter extends Presenter {


    public function gitHubLink()
    {
        if (!empty($this->entity->github)) {
            return 'https://github.com/'.e($this->entity->github);
        }
    }

    public function twitterLink()
    {
        if (!empty($this->entity->twitter)) {
            return 'https://twitter.com/'.e($this->entity->twitter);
        }
    }

    public function facebookLink()
    {
        if (!empty($this->entity->facebook)) {
            return 'https://www.facebook.com/'.e($this->entity->facebook);
        }
    }

    public function googlePlusLink()
    {
        if (!empty($this->entity->google_plus)) {
            return 'https://plus.google.com/+'.e($this->entity->google_plus);
        }
    }

    public function IRCLink()
    {
        if (!empty($this->entity->irc)) {
            return 'irc://irc.freenode.net/buildbrighton';
        }
    }

    public function description()
    {
        return nl2br(e($this->entity->description));
    }

} 