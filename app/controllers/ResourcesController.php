<?php

use Michelf\Markdown;

class ResourcesController extends \BaseController
{


    /**
     * @var \BB\Repo\PolicyRepository
     */
    private $policyRepository;

    function __construct(\BB\Repo\PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('resources.index');
    }

    public function viewPolicy($document)
    {
        $document = $this->policyRepository->getByName($document);

        $htmlDocument = Markdown::defaultTransform($document);

        return View::make('resources.policy')->with('document', $htmlDocument);
    }

}
