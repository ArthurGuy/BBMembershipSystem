<?php 

class ProfileController extends \BaseController {

    protected $layout = 'layouts.main';

    /**
     * @var \BB\Repo\ProfileDataRepository
     */
    private $profileRepo;
    /**
     * @var \BB\Validators\ProfileValidator
     */
    private $profileValidator;
    /**
     * @var \BB\Repo\ProfileSkillsRepository
     */
    private $profileSkillsRepository;

    /**
     * @param \BB\Repo\ProfileDataRepository   $profileRepo
     * @param \BB\Validators\ProfileValidator  $profileValidator
     * @param \BB\Repo\ProfileSkillsRepository $profileSkillsRepository
     */
    function __construct(\BB\Repo\ProfileDataRepository $profileRepo, \BB\Validators\ProfileValidator $profileValidator, \BB\Repo\ProfileSkillsRepository $profileSkillsRepository)
    {
        $this->profileRepo = $profileRepo;
        $this->profileValidator = $profileValidator;
        $this->profileSkillsRepository = $profileSkillsRepository;
    }

    public function edit($userId)
    {
        //Verify the user can access this user record - we don't need the record just the auth check
        User::findWithPermission($userId);

        $profileData = $this->profileRepo->getUserProfile($userId);
        $skills = $this->profileSkillsRepository->getSelectArray();
        $this->layout->content = View::make('account.profile.edit')->with('profileData', $profileData)->with('userId', $userId)->with('skills', $skills);
    }

    public function update($userId)
    {
        //Verify the user can access this user record - we don't need the record just the auth check
        User::findWithPermission($userId);

        $this->profileValidator->validate(Input::all(), $userId);

        $this->profileRepo->update($userId, Input::all());

        Notification::success("Profile Updated");
        return Redirect::route('members.show', $userId);
    }

} 