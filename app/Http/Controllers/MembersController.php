<?php namespace BB\Http\Controllers;

use BB\Entities\User;

class MembersController extends Controller
{
    
    /**
     * @var
     */
    private $profileRepo;
    /**
     * @var \BB\Repo\ProfileSkillsRepository
     */
    private $profileSkillsRepository;
    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;

    /**
     * @param \BB\Repo\ProfileDataRepository   $profileRepo
     * @param \BB\Repo\ProfileSkillsRepository $profileSkillsRepository
     * @param \BB\Repo\UserRepository          $userRepository
     */
    function __construct(\BB\Repo\ProfileDataRepository $profileRepo, \BB\Repo\ProfileSkillsRepository $profileSkillsRepository, \BB\Repo\UserRepository $userRepository)
    {
        $this->profileRepo = $profileRepo;
        $this->profileSkillsRepository = $profileSkillsRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->getActivePublicList(!\Auth::guest());
        return \View::make('members.index')->withUsers($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        if (\Auth::guest() && $user->profile_private) {
            return \Response::make('', 404);
        }

        $profileData = $this->profileRepo->getUserProfile($id);
        $userSkills = array_intersect_ukey($this->profileSkillsRepository->getAll(), array_flip($profileData->skills), [$this, 'key_compare_func']);
        return \View::make('members.show')->with('user', $user)->with('profileData', $profileData)->with('userSkills', $userSkills);
    }

    private function key_compare_func($key1, $key2)
    {
        if ($key1 == $key2) {
            return 0;
        } else if ($key1 > $key2) {
            return 1;
        } else {
            return -1;
        }
    }

}
