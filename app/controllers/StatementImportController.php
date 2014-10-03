<?php

class StatementImportController extends \BaseController {

    protected $layout = 'layouts.main';


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $this->layout->content = View::make('statement-import.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

        $spreadsheetPath = Input::file('statement')->getRealPath();

        $reader = new SpreadsheetReader($spreadsheetPath);
        $reader->ChangeSheet(0);


        $stringMatchUsers = User::active()->where('import_match_string', '!=', '')->get();
        $users = User::active()->get();


        echo '<br />'.PHP_EOL;
        echo '<table width="100%">';
        foreach ($reader as $i=>$row)
        {
            echo "<tr>";
            $subPayment = false;
            //print_r($row);

            //If the payment isn't a credit then we don't care about it
            if ($row[1] != 'CR')
            {
                continue;
            }

            $date = new \Carbon\Carbon($row[0]);
            echo "<td>".$date.'</td>';

            if (strpos(strtoupper($row[2]), 'SUB') !== false)
            {
                $subPayment = true;
            }
            elseif (strpos($row[2], 'MEMBERSHIP') !== false)
            {
                $subPayment = true;
            }

            if ($subPayment)
            {
                echo '<td>SUB</td>';
                $reasonString = 'subscription';
            }
            else
            {
                echo '<td></td>';
                $reasonString = 'unknown';
            }

            $matchedUser = false;
            $paymentDescription = strtolower($row[2]);

            //Try matching against specific match strings first
            foreach ($stringMatchUsers as $user)
            {
                if (strpos($paymentDescription, strtolower($user->import_match_string)) !== false)
                {
                    $matchedUser = $user;
                    break;
                }
            }

            //If there was no match do a general surname match
            if (!$matchedUser)
            {
                foreach ($users as $user)
                {
                    if (strpos($paymentDescription, strtolower($user->family_name)) !== false)
                    {
                        $matchedUser = $user;
                        break;
                    }
                }
            }
            if ($matchedUser)
            {
                echo '<td>'.$matchedUser->name.'</td>';
            }
            else
            {
                echo '<td>Unknown</td>';
            }

            echo '<td>'.$row[2].'</td>';

            echo '<td>'.$row[4].'</td>';

            echo "</tr>";

            if ($matchedUser)
            {
                Payment::create([
                    'created_at' => $date,
                    'reason' => $reasonString,
                    'source' => 'standing-order',
                    'user_id' => $matchedUser->id,
                    'amount' => $row[4],
                    'fee' => 0,
                    'amount_minus_fee' => $row[4],
                    'status' => 'paid'
                ]);
                if ($subPayment)
                {
                    $matchedUser->extendMembership('standing-order', $date->addMonth());
                    $matchedUser->monthly_subscription = $row[4];
                    $matchedUser->save();
                }
            }
        }
        echo "</table>";
        exit;
	}





}
