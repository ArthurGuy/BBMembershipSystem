<?php

namespace BB\Events;

use BB\Entities\Expense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewExpenseSubmitted extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var Expense
     */
    public $expense;

    /**
     * Create a new event instance.
     *
     * @param Expense $expense
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user.' . $this->expense->user->id];
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'expense' => [
                'id'          => $this->expense->id,
                'category'    => $this->expense->category,
                'description' => $this->expense->description,
                'amount'      => $this->expense->amount,
                'file'        => $this->expense->file,
            ],
            'user'    => [
                'id'          => $this->expense->user->id,
                'name'        => $this->expense->user->name,
                'hash'        => $this->expense->user->hash,
            ]
        ];
    }
}
