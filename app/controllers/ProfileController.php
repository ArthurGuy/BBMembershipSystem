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

    function __construct(\BB\Repo\ProfileDataRepository $profileRepo, \BB\Validators\ProfileValidator $profileValidator)
    {
        $this->profileRepo = $profileRepo;
        $this->profileValidator = $profileValidator;
    }

    public function edit($userId)
    {
        $profileData = $this->profileRepo->getUserProfile($userId);
        $this->layout->content = View::make('account.profile.edit')->with('profileData', $profileData)->with('userId', $userId);
    }

    public function update()
    {

    }

} 