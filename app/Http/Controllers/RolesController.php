<?php namespace BB\Http\Controllers;

use BB\Entities\Role;

class RolesController extends Controller
{
    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;

    function __construct(\BB\Repo\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

    public function memberList($roleName)
    {
        $role = Role::with('Users')->where('name', $roleName)->first();
        return \View::make('roles.member-list')->with('role', $role);
    }

    public function groupList()
    {
        $roles = Role::all();
        return \View::make('roles.group-list')->with('roles', $roles);
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
