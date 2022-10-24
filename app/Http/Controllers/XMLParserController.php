<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Payment;
use Illuminate\Http\Request;
use SbWereWolf\XmlNavigator\NavigatorFabric;
use SimpleXMLElement;

class XMLParserController extends Controller
{
    public function parse() {
        $files = glob(storage_path('app/public/documents/*.{xml}'), GLOB_BRACE);
        foreach($files as $file) {
            echo $file . '<br>';
            $xml = simplexml_load_file($file) or die('can\'t load xml');
            $array = [];
            $payments = [];
            foreach ($xml as $record)
            {
                foreach($record->attributes() as $a => $b) {
                    $array += [
                        $a => (string) $b,
                    ];
                    //echo $a,'="',$b,"\"\n";
                }
            }

            foreach($xml->Payments->PaymentSchedule as $a) {
                foreach ($a->P1 as $b) {
                    array_push($payments, [$b->attributes()->getName() => (int)$b->attributes()->PaymentNumber]);
                }
            }

            foreach ($payments as $payment => $val) {
                foreach($xml->Payments->PaymentSchedule->P1 as $a) {
                    foreach ($a as $b) {
                        if ($val['PaymentNumber'] === (int)$a->attributes()) {
                            $payments[$payment] += [
                                $b->attributes()->getName() => (string) $b->attributes(),
                            ];
                        }
                    }
                }
            }
            $dateArray = [];
            $pattern = '/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/';
            preg_match_all($pattern, $array['Validity'], $dateArray);
            //dd($dateArray);
            //dd(date($array['Validity']));
            if (!empty($array)) {
                Agreement::create([
                    'leasing_subject' => $array['LeasingSubject'],
                    'contract_cost' => $array['ContractCost'],
                    'payment_amount' => $array['PaymentAmount'],
                    'total_amount' => $array['TotalAmount'],
                    'validity_start' => $dateArray[0][0],
                    'validity_end' => $dateArray[0][1],
                ]);
            }
            if (!empty($payments)) {
                foreach ($payments as $payment) {
                    Payment::create([
                        'agreement_id' => Agreement::where('leasing_subject', '=', $array['LeasingSubject'])->value('id'),
                        'payment_number' => $payment['PaymentNumber'],
                        'settlement_month' => $payment['SettlementMonth'],
                        'redemption_payment' => $payment['RedemptionPayment'],
                        'advance_payment_amount' => $payment['AdvancePaymentAmount'],
                        'total_amount' => $payment['TotalAmount'],
                    ]);
                }
            }
            echo 'done';
        }
    }
}
