<?php

use BB\Entities\Payment;
use BB\Entities\User;
use BB\Repo\SubscriptionChargeRepository;

class StatementImportController extends \BaseController
{
    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;

    public function __construct(SubscriptionChargeRepository $subscriptionChargeRepository)
    {
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('statement-import.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

        $spreadsheetPath = Input::file('statement')->getRealPath();
        $testProcess = Request::get('test');
        if ($testProcess) {
            $testProcess = true;
            echo "Test Mode - no payment records are being created<br /><br />";
        } else {
            $testProcess = false;
            echo "Live Mode - Payments have been created<br /><br />";
        }

        $reader = new SpreadsheetReader($spreadsheetPath);
        $reader->ChangeSheet(0);


        $stringMatchUsers = User::active()->where('import_match_string', '!=', '')->get();
        $users = User::active()->get();


        echo '<br />' . PHP_EOL;
        echo '<table width="100%">';
        foreach ($reader as $i=>$row) {
            echo "<tr>";
            $subPayment = false;
            $balancePayment = false;
            $paymentReference = null;
            //print_r($row);

            //If the payment isn't a credit then we don't care about it
            //if (($row[1] != 'CR') && ($row[1]) != 'BP')
            if ($row[1] != 'CR') {
                continue;
            }

            $date = new \Carbon\Carbon($row[0]);
            echo "<td>" . $date . '</td>';

            //echo "<td>".$row[1].'</td>';

            if (strpos(strtoupper($row[2]), 'SUB') !== false) {
                $subPayment = true;
            } elseif (strpos($row[2], 'MEMBERSHIP') !== false) {
                $subPayment = true;
            } elseif (strpos($row[2], '-BALANCE-') !== false) {
                $balancePayment = true;
                $descriptionParts = explode('-BALANCE-', $row[2]);
                if (is_array($descriptionParts) && count($descriptionParts) > 1) {
                    $paymentReference = strtolower($descriptionParts[1]);
                }
            }

            if ($subPayment) {
                echo '<td>SUB</td>';
                $reasonString = 'subscription';
            } elseif ($balancePayment) {
                echo '<td>Balance</td>';
                $reasonString = 'balance';
            } else {
                echo '<td></td>';
                $reasonString = 'unknown';
            }

            $matchedUser = false;
            $paymentDescription = strtolower($row[2]);

            //Try matching against specific match strings first
            foreach ($stringMatchUsers as $user) {
                if (strpos($paymentDescription, strtolower($user->import_match_string)) !== false) {
                    $matchedUser = $user;
                    break;
                }
            }

            //If there was no match do a general surname match
            if ( ! $matchedUser) {
                foreach ($users as $user) {
                    if (strpos($paymentDescription, strtolower($user->family_name)) !== false) {
                        $matchedUser = $user;
                        break;
                    }
                }
            }
            if ($matchedUser) {
                echo '<td>' . $matchedUser->name . '</td>';
                if ($subPayment) {
                    $subCharge = $this->subscriptionChargeRepository->findCharge($matchedUser->id, $date);
                    if ($subCharge) {
                        echo '<td>Sub Charge: ' . $subCharge->amount . '</td>';
                    } else {
                        echo '<td>No Sub Charge</td>';
                        $subPayment = false;
                        $reasonString = 'balance';
                    }
                }
            } else {
                echo '<td>Unknown</td><td></td>';
            }

            echo '<td>' . $row[2] . '</td>';

            //echo '<td>'.$row[3].'</td>';

            echo '<td>' . $row[4] . '</td>';

            echo "</tr>";

            if ( ! $testProcess && $matchedUser) {
                if ($subPayment) {
                    if (isset($subCharge) && $subCharge) {
                        $paymentReference = $subCharge->id;
                        $this->subscriptionChargeRepository->markChargeAsPaid($subCharge->id, $date, $row[4]);
                    }
                }

                Payment::create([
                    'created_at' => $date,
                    'reason' => $reasonString,
                    'source' => 'standing-order',
                    'user_id' => $matchedUser->id,
                    'amount' => $row[4],
                    'fee' => 0,
                    'amount_minus_fee' => $row[4],
                    'status' => 'paid',
                    'reference' => $paymentReference
                ]);
                if ($subPayment) {
                    if ($matchedUser->payment_method == 'standing-order') {
                        $matchedUser->monthly_subscription = $row[4];
                    }

                    $matchedUser->save();
                }
            }
        }
        echo "</table>";
        exit;
    }





}
