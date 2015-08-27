<?php namespace BB\Http\Controllers;

use BB\Events\MemberActivity;
use BB\Repo\ACSNodeRepository;
use BB\Repo\PaymentRepository;
use BB\Services\KeyFobAccess;

class ACSSparkController extends Controller
{

    /**
     * @var ACSNodeRepository
     */
    private $acsNodeRepository;
    /**
     * @var KeyFobAccess
     */
    private $keyFobAccess;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    function __construct(ACSNodeRepository $acsNodeRepository, KeyFobAccess $keyFobAccess, PaymentRepository $paymentRepository)
    {
        $this->acsNodeRepository = $acsNodeRepository;
        $this->keyFobAccess     = $keyFobAccess;
        $this->paymentRepository = $paymentRepository;
    }

    public function handle()
    {
        $data = \Request::only(['device', 'tag', 'service']);

        try {
            $keyFob = $this->keyFobAccess->lookupKeyFob($data['tag']);
        } catch (\Exception $e) {
            \Log::debug(json_encode($data));
            return \Response::make('Not found', 404);
        }
        $user = $keyFob->user()->first();

        $this->paymentRepository->recordPayment($data['service'], $user->id, 'balance', null, 0.05, 'paid', 0, $data['device']);

        event(new MemberActivity($keyFob, $data['service']));

        return \Response::make('OK', 201);
    }
}