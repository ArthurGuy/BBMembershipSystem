<?php namespace BB\Services;

use BB\Repo\PaymentRepository;
use BB\Repo\StorageBoxRepository;

class MemberStorage {

    /**
     * @var int
     */
    private $memberId;

    /**
     * @var StorageBoxRepository
     */
    private $storageBoxRepository;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var
     */
    private $memberBoxes;
    /**
     * @var
     */
    private $boxPayments;


    const BOX_DEPOSIT = 5;

    const VOLUME_ALLOWED = 19;

    public function __construct(StorageBoxRepository $storageBoxRepository, PaymentRepository $paymentRepository)
    {
        $this->storageBoxRepository = $storageBoxRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param int $memberId
     */
    public function setMember($memberId)
    {
        $this->memberId = $memberId;

        $this->memberBoxes = $this->storageBoxRepository->getMemberBoxes($this->memberId);

        $this->boxPayments = $this->paymentRepository->getStorageBoxPayments($this->memberId);
    }

    /**
     * Work out how much box volume the member has taken
     * @return int
     */
    public function volumeTaken()
    {
        $takenVolume = 0;
        foreach ($this->memberBoxes as $box) {
            $takenVolume += $box->size;
        }

        return $takenVolume;
    }

    /**
     * How much volume do they have left
     * @return int
     */
    public function volumeAvailable()
    {
        return self::VOLUME_ALLOWED - $this->volumeTaken();
    }

    /**
     * Return a collection of the users boxes
     * @return mixed
     */
    public function getMemberBoxes()
    {
        return $this->memberBoxes;
    }

    /**
     * How many boxes have they taken
     * @return int
     */
    public function getNumBoxesTaken()
    {
        return count($this->getMemberBoxes());
    }

    /**
     * Return a collection of the users box payments
     * @return mixed
     */
    public function getBoxPayments()
    {
        return $this->boxPayments;
    }

    /**
     * Get the total the user has spent on storage
     * @return double
     */
    public function getPaymentTotal()
    {
        $paymentTotal = 0;
        foreach ($this->boxPayments as $payment) {
            $paymentTotal += $payment->amount;
        }
        return $paymentTotal;
    }

    /**
     * How many boxes has the user paid for
     * @return int
     */
    public function getNumBoxesPaidFor()
    {
        return (int)($this->getPaymentTotal() / self::BOX_DEPOSIT);
    }

    /**
     * How much storage box money do they have left that hasn't been assigned to boxes
     * @return int
     */
    public function getMoneyAvailable()
    {
        return $this->getRemainingBoxesPaidFor() * self::BOX_DEPOSIT;
    }

    /**
     * How many boxes can they still claim based on existing payments
     * @return int
     */
    public function getRemainingBoxesPaidFor()
    {
        return $this->getNumBoxesPaidFor() - $this->getNumBoxesTaken();
    }

}