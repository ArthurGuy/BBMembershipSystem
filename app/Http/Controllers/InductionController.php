<?php namespace BB\Http\Controllers;

use BB\Entities\Induction;
use BB\Exceptions\PaymentException;
use BB\Repo\PaymentRepository;

class InductionController extends Controller
{

    /**
     * @var \BB\Repo\InductionRepository
     */
    private $inductionRepository;
    /**
     * @var \BB\Repo\EquipmentRepository
     */
    private $equipmentRepository;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @param \BB\Repo\InductionRepository $inductionRepository
     */
    function __construct(\BB\Repo\InductionRepository $inductionRepository, \BB\Repo\EquipmentRepository $equipmentRepository, PaymentRepository $paymentRepository)
    {
        $this->inductionRepository = $inductionRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->paymentRepository = $paymentRepository;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param      $userId
     * @param  int $id
     * @throws \BB\Exceptions\NotImplementedException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($userId, $id)
    {
        $induction = Induction::findOrFail($id);

        if (\Input::get('mark_trained', false)) {
            $induction->trained = \Carbon\Carbon::now();
            $induction->trainer_user_id = \Input::get('trainer_user_id', false);
            $induction->save();
        } elseif (\Input::get('is_trainer', false)) {
            $induction->is_trainer = true;
            $induction->save();
        } elseif (\Input::get('cancel_payment', false)) {
            if ($induction->trained) {
                throw new \BB\Exceptions\NotImplementedException();
            }
            //$payment = $this->paymentRepository->getById($induction->payment_id);
            $this->paymentRepository->refundPaymentToBalance($induction->payment_id);
            $this->inductionRepository->delete($id);
        } else {
            throw new \BB\Exceptions\NotImplementedException();
        }
        \Notification::success("Updated");
        return \Redirect::route('account.show', $userId);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


}
