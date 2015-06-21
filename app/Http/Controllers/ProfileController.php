<?php namespace BB\Http\Controllers;

use BB\Entities\User;

class ProfileController extends Controller
{

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
        return \View::make('account.profile.edit')
            ->with('profileData', $profileData)
            ->with('userId', $userId)
            ->with('skills', $skills)
            ->with('user', $user);
    }

    public function update($userId)
    {
        //Verify the user can access this user record - we don't need the record just the auth check
        $user = User::findWithPermission($userId);

        $input = \Input::all();
        //Clear the profile photo field as this is handled separately below.
        unset($input['new_profile_photo']);

        if (empty($input['profile_photo_private'])) {
            $input['profile_photo_private'] = false;
        }

        //Trim all the data so some of the validation doesn't choke on spaces
        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $input[$key] = trim($value);
            }
        }

        $this->profileValidator->validate($input, $userId);

        $this->profileRepo->update($userId, $input);

        if (\Input::file('new_profile_photo')) {
            try {
                $this->userImage->uploadPhoto($user->hash, \Input::file('new_profile_photo')->getRealPath(), true);

                $this->profileRepo->update($userId, ['new_profile_photo'=>1]);

                \Notification::success("Photo uploaded, it will be checked and appear shortly");
            } catch (\Exception $e) {
                \Log::error($e);
            }
        } else {
            \Notification::success("Profile Updated");
        }

        return \Redirect::route('members.show', $userId);
    }

} 