<?php

namespace BB\Listeners;

use BB\Repo\PaymentRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Mail\Mailer;
use BB\Events\UnknownPayPalPaymentReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailDonorAboutUnknownPayPalPayment implements ShouldQueue
{
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * Create the event listener.
     *
     * @param Mailer            $mailer
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(Mailer $mailer, PaymentRepository $paymentRepository)
    {
        $this->mailer            = $mailer;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Handle the event.
     *
     * @param UnknownPayPalPaymentReceived $event
     */
    public function handle(UnknownPayPalPaymentReceived $event)
    {
        $email = $event->emailAddress;
        $payment = $this->paymentRepository->getById($event->paymentId);
        $this->mailer->send('emails.paypal-donation', ['email' => $email, 'payment' => $payment], function ($m) use ($email) {
            $m->to($email);
            $m->cc('trustees@buildbrighton.com');
            $m->subject('Unknown PayPal Payment Received');
        });
    }
}
