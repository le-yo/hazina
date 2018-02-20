<?php

use Illuminate\Database\Seeder;

class ConfirmationPhraseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $menu =  \App\menu::find(3);
        $menu->confirmation_title = "Loan";
        $menu->save();

    }
}
