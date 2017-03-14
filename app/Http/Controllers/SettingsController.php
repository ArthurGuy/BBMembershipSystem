<?php namespace BB\Http\Controllers;

use BB\Entities\Settings;

class SettingsController extends Controller
{

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update()
	{
        $input = \Input::only('key', 'value');

        Settings::change($input['key'], $input['value']);

        \Notification::success("Setting updated");
        return redirect()->back()->withInput();
	}

}
