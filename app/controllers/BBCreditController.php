<?php 

class BBCreditController extends \BaseController {

    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;

    public function __construct(\BB\Repo\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($userId)
    {
        //Verify the user can access this user record
        $user = User::findWithPermission($userId);

        return View::make('account.bbcredit.index')->with('user', $user);
    }

} 