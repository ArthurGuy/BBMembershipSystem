<?php

class MembersController extends \BaseController {

    protected $layout = 'layouts.main';

	public function index()
	{
        $users = User::activePublicList();
        $this->layout->content = View::make('members.index')->withUsers($users);
	}


}
