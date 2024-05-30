<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Afghanistan', 'iso_code' => 'AF'],
            ['name' => 'Åland Islands', 'iso_code' => 'AX'],
            ['name' => 'Albania', 'iso_code' => 'AL'],
            ['name' => 'Algeria', 'iso_code' => 'DZ'],
            ['name' => 'American Samoa', 'iso_code' => 'AS'],
            ['name' => 'Andorra', 'iso_code' => 'AD'],
            ['name' => 'Angola', 'iso_code' => 'AO'],
            ['name' => 'Anguilla', 'iso_code' => 'AI'],
            ['name' => 'Antarctica', 'iso_code' => 'AQ'],
            ['name' => 'Antigua and Barbuda', 'iso_code' => 'AG'],
            ['name' => 'Argentina', 'iso_code' => 'AR'],
            ['name' => 'Armenia', 'iso_code' => 'AM'],
            ['name' => 'Aruba', 'iso_code' => 'AW'],
            ['name' => 'Australia', 'iso_code' => 'AU'],
            ['name' => 'Austria', 'iso_code' => 'AT'],
            ['name' => 'Azerbaijan', 'iso_code' => 'AZ'],
            ['name' => 'Bahamas', 'iso_code' => 'BS'],
            ['name' => 'Bahrain', 'iso_code' => 'BH'],
            ['name' => 'Bangladesh', 'iso_code' => 'BD'],
            ['name' => 'Barbados', 'iso_code' => 'BB'],
            ['name' => 'Belarus', 'iso_code' => 'BY'],
            ['name' => 'Belgium', 'iso_code' => 'BE'],
            ['name' => 'Belize', 'iso_code' => 'BZ'],
            ['name' => 'Benin', 'iso_code' => 'BJ'],
            ['name' => 'Bermuda', 'iso_code' => 'BM'],
            ['name' => 'Bhutan', 'iso_code' => 'BT'],
            ['name' => 'Bolivia', 'iso_code' => 'BO'],
            ['name' => 'Bosnia and Herzegovina', 'iso_code' => 'BA'],
            ['name' => 'Spain', 'iso_code' => 'ES']
        ];

        Country::insert($countries);
    }
}
