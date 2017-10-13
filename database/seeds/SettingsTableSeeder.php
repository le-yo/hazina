<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {

        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('settings')->truncate();

        DB::table('settings')->delete();


        $products = self::getProductList();
        DB::table('settings')->insert($products);


    }

    public function getProductList(){
        $clients_url = MIFOS_URL."/loanproducts?".MIFOS_tenantIdentifier;
        //$clients_url = 'https://intrasoft.openmf.org/mifosng-provider/api/v1/clients?tenantIdentifier=intrasoft';
        $ch = curl_init();
        $data = "";
        curl_setopt($ch, CURLOPT_URL, $clients_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, MIFOS_UN.':'.MIFOS_PASS);

//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        if ($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
        curl_close($ch);
        $loanproducts = json_decode($data);

        $data = array();
        foreach($loanproducts as $loanproduct){
            print_r($loanproduct);
//            exit;
            $dat = array();
            $dat['product_name'] = $loanproduct->name;
            $dat['productId'] = $loanproduct->id;
            $dat['loanTermFrequency'] = $loanproduct->repaymentFrequencyType->id;
            $dat['loanTermFrequencyType'] = $loanproduct->repaymentFrequencyType->id;
            $dat['loanType'] = "individual";
            if(!empty($loanproduct->minNumberOfRepayments)) {
                $dat['numberOfRepayments'] = $loanproduct->minNumberOfRepayments;
                $dat['minNumberOfRepayments'] =$loanproduct->minNumberOfRepayments;
            }else{
                $dat['numberOfRepayments'] = 1;
                $dat['minNumberOfRepayments'] =1;

            }
            if(!empty($loanproduct->maxNumberOfRepayments)) {
                $dat['maxNumberOfRepayments'] = $loanproduct->maxNumberOfRepayments;
            }else{
                $dat['maxNumberOfRepayments'] = 1;

            }
            $dat['repaymentEvery'] =$loanproduct->repaymentEvery;
            $dat['repaymentFrequencyType'] =$loanproduct->repaymentFrequencyType->id;
            $dat['interestRatePerPeriod'] =$loanproduct->interestRatePerPeriod;
            $dat['amortizationType'] =$loanproduct->amortizationType->id;
            $dat['interestType'] =$loanproduct->interestType->id;
            $dat['interestCalculationPeriodType'] =$loanproduct->interestCalculationPeriodType->id;
            $dat['transactionProcessingStrategyId'] =$loanproduct->transactionProcessingStrategyId;
            $dat['maxOutstandingLoanBalance'] =$loanproduct->transactionProcessingStrategyId;
            $dat['is_active'] =1;

            array_push($data,$dat);

        }

        return $data;


    }

}
