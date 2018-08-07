<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class MenusTableSeeder extends Seeder
{
    public function run()
    {
        //menu types type 0 - authentication mini app, Type 1 - another menu mini app, type 2 leads to a process app, 3 gives information directly
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('menus')->truncate();

        DB::table('menus')->delete();

        DB::table('menus')->insert(array(
            array(
                'title' => 'Welcome to Uni Credit',
                'is_parent' => 1,
                'type' => 2,
                'confirmation_message' => "",
            ),
            array(
                'title' => 'Main Menu',
                'is_parent' => 0,
                'type' => 1,
                'confirmation_message' => "",
            ),
            array(
                'title' => 'Apply for Loan',
                'is_parent' => 0,
                'type' => 2,
                'confirmation_message' => "Your Salary Advance Loan request has been received, you will receive an SMS response. For further assistance please call our customer care line: 020 403 8000.",
            ),
            array(
                'title' => 'Loan balance',
                'is_parent' => 0,
                'type' => 3,
                'confirmation_message' => "Your outstanding balance is Ksh. 0",
            ),
            array(
                'title' => 'Repay Loan',
                'is_parent' => 0,
                'type' => 3,
                'confirmation_message' => "To repay your Salary Advance Loan: 1) Go to M-pesa; 2)Select Lipa na M-pesa; 3) Select Paybill; 4)Enter Business No. 963334; 5)Account No. {phone} 6)Enter Amount; 7) Enter you M-Pesa Pin.",
            ),
            array(
                'title' => 'Extend Loan:',
                'is_parent' => 0,
                'type' => 3,
                'confirmation_message' => "Sample Statement:".PHP_EOL."1/9/2015 Loan approved - Ksh 10,000".PHP_EOL."2/9/2015 Loan repaid - Ksh. 3000".PHP_EOL."3/9/2015 Loan balance inquiry.",
            ),
            array(
                'title' => 'Mini Statement:',
                'is_parent' => 0,
                'type' => 3,
                'confirmation_message' => "Sample Statement:".PHP_EOL."1/9/2015 Loan approved - Ksh 10,000".PHP_EOL."2/9/2015 Loan repaid - Ksh. 3000".PHP_EOL."3/9/2015 Loan balance inquiry.",
            ),
            array(
                'title' => 'Terms & Conditions:',
                'is_parent' => 0,
                'type' => 3,
                'confirmation_message' => "Visit http://www.unilimited.co.ke",
            ),
            //menu 9
            array(
                'title' => 'Register:',
                'is_parent' => 0,
                'type' => 2,
                'confirmation_message' => "Thank you for your interest UNI Ltd Salary Advance product, your registration request has been received and will be processed shortly. For further assistance please call our customer care line: 020 403 8000",
            ),

        ));
    }
}
