<?php

namespace BB\Http\Controllers;

use BB\Entities\User;
use BB\Repo\PolicyRepository;
use BB\Repo\UserRepository;
use BB\Validators\InductionValidator;
use Illuminate\Http\Request;
use BB\Http\Requests;
use Michelf\Markdown;

class MemberInductionController extends Controller
{

    /**
     * @var PolicyRepository
     */
    private $policyRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var InductionValidator
     */
    private $inductionValidator;

    function __construct(PolicyRepository $policyRepository, UserRepository $userRepository, InductionValidator $inductionValidator)
    {
        $this->policyRepository = $policyRepository;
        $this->userRepository = $userRepository;
        $this->inductionValidator = $inductionValidator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findWithPermission($id);

        $document = $this->policyRepository->getByName('member-agreement');

        $htmlDocument = Markdown::defaultTransform($document);

        return view('account.induction')->with('user', $user)->with('document', $htmlDocument);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findWithPermission($id);

        $input = $request->only('rules_agreed', 'induction_completed');

        $this->inductionValidator->validate($input);

        $this->userRepository->recordInductionCompleted($id);

        return \Redirect::route('account.show', [$user->id]);
    }
}
