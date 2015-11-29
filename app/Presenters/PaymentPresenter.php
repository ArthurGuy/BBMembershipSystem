<?php namespace BB\Presenters;

use Laracasts\Presenter\Presenter;

class PaymentPresenter extends Presenter
{

    public function reason()
    {
        switch ($this->entity->reason) {
            case 'subscription':
                return 'Subscription';
            case 'unknown':
                return 'Unknown';
            case 'induction':
                return 'Equipment Access Fee';
            case 'door-key':
                return 'Key Deposit';
            case 'storage-box':
                return 'Storage Box Deposit';
            case 'balance':
                return 'Balance Top Up';
            case 'equipment-fee':
                return 'Equipment Costs';
            case 'withdrawal':
                return 'Withdrawal';
            case 'consumables':
                return 'Consumables (' . $this->entity->reference . ')';
            case 'transfer':
                return 'Transfer (to user:' . $this->entity->reference . ')';
            case 'donation':
                return 'Donation';
            default:
                return $this->entity->reason;
        }
    }

    public function status()
    {
        switch ($this->entity->status) {
            case 'pending':
                return 'Pending Confirmation';
            case 'cancelled':
                return 'Cancelled';
            case 'paid':
            case 'withdrawn':
                return 'Paid';
            default:
                return $this->entity->status;
        }
    }

    public function date()
    {
        return $this->entity->created_at->toFormattedDateString();
    }

    public function method()
    {
        switch ($this->entity->source) {
            case 'gocardless':
            case 'gocardless-variable':
                return 'Direct Debit';
            case 'stripe':
                return 'Credit/Debit Card';
            case 'paypal':
                return 'PayPal';
            case 'standing-order':
                return 'Standing Order';
            case 'manual':
                return 'Manual';
            case 'cash':
                return 'Cash' . ($this->entity->source_id? ' (' . $this->entity->source_id . ')':'');
            case 'other':
                return 'Other';
            case 'balance':
                return 'BB Balance';
            case 'reimbursement':
                return 'Reimbursement';
            case 'transfer':
                return 'Transfer (from user:' . $this->entity->reference . ')';
            default:
                return $this->entity->source;
        }
    }

    public function amount()
    {
        return '&pound;' . $this->entity->amount;
    }

    public function balanceAmount()
    {
        if ($this->entity->source == 'balance') {
            return '-&pound;' . $this->entity->amount;
        } elseif ($this->entity->reason == 'balance') {
            return '&pound;' . $this->entity->amount;
        }
    }

    public function balanceRowClass()
    {
        if ($this->entity->source == 'balance') {
            return 'danger';
        } elseif ($this->entity->reason == 'balance') {
            return 'success';
        }
    }
} 