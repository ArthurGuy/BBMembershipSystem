<?php 

class ProfileController extends \BaseController {

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
     * @var \BB\Helpers\UserImage
     */
    private $userImage;

    /**
     * @param \BB\Repo\ProfileDataRepository   $profileRepo
     * @param \BB\Validators\ProfileValidator  $profileValidator
     * @param \BB\Repo\ProfileSkillsRepository $profileSkillsRepository
     */
    function __construct(
        \BB\Repo\ProfileDataRepository $profileRepo,
        \BB\Validators\ProfileValidator $profileValidator,
        \BB\Repo\ProfileSkillsRepository $profileSkillsRepository,
        \BB\Helpers\UserImage $userImage)
    {
        $this->profileRepo = $profileRepo;
        $this->profileValidator = $profileValidator;
        $this->profileSkillsRepository = $profileSkillsRepository;
        $this->userImage = $userImage;
    }

    public function edit($userId)
    {
        //Verify the user can access this user record
        $user = User::findWithPermission($userId);

        $profileData = $this->profileRepo->getUserProfile($userId);
        $skills = $this->profileSkillsRepository->getSelectArray();
        return View::make('account.profile.edit')
            ->with('profileData', $profileData)
            ->with('userId', $userId)
            ->with('skills', $skills)
            ->with('user', $user);
    }

    public function update($userId)
    {
        //Verify the user can access this user record - we don't need the record just the auth check
        $user = User::findWithPermission($userId);

        $input = Input::all();

        if (empty($input['profile_photo_private']))
            $input['profile_photo_private'] = false;

        //If the user hasnt provided a new image unset the field so we dont clear the old one
        if (empty($input['profile_photo']))
            unset($input['profile_photo']);

        $this->profileValidator->validate($input, $userId);

        $this->profileRepo->update($userId, $input);

        if (Input::file('profile_photo'))
        {
            try
            {
                $this->userImage->uploadPhoto($user->hash, Input::file('profile_photo')->getRealPath());

                //$user->profilePhoto(true);
                $this->profileRepo->update($userId, ['profile_photo'=>1]);
            }
            catch (\Exception $e)
            {
                Log::error($e);
            }
        }

        Notification::success("Profile Updated");
        return Redirect::route('members.show', $userId);
    }

} 