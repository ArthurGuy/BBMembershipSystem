<?php

class MembersController extends \BaseController {
    
    /**
     * @var
     */
    private $profileRepo;
    /**
     * @var \BB\Repo\ProfileSkillsRepository
     */
    private $profileSkillsRepository;

    /**
     * @param \BB\Repo\ProfileDataRepository   $profileRepo
     * @param \BB\Repo\ProfileSkillsRepository $profileSkillsRepository
     */
    function __construct(\BB\Repo\ProfileDataRepository $profileRepo, \BB\Repo\ProfileSkillsRepository $profileSkillsRepository)
    {
        $this->profileRepo = $profileRepo;
        $this->profileSkillsRepository = $profileSkillsRepository;
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
        $userSkills = array_intersect_ukey($this->profileSkillsRepository->getAll(), array_flip($profileData->skills), '_key_compare_func');
        return View::make('members.show')->with('user', $user)->with('profileData', $profileData)->with('userSkills', $userSkills);
    }

}
function _key_compare_func($key1, $key2)
{
    if ($key1 == $key2)
        return 0;
    else if ($key1 > $key2)
        return 1;
    else
        return -1;
}