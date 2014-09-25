<?php

class MembersController extends \BaseController {
    
    /**
     * @var
     */
    private $profileRepo;

    /**
     * @param \BB\Repo\ProfileDataRepository $profileRepo
     */
    function __construct(\BB\Repo\ProfileDataRepository $profileRepo)
    {
        $this->profileRepo = $profileRepo;
    }

	public function index()
	{
        $users = User::activePublicList();
        return View::make('members.index')->withUsers($users);
	}

    public function show($id)
    {
        $user = User::findOrFail($id);
        $profileData = $this->profileRepo->getUserProfile($id);
        return View::make('members.show')->with('user', $user)->with('profileData', $profileData);
    }
}
