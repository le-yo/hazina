<?php

use Illuminate\Database\Seeder;

class RemindersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        //menu types type 0 - authentication mini app, Type 1 - another menu mini app, type 2 leads to a process app, 3 gives information directly
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('reminders')->truncate();

        DB::table('reminders')->delete();

        DB::table('reminders')->insert(array(
            array(
                'slug' => '5_days_to',
                'message' => "Dear {name}, kind reminder that your Salary Advance Loan of Kshs. {balance} is due for repayment on {due_date}. To repay your Salary Advance Loan: 1) Go to M-pesa; 2)Select Lipa na M-pesa; 3) Select Paybill; 4)Enter Business No. 963334; 5)Account No. {phone}; 6)Enter Amount; 7) Enter you M-Pesa Pin. For further assistance please call our customer care line: 0704 000 999",
                'type' => 1,
                'days_to' => 5,
                'days_overdue' => null,
            ),
            array(
                'slug' => '1_days_to',
                'message' => "Dear {name}, kindly remember to honour your Salary Advance Loan repayment of Kshs. {balance} , Due tomorrow {due_date}. For further assistance please  call our customer care line 0704 000 999",
                'type' => 2,
                'days_to' => 1,
                'days_overdue' => null,
            ),
            array(
                'slug' => '0_days_to',
                'message' => "Dear {name}, kindly repay your Salary Advance Loan of Kshs. {balance} , Today {due_date}.  To repay your Salary Advance Loan: 1) Go to M-pesa; 2)Select Lipa na M-pesa; 3) Select Paybill; 4)Enter Business No. 963334; 5)Account No. {phone}; 6)Enter Amount; 7) Enter you M-pesa Pin. For further assistance please call our customer care line 0704 000 999",
                'type' => 3,
                'days_to' => 0,
                'days_overdue' => null,
            ),
            array(
                'slug' => '1_days_overdue',
                'message' => "Dear {name}, your Salary Advance Loan is overdue from {due_date}. Late Payment penalty is accruing at 1.75% per week. Please repay your total balance of Kshs. {balance} immediately to avoid penalty accruing. For further assistance please call our customer care line 0704 000 999",
                'type' => 4,
                'days_to' => null,
                'days_overdue' => 1,
            ),
            array(
                'slug' => '7_days_overdue',
                'message' => "Dear {name}, your Salary Advance Loan is overdue from {due_date}. Late Payment penalty is accruing at 1.75% per week. Your outstanding balance is Kshs. {balance} and your late payment penalty is Kshs. {balance} . Please repay your total balance of Kshs. {balance} immediately to avoid further penalty accruing. For further assistance please call our customer care line 0704 000 999",
                'type' => 5,
                'days_to' => null,
                'days_overdue' => 7,
            ),
            array(
                'slug' => '8_days_overdue',
                'message' => "Dear {name}, your Salary Advance Loan is overdue from {due_date}. If we do not receive your repayment of Kshs {balance} by close of Business Today, we are obligated to forward the overdue Salary Advance Loan amount to your company  for direct deduction from your next salary. Late Payment penalty is accruing at 1.75% per week. To avoid this, please repay your total outstanding balance of Kshs {balance} immediately to avoid further penalties and listing with a credit reference bureau. For further assistance please call our customer care line 0704 000 999",
                'type' => 6,
                'days_to' => null,
                'days_overdue' => 8,
            ),
            array(
                'slug' => '15_days_overdue',
                'message' => "Dear {name}, your Salary Advance Loan is overdue from {due_date}. We have shared details of your overdue Salary Advance Loan with your company for direct deduction from your salary. Late Payment penalty is accruing at 1.75% per week. Please repay your total balance of Kshs. {balance} immediately to avoid further penalty accruing and registration with all the credit reference bureaus. For further assistance please call our customer care line 0704 000 999",
                'type' => 7,
                'days_to' => null,
                'days_overdue' => 15,
            ),
        ));
    }
}
