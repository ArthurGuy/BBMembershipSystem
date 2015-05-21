<?php

use Carbon\Carbon;

class ExpensesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return \BB\Entities\Expense::where('user_id', Auth::user()->id)->get();
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
		$data = Request::only(['category', 'description', 'amount', 'expense_date', 'file']);
        $data['user_id'] = Auth::user()->id;
        $data['expense_date'] = Carbon::now();

        $expense = \BB\Entities\Expense::create($data);

        return $expense;
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return \BB\Entities\Expense::findOrFail($id);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $data = Request::only(['category', 'description', 'amount', 'expense_date']);

        $expense = \BB\Entities\Expense::findOrFail($id);
        $expense = $expense->update($data);

        return $expense;
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $expense = \BB\Entities\Expense::findOrFail($id);
        $expense->delete();
	}


}
