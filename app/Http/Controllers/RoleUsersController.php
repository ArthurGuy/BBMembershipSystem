<?php namespace BB\Http\Controllers;

use BB\Entities\Role;
use BB\Entities\User;
use BB\Validators\RoleUserValidator;

class RoleUsersController extends Controller
{

    /**
     * @var RoleUserValidator
     */
    private $roleUserValidator;

    public function __construct(RoleUserValidator $roleUserValidator)
    {
        $this->roleUserValidator = $roleUserValidator;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($roleId)
    {
        $formData = \Request::only(['user_id']);
        $this->roleUserValidator->validate($formData);

        $role = Role::findOrFail($roleId);

        //If the user isnt already a member add them
        if ( ! $role->users()->get()->contains($formData['user_id'])) {
            $role->users()->attach($formData['user_id']);
        }

        return \Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $roleId
     * @param $userId
     * @return Response
     */
    public function destroy($roleId, $userId)
    {
        $role = Role::findOrFail($roleId);

        $role->users()->detach($userId);

        return \Redirect::back();
    }


}
