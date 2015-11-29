<?php namespace BB\Http\Controllers;

use BB\Events\NewExpenseSubmitted;
use BB\Exceptions\ImageFailedException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ExpensesController extends Controller {

    /**
     * @var \BB\Validators\ExpenseValidator
     */
    private $expenseValidator;
    /**
     * @var \BB\Repo\ExpenseRepository
     */
    private $expenseRepository;

    /**
     * @param \BB\Validators\ExpenseValidator $expenseValidator
     * @param \BB\Repo\ExpenseRepository      $expenseRepository
     */
    function __construct(\BB\Validators\ExpenseValidator $expenseValidator, \BB\Repo\ExpenseRepository $expenseRepository)
    {
        $this->expenseValidator = $expenseValidator;
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \BB\Exceptions\AuthenticationException
     */
	public function index()
	{
        $userId       = \Request::get('user_id', '');
        $sortBy       = \Request::get('sortBy', 'created_at');
        $direction    = \Request::get('direction', 'desc');

        if (\Request::ajax()) {
            if ($userId) {
                return \BB\Entities\Expense::where('user_id', $userId)->get();
            }
            return \BB\Entities\Expense::all();
        }

        if ($userId) {
            $this->expenseRepository->memberFilter($userId);
        }
        $expenses = $this->expenseRepository->getPaginated(compact('sortBy', 'direction'));

        return \View::make('expenses.index')->with('expenses', $expenses);
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
     * @throws ImageFailedException
     * @throws \BB\Exceptions\FormValidationException
     */
	public function store()
	{
		$data = \Request::only(['user_id', 'category', 'description', 'amount', 'expense_date', 'file']);

        $this->expenseValidator->validate($data);


        if (\Input::file('file')) {
            try {
                $filePath = \Input::file('file')->getRealPath();
                $ext = \Input::file('file')->guessClientExtension();
                $mimeType = \Input::file('file')->getMimeType();

                $newFilename = \App::environment().'/expenses/' . str_random() . '.' . $ext;

                Storage::put($newFilename, file_get_contents($filePath), 'public');

                $data['file'] = $newFilename;

            } catch(\Exception $e) {
                \Log::error($e);
                throw new ImageFailedException($e->getMessage());
            }
        }

        //Reformat from d/m/y to YYYY-MM-DD
        $data['expense_date'] = Carbon::createFromFormat('j/n/y', $data['expense_date']);

        if ($data['user_id'] != \Auth::user()->id) {
            throw new \BB\Exceptions\FormValidationException('You can only submit expenses for yourself', new \Illuminate\Support\MessageBag(['general'=>'You can only submit expenses for yourself']));
        }

        $expense = $this->expenseRepository->create($data);

        event(new NewExpenseSubmitted($expense));

        return \Response::json($expense, 201);
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
     * @param  int $id
     * @return Response
     * @throws \BB\Exceptions\AuthenticationException
     */
	public function update($id)
	{
        /**
        if (\Request::ajax()) {
            $data = \Request::only(['category', 'description', 'amount', 'expense_date']);

            $expense = \BB\Entities\Expense::findOrFail($id);
            $expense = $expense->update($data);

            return $expense;
        }
        */

        if ( ! \Auth::user()->hasRole('admin')) {
            throw new \BB\Exceptions\AuthenticationException();
        }

        $data = \Request::only('approve', 'decline');
        if ( ! empty($data['approve'])) {
            $this->expenseRepository->approveExpense($id, \Auth::user()->id);
        }
        if ( ! empty($data['decline'])) {
            $this->expenseRepository->declineExpense($id, \Auth::user()->id);
        }

        return \Redirect::route('expenses.index');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //$expense = \BB\Entities\Expense::findOrFail($id);
        //$expense->delete();
	}


}
