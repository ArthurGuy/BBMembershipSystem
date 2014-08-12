<?php 

class AccountImportController extends \BaseController {

    public function fetch()
    {

        //Where are we going to store the local file
        $spreadsheetPath = storage_path("cache/Subscribers.ods");

        //If we have a local file check the date and see if it needs replacing
        if (\File::exists($spreadsheetPath))
        {
            $lastModified = \Carbon\Carbon::createFromTimeStamp(\File::lastModified($spreadsheetPath));
            if ($lastModified->lt(\Carbon\Carbon::now()->subDay()))
            {
                //If its over a day old remove the cached file
                \File::delete($spreadsheetPath);
            }
        }

        //If we don't have a local file fetch it from dropbox
        if (!\File::exists($spreadsheetPath))
        {
            $f = fopen($spreadsheetPath, "w+b");
            $fileMetadata = Dropbox::getFile("/Build Brighton/Accounts/Subscriber Payments.ods", $f);
            fclose($f);
        }

        //Load a parser for the spreadsheet
        $reader = new SpreadsheetReader($spreadsheetPath);

        //There are 3 sheets, I think we just needs sheet 1
        //dd($reader->Sheets());
        $reader->ChangeSheet(0);

        $memberArray = array();

        //Get the spreadsheet headings
        $header = $reader->current();
        //print_r($header);

        //Wanted headings
        $wantedHeader = array(
            'Name',
            'Full Name',
            'Email',
            'Phone',
            'Twitter',
            'Trusted?',
            'Keys',
            'Notes',
            'Home Address',
            'Emergency Contact Name',
            'Emergency Contact Number'
        );
        $boolData = [
            'Keys',
            'Trusted?'
        ];

        //Process each member row
        foreach ($reader as $i=>$row)
        {
            //print_r($row);
            //echo PHP_EOL;

            $memberArray[$i] = array();
            foreach ($row as $n => $col)
            {

                if ($header[$n] == 'Leaving Date')
                {
                    if (empty($col))
                    {
                        $memberArray[$i]['status'] = 'active';
                    }
                    elseif (\Carbon\Carbon::createFromFormat('d/m/Y', $col)->lt(\Carbon\Carbon::now()))
                    {
                        $memberArray[$i]['status'] = 'expired';
                    }
                    else
                    {
                        $memberArray[$i]['status'] = 'leaving';
                    }
                }
                //Convert boolean data to 1 or 0
                if (in_array($header[$n], $boolData))
                {
                    if (strtolower($col) == 'y')
                    {
                        $col = true;
                    }
                    elseif (strtolower($col) == 'n')
                    {
                        $col = false;
                    }
                }
                //if (in_array($header[$n], $wantedHeader))
                {
                    $memberArray[$i][$header[$n]] = $col;
                }
            }
        }
        print_r($memberArray);

        foreach ($memberArray as $member)
        {
            if (empty($member['Email']))
                continue;

            if (empty($member['Full Name']))
                $member['Full Name'] = $member['Name'];

            $givenName = '';
            $familyName = '';
            $nameParts = explode(' ', $member['Full Name']);
            if (count($nameParts) == 1)
            {
                $familyName = $nameParts[0];
            }
            elseif (count($nameParts) > 1)
            {
                $familyName = $nameParts[count($nameParts)-1];
                unset($nameParts[count($nameParts)-1]);
                $givenName = implode(' ', $nameParts);
            }


            $addressParts = array_map('trim', explode(',', $member['Home Address']));

            $address_1 = $address_2 = $address_3 = $address_4 = $postcode = '';

            if (count($addressParts) > 0)
            {
                if (count($addressParts) > 1)
                    $postcode = $addressParts[count($addressParts)-1];
                if (isset($addressParts[0]) && (count($addressParts)-1 != 0))
                    $address_1 = $addressParts[0];
                if (isset($addressParts[1]) && (count($addressParts)-1 != 1))
                    $address_2 = $addressParts[1];
                if (isset($addressParts[2]) && (count($addressParts)-1 != 2))
                    $address_3 = $addressParts[2];
                if (isset($addressParts[3]) && (count($addressParts)-1 != 3))
                    $address_4 = $addressParts[3];
            }

            $emergencyContact = '';
            if (!empty($member['Emergency Contact Name']))
                $emergencyContact = $member['Emergency Contact Name'];
            if (!empty($member['Emergency Contact Number']))
                $emergencyContact .= ' '.$member['Emergency Contact Number'];

            $paymentMethod = 'other';
            if (strtolower($member['Method']) == 'paypal')
                $paymentMethod = 'paypal';
            if (strtolower($member['Method']) == 'standing order')
                $paymentMethod = 'standing-order';

            $status = '';
            $active = true;
            $founder = false;
            $banned = false;
            $joinDate = '';


            //This contains mostly useless information
            switch (strtolower(trim($member['Membership Type'])))
            {
                case 'ex': $status = 'left'; break;
                case 'full': $status = 'active'; break;
                case 'concession': $status = 'active'; break;
                case 'mini-member': $status = 'active'; break;
                case 'skiff mate': $status = 'active'; break;
                case 'director': $status = 'active'; break;
                case 'honorary': $status = 'active'; break;
            }

            //These situations override the ones above
            switch (strtolower(trim($member['Visit Frequency'])))
            {
                case 'cancelled': $status = 'left'; break;
                case 'terminated': $status = 'left'; $banned = true; break;
            }

            //Take a look at the join date to see if they are a founder
            if (strtolower($member['Join Date']) == 'founder')
            {
                $founder = true;
                $joinDate = \Carbon\Carbon::createFromFormat('j/n/y', '1/7/09');
            }
            else
            {
                $joinDate = \Carbon\Carbon::createFromFormat('j/n/y', $member['Join Date']);
            }

            //This is my status info from above
            if (empty($status) && ($member['status'] == 'active'))
                $status = 'active';

            //If the user has left they arent active
            if ($status == 'left')
                $active = false;

            $bannedDate = '0000-00-00';
            if ($banned)
            {
                //There are only 3 banned users with the same date
                $bannedDate = \Carbon\Carbon::createFromFormat('j/n/y', '15/2/12');
            }

            $paymentDay = preg_replace("/[^0-9]/", "", $member['Normal Pay Date']);
            if (empty($paymentDay))
                $paymentDay = 1;
            if ($paymentDay > 28)
                $paymentDay = 28;

            $user = User::where('email', trim($member['Email']))->first();
            if (!$user)
            {
                $user = User::create([
                    'given_name'        => $givenName,
                    'family_name'       => $familyName,
                    'email'             => trim($member['Email']),
                    'address_line_1'    => $address_1,
                    'address_line_2'    => $address_2,
                    'address_line_3'    => $address_3,
                    'address_line_4'    => $address_4,
                    'address_postcode'  => $postcode,
                    'emergency_contact' => trim($emergencyContact),
                    'notes'             => $member['Notes'],
                    'active'            => $active,
                    'status'            => $status,
                    'trusted'           => $member['Trusted?'],
                    'key_holder'        => $member['Keys'],
                    'payment_method'    => $paymentMethod,
                    'payment_day'       => $paymentDay,
                    'monthly_subscription'  => round(preg_replace("/[^0-9.,]/", "", $member['Normal Payment']), 0, PHP_ROUND_HALF_UP),
                    'created_at'        => $joinDate,
                    'founder'           => $founder,
                    'banned_date'       => $bannedDate,
                    'induction_complete'=> true
                ]);
            }

            for ($y=2009; $y <= 2014; $y++)
            {
                for ($m=1; $m <= 12; $m++)
                {
                    if (($y == 2012) && ($m == 9))
                    {
                        $monthYearString = $m.'/'.$y;
                    }
                    else
                    {
                        $monthYearString = sprintf('%02d', $m).'/'.$y;
                    }
                    if (isset($member[$monthYearString]) && !empty($member[$monthYearString]))
                    {
                        $amount = trim(preg_replace("/[^0-9.,]/", "", $member[$monthYearString]));
                        if (!empty($amount))
                        {
                            $paymentDate = \Carbon\Carbon::createFromFormat('j/m/Y', $paymentDay.'/'.$m.'/'.$y);
                            $payment = new Payment([
                                'reason' => 'subscription',
                                'source' => 'other',
                                'amount' => $amount,
                                'amount_minus_fee' => $amount,
                                'status' => 'paid',
                                'created_at' => $paymentDate
                            ]);
                            $user->payments()->save($payment);
                        }
                    }
                }
            }

            //Update the users subscription end date based on the last recorded payment
            $recentPayment = $user->payments()->subscription()->first();
            $user->subscription_expires = $recentPayment->created_at->addMonth();
            $user->save();
        }
    }
} 