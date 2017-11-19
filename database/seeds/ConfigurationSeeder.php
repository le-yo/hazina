<?php

use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('configurations')->truncate();

        DB::table('configurations')->delete();

        DB::table('configurations')->insert(array(
            array(
                'slug' => 'min_loanee_age',
                'value' => 18,
            ),
            array(
                'slug' => 'max_loanee_age',
                'value' => 61,
            ),
            array(
                'slug' => 'age_error_message',
                'value' => 'You must be over {min} years and under {max} years old.',
            ),
            array(
                'slug' => 'date_str_length',
                'value' => 8,
            ),
            array(
                'slug' => 'invalid_date_str_length',
                'value' => "Invalid Date. Date must be of the format DDMMYYYY and must contain 8 digits",
            ),
        ));
    }
}
