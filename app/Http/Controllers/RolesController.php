<?php namespace BB\Http\Controllers;

use BB\Entities\Role;
use BB\Validators\RoleValidator;

class RolesController extends Controller
{
    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;
    /**
     * @var RoleValidator
     */
    private $roleValidator;

    function __construct(\BB\Repo\UserRepository $userRepository, RoleValidator $roleValidator)
    {
        $this->userRepository = $userRepository;
        $this->roleValidator = $roleValidator;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::with('Users')->get();
        $memberList = $this->userRepository->getAllAsDropdown();
        return \View::make('roles.index')->with('roles', $roles)->with('memberList', $memberList);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $role = Role::findOrFail($id);

        $formData = \Request::only(['description', 'title', 'email_public', 'email_private', 'slack_channel']);
        $this->roleValidator->validate($formData);

        $role->update($formData);

        return \Redirect::back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        
    }


}
