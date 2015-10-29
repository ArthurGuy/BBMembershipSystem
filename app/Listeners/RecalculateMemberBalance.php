<?php

namespace BB\Listeners;

use BB\Events\MemberBalanceChanged;
use BB\Services\Credit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecalculateMemberBalance
{
    /**
     * @var Credit
     */
    private $memberCreditService;

    /**
     * Create the event listener.
     *
     * @param Credit $memberCreditService
     */
    public function __construct(Credit $memberCreditService)
    {
        $this->memberCreditService = $memberCreditService;
    }

    /**
     * Handle the event.
     *
     * @param  MemberBalanceChanged  $event
     * @return void
     */
    public function handle(MemberBalanceChanged $event)
    {
        $this->memberCreditService->setUserId($event->userId);
        $this->memberCreditService->recalculate();
    }
}
