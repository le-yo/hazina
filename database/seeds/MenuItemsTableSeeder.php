<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class MenuItemsTableSeeder extends Seeder
{
    public function run()
    {
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('menu_items')->truncate();

        DB::table('menu_items')->delete();

        DB::table('menu_items')->insert(array(
            array(
                'menu_id' => 1,
                'description' => 'Enter PIN ((Forgot PIN? Answer with 0)',
                'next_menu_id' => 2,
                'step' => 0,
                'confirmation_phrase' => '',
            ),
            array(
                'menu_id' => 2,
                'description' => 'Request Loan',
                'next_menu_id' => 3,
                'step' => 0,
                'confirmation_phrase' => '',
            ),
            array(
                'menu_id' => 2,
                'description' => 'Loan Balance',
                'next_menu_id' => 4,
                'step' => 0,
                'confirmation_phrase' => '',
            ),
            array(
                'menu_id' => 2,
                'description' => 'Repay Loan',
                'next_menu_id' => 5,
                'step' => 0,
                'confirmation_phrase' => '',
            ),
            array(
                'menu_id' => 0,
                'description' => 'Extend Loan',
                'next_menu_id' => 6,
                'step' => 0,
                'confirmation_phrase' => '',
            ),
//            array(
//                'menu_id' => 2,
//                'description' => 'Mini Statements',
//                'next_menu_id' => 7,
//                'step' => 0,
//                'confirmation_phrase' => '',
//            ),
            array(
                'menu_id' => 2,
                'description' => 'Terms & Conditions',
                'next_menu_id' => 8,
                'step' => 0,
                'confirmation_phrase' => '',
            ),
            array(
                'menu_id' => 3,
                'description' => 'Enter loan Amount',
                'next_menu_id' => 0,
                'step' => 1,
                'confirmation_phrase' => 'Amount',
            ),
            array(
                'menu_id' => 9,
                'description' => 'Welcome to Uni Limited.'.PHP_EOL.'By proceeding you agree to the terms and conditions on www.unilimited.co.ke'.PHP_EOL.'1. I Agree'.PHP_EOL.'2. I Disagree',
                'next_menu_id' => 0,
                'step' => 1,
                'confirmation_phrase' => 'IGNORE',
            ),
            array(
                'menu_id' => 9,
                'description' => 'Welcome to Uni Limited.'.PHP_EOL.'By proceeding you agree to the terms and conditions on www.unilimited.co.ke'.PHP_EOL.'1. I Agree'.PHP_EOL.'2. I Disagree',
                'next_menu_id' => 0,
                'step' => 1,
                'confirmation_phrase' => 'IGNORE',
            ),
            array(
                'menu_id' => 9,
                'description' => 'Enter Full Name as per national ID',
                'next_menu_id' => 0,
                'step' => 2,
                'confirmation_phrase' => 'Name',
            ),
            array(
                'menu_id' => 9,
                'description' => 'Enter national ID Number',
                'next_menu_id' => 0,
                'step' => 3,
                'confirmation_phrase' => 'ID',
            ),
            array(
                'menu_id' => 9,
                'description' => 'Select Gender:'.PHP_EOL."1. M".PHP_EOL."2. F",
                'next_menu_id' => 0,
                'step' => 4,
                'confirmation_phrase' => 'IGNORE',
            ),
            array(
                'menu_id' => 9,
                'description' => 'Enter date of birth (DDMMYYYY)',
                'next_menu_id' => 0,
                'step' => 5,
                'confirmation_phrase' => 'IGNORE',
            ),
            array(
                'menu_id' => 9,
                'description' => 'Enter employer registered name',
                'next_menu_id' => 0,
                'step' => 6,
                'confirmation_phrase' => 'Employer',
            ),
            array(
                'menu_id' => 9,
                'description' => 'Enter Net Salary(Kshs.)',
                'next_menu_id' => 0,
                'step' => 7,
                'confirmation_phrase' => 'Salary',
            ),
        ));
    }
}
