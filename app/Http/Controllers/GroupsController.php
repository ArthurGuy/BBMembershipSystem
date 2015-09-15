<?php namespace BB\Http\Controllers;

use BB\Entities\Role;
use Response;
use View;

class GroupsController extends Controller
{

    /**
     * Display all the groups
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::with('users')->get();
        return View::make('groups.index')->with('roles', $roles);
    }

    /**
     * Display a specific group
     *
     * @param string $roleName
     *
     * @return Response
     */
    public function show($roleName)
    {
        $role = Role::with('Users')->where('name', $roleName)->first();
        return View::make('groups.show')->with('role', $role);
    }

}
