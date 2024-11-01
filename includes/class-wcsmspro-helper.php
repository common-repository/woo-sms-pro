<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_HELPER
{
    /**
     * @param mixed $phone_number 03131231231
     * @param string $iso_code GB
     * @return int|mixed $phone_number
     */
    public static function get_valid_number($phone_number, $iso_code)
    {
        $countries = array(
            'AF' => array(
                'name' => 'Afghanistan',
                'dial_code' => '+93',
            ),
            'AL' => array(
                'name' => 'Albania',
                'dial_code' => '+355',
            ),
            'DZ' => array(
                'name' => 'Algeria',
                'dial_code' => '+213',
            ),
            'AS' => array(
                'name' => 'American Samoa',
                'dial_code' => '+1',
            ),
            'AD' => array(
                'name' => 'Andorra',
                'dial_code' => '+376',
            ),
            'AO' => array(
                'name' => 'Angola',
                'dial_code' => '+244',
            ),
            'AI' => array(
                'name' => 'Anguilla',
                'dial_code' => '+1',
            ),
            'AG' => array(
                'name' => 'Antigua',
                'dial_code' => '+1',
            ),
            'AR' => array(
                'name' => 'Argentina',
                'dial_code' => '+54',
            ),
            'AM' => array(
                'name' => 'Armenia',
                'dial_code' => '+374',
            ),
            'AW' => array(
                'name' => 'Aruba',
                'dial_code' => '+297',
            ),
            'AU' => array(
                'name' => 'Australia',
                'dial_code' => '+61',
            ),
            'AT' => array(
                'name' => 'Austria',
                'dial_code' => '+43',
            ),
            'AZ' => array(
                'name' => 'Azerbaijan',
                'dial_code' => '+994',
            ),
            'BH' => array(
                'name' => 'Bahrain',
                'dial_code' => '+973',
            ),
            'BD' => array(
                'name' => 'Bangladesh',
                'dial_code' => '+880',
            ),
            'BB' => array(
                'name' => 'Barbados',
                'dial_code' => '+1',
            ),
            'BY' => array(
                'name' => 'Belarus',
                'dial_code' => '+375',
            ),
            'BE' => array(
                'name' => 'Belgium',
                'dial_code' => '+32',
            ),
            'BZ' => array(
                'name' => 'Belize',
                'dial_code' => '+501',
            ),
            'BJ' => array(
                'name' => 'Benin',
                'dial_code' => '+229',
            ),
            'BM' => array(
                'name' => 'Bermuda',
                'dial_code' => '+1',
            ),
            'BT' => array(
                'name' => 'Bhutan',
                'dial_code' => '+975',
            ),
            'BO' => array(
                'name' => 'Bolivia',
                'dial_code' => '+591',
            ),
            'BA' => array(
                'name' => 'Bosnia and Herzegovina',
                'dial_code' => '+387',
            ),
            'BW' => array(
                'name' => 'Botswana',
                'dial_code' => '+267',
            ),
            'BR' => array(
                'name' => 'Brazil',
                'dial_code' => '+55',
            ),
            'IO' => array(
                'name' => 'British Indian Ocean Territory',
                'dial_code' => '+246',
            ),
            'VG' => array(
                'name' => 'British Virgin Islands',
                'dial_code' => '+1',
            ),
            'BN' => array(
                'name' => 'Brunei',
                'dial_code' => '+673',
            ),
            'BG' => array(
                'name' => 'Bulgaria',
                'dial_code' => '+359',
            ),
            'BF' => array(
                'name' => 'Burkina Faso',
                'dial_code' => '+226',
            ),
            'MM' => array(
                'name' => 'Burma Myanmar',
                'dial_code' => '+95',
            ),
            'BI' => array(
                'name' => 'Burundi',
                'dial_code' => '+257',
            ),
            'KH' => array(
                'name' => 'Cambodia',
                'dial_code' => '+855',
            ),
            'CM' => array(
                'name' => 'Cameroon',
                'dial_code' => '+237',
            ),
            'CA' => array(
                'name' => 'Canada',
                'dial_code' => '+1',
            ),
            'CV' => array(
                'name' => 'Cape Verde',
                'dial_code' => '+238',
            ),
            'KY' => array(
                'name' => 'Cayman Islands',
                'dial_code' => '+1',
            ),
            'CF' => array(
                'name' => 'Central African Republic',
                'dial_code' => '+236',
            ),
            'TD' => array(
                'name' => 'Chad',
                'dial_code' => '+235',
            ),
            'CL' => array(
                'name' => 'Chile',
                'dial_code' => '+56',
            ),
            'CN' => array(
                'name' => 'China',
                'dial_code' => '+86',
            ),
            'CO' => array(
                'name' => 'Colombia',
                'dial_code' => '+57',
            ),
            'KM' => array(
                'name' => 'Comoros',
                'dial_code' => '+269',
            ),
            'CK' => array(
                'name' => 'Cook Islands',
                'dial_code' => '+682',
            ),
            'CR' => array(
                'name' => 'Costa Rica',
                'dial_code' => '+506',
            ),
            'CI' => array(
                'name' => 'Côte d\'Ivoire',
                'dial_code' => '+225',
            ),
            'HR' => array(
                'name' => 'Croatia',
                'dial_code' => '+385',
            ),
            'CU' => array(
                'name' => 'Cuba',
                'dial_code' => '+53',
            ),
            'CY' => array(
                'name' => 'Cyprus',
                'dial_code' => '+357',
            ),
            'CZ' => array(
                'name' => 'Czech Republic',
                'dial_code' => '+420',
            ),
            'CD' => array(
                'name' => 'Democratic Republic of Congo',
                'dial_code' => '+243',
            ),
            'DK' => array(
                'name' => 'Denmark',
                'dial_code' => '+45',
            ),
            'DJ' => array(
                'name' => 'Djibouti',
                'dial_code' => '+253',
            ),
            'DM' => array(
                'name' => 'Dominica',
                'dial_code' => '+1',
            ),
            'DO' => array(
                'name' => 'Dominican Republic',
                'dial_code' => '+1',
            ),
            'EC' => array(
                'name' => 'Ecuador',
                'dial_code' => '+593',
            ),
            'EG' => array(
                'name' => 'Egypt',
                'dial_code' => '+20',
            ),
            'SV' => array(
                'name' => 'El Salvador',
                'dial_code' => '+503',
            ),
            'GQ' => array(
                'name' => 'Equatorial Guinea',
                'dial_code' => '+240',
            ),
            'ER' => array(
                'name' => 'Eritrea',
                'dial_code' => '+291',
            ),
            'EE' => array(
                'name' => 'Estonia',
                'dial_code' => '+372',
            ),
            'ET' => array(
                'name' => 'Ethiopia',
                'dial_code' => '+251',
            ),
            'FK' => array(
                'name' => 'Falkland Islands',
                'dial_code' => '+500',
            ),
            'FO' => array(
                'name' => 'Faroe Islands',
                'dial_code' => '+298',
            ),
            'FM' => array(
                'name' => 'Federated States of Micronesia',
                'dial_code' => '+691',
            ),
            'FJ' => array(
                'name' => 'Fiji',
                'dial_code' => '+679',
            ),
            'FI' => array(
                'name' => 'Finland',
                'dial_code' => '+358',
            ),
            'FR' => array(
                'name' => 'France',
                'dial_code' => '+33',
            ),
            'GF' => array(
                'name' => 'French Guiana',
                'dial_code' => '+594',
            ),
            'PF' => array(
                'name' => 'French Polynesia',
                'dial_code' => '+689',
            ),
            'GA' => array(
                'name' => 'Gabon',
                'dial_code' => '+241',
            ),
            'GE' => array(
                'name' => 'Georgia',
                'dial_code' => '+995',
            ),
            'DE' => array(
                'name' => 'Germany',
                'dial_code' => '+49',
            ),
            'GH' => array(
                'name' => 'Ghana',
                'dial_code' => '+233',
            ),
            'GI' => array(
                'name' => 'Gibraltar',
                'dial_code' => '+350',
            ),
            'GR' => array(
                'name' => 'Greece',
                'dial_code' => '+30',
            ),
            'GL' => array(
                'name' => 'Greenland',
                'dial_code' => '+299',
            ),
            'GD' => array(
                'name' => 'Grenada',
                'dial_code' => '+1',
            ),
            'GP' => array(
                'name' => 'Guadeloupe',
                'dial_code' => '+590',
            ),
            'GU' => array(
                'name' => 'Guam',
                'dial_code' => '+1',
            ),
            'GT' => array(
                'name' => 'Guatemala',
                'dial_code' => '+502',
            ),
            'GN' => array(
                'name' => 'Guinea',
                'dial_code' => '+224',
            ),
            'GW' => array(
                'name' => 'Guinea-Bissau',
                'dial_code' => '+245',
            ),
            'GY' => array(
                'name' => 'Guyana',
                'dial_code' => '+592',
            ),
            'HT' => array(
                'name' => 'Haiti',
                'dial_code' => '+509',
            ),
            'HN' => array(
                'name' => 'Honduras',
                'dial_code' => '+504',
            ),
            'HK' => array(
                'name' => 'Hong Kong',
                'dial_code' => '+852',
            ),
            'HU' => array(
                'name' => 'Hungary',
                'dial_code' => '+36',
            ),
            'IS' => array(
                'name' => 'Iceland',
                'dial_code' => '+354',
            ),
            'IN' => array(
                'name' => 'India',
                'dial_code' => '+91',
            ),
            'ID' => array(
                'name' => 'Indonesia',
                'dial_code' => '+62',
            ),
            'IR' => array(
                'name' => 'Iran',
                'dial_code' => '+98',
            ),
            'IQ' => array(
                'name' => 'Iraq',
                'dial_code' => '+964',
            ),
            'IE' => array(
                'name' => 'Ireland',
                'dial_code' => '+353',
            ),
            'IL' => array(
                'name' => 'Israel',
                'dial_code' => '+972',
            ),
            'IT' => array(
                'name' => 'Italy',
                'dial_code' => '+39',
            ),
            'JM' => array(
                'name' => 'Jamaica',
                'dial_code' => '+1',
            ),
            'JP' => array(
                'name' => 'Japan',
                'dial_code' => '+81',
            ),
            'JO' => array(
                'name' => 'Jordan',
                'dial_code' => '+962',
            ),
            'KZ' => array(
                'name' => 'Kazakhstan',
                'dial_code' => '+7',
            ),
            'KE' => array(
                'name' => 'Kenya',
                'dial_code' => '+254',
            ),
            'KI' => array(
                'name' => 'Kiribati',
                'dial_code' => '+686',
            ),
            'XK' => array(
                'name' => 'Kosovo',
                'dial_code' => '+381',
            ),
            'KW' => array(
                'name' => 'Kuwait',
                'dial_code' => '+965',
            ),
            'KG' => array(
                'name' => 'Kyrgyzstan',
                'dial_code' => '+996',
            ),
            'LA' => array(
                'name' => 'Laos',
                'dial_code' => '+856',
            ),
            'LV' => array(
                'name' => 'Latvia',
                'dial_code' => '+371',
            ),
            'LB' => array(
                'name' => 'Lebanon',
                'dial_code' => '+961',
            ),
            'LS' => array(
                'name' => 'Lesotho',
                'dial_code' => '+266',
            ),
            'LR' => array(
                'name' => 'Liberia',
                'dial_code' => '+231',
            ),
            'LY' => array(
                'name' => 'Libya',
                'dial_code' => '+218',
            ),
            'LI' => array(
                'name' => 'Liechtenstein',
                'dial_code' => '+423',
            ),
            'LT' => array(
                'name' => 'Lithuania',
                'dial_code' => '+370',
            ),
            'LU' => array(
                'name' => 'Luxembourg',
                'dial_code' => '+352',
            ),
            'MO' => array(
                'name' => 'Macau',
                'dial_code' => '+853',
            ),
            'MK' => array(
                'name' => 'Macedonia',
                'dial_code' => '+389',
            ),
            'MG' => array(
                'name' => 'Madagascar',
                'dial_code' => '+261',
            ),
            'MW' => array(
                'name' => 'Malawi',
                'dial_code' => '+265',
            ),
            'MY' => array(
                'name' => 'Malaysia',
                'dial_code' => '+60',
            ),
            'MV' => array(
                'name' => 'Maldives',
                'dial_code' => '+960',
            ),
            'ML' => array(
                'name' => 'Mali',
                'dial_code' => '+223',
            ),
            'MT' => array(
                'name' => 'Malta',
                'dial_code' => '+356',
            ),
            'MH' => array(
                'name' => 'Marshall Islands',
                'dial_code' => '+692',
            ),
            'MQ' => array(
                'name' => 'Martinique',
                'dial_code' => '+596',
            ),
            'MR' => array(
                'name' => 'Mauritania',
                'dial_code' => '+222',
            ),
            'MU' => array(
                'name' => 'Mauritius',
                'dial_code' => '+230',
            ),
            'YT' => array(
                'name' => 'Mayotte',
                'dial_code' => '+262',
            ),
            'MX' => array(
                'name' => 'Mexico',
                'dial_code' => '+52',
            ),
            'MD' => array(
                'name' => 'Moldova',
                'dial_code' => '+373',
            ),
            'MC' => array(
                'name' => 'Monaco',
                'dial_code' => '+377',
            ),
            'MN' => array(
                'name' => 'Mongolia',
                'dial_code' => '+976',
            ),
            'ME' => array(
                'name' => 'Montenegro',
                'dial_code' => '+382',
            ),
            'MS' => array(
                'name' => 'Montserrat',
                'dial_code' => '+1',
            ),
            'MA' => array(
                'name' => 'Morocco',
                'dial_code' => '+212',
            ),
            'MZ' => array(
                'name' => 'Mozambique',
                'dial_code' => '+258',
            ),
            'NA' => array(
                'name' => 'Namibia',
                'dial_code' => '+264',
            ),
            'NR' => array(
                'name' => 'Nauru',
                'dial_code' => '+674',
            ),
            'NP' => array(
                'name' => 'Nepal',
                'dial_code' => '+977',
            ),
            'NL' => array(
                'name' => 'Netherlands',
                'dial_code' => '+31',
            ),
            'AN' => array(
                'name' => 'Netherlands Antilles',
                'dial_code' => '+599',
            ),
            'NC' => array(
                'name' => 'New Caledonia',
                'dial_code' => '+687',
            ),
            'NZ' => array(
                'name' => 'New Zealand',
                'dial_code' => '+64',
            ),
            'NI' => array(
                'name' => 'Nicaragua',
                'dial_code' => '+505',
            ),
            'NE' => array(
                'name' => 'Niger',
                'dial_code' => '+227',
            ),
            'NG' => array(
                'name' => 'Nigeria',
                'dial_code' => '+234',
            ),
            'NU' => array(
                'name' => 'Niue',
                'dial_code' => '+683',
            ),
            'NF' => array(
                'name' => 'Norfolk Island',
                'dial_code' => '+672',
            ),
            'KP' => array(
                'name' => 'North Korea',
                'dial_code' => '+850',
            ),
            'MP' => array(
                'name' => 'Northern Mariana Islands',
                'dial_code' => '+1',
            ),
            'NO' => array(
                'name' => 'Norway',
                'dial_code' => '+47',
            ),
            'OM' => array(
                'name' => 'Oman',
                'dial_code' => '+968',
            ),
            'PK' => array(
                'name' => 'Pakistan',
                'dial_code' => '+92',
            ),
            'PW' => array(
                'name' => 'Palau',
                'dial_code' => '+680',
            ),
            'PS' => array(
                'name' => 'Palestine',
                'dial_code' => '+970',
            ),
            'PA' => array(
                'name' => 'Panama',
                'dial_code' => '+507',
            ),
            'PG' => array(
                'name' => 'Papua New Guinea',
                'dial_code' => '+675',
            ),
            'PY' => array(
                'name' => 'Paraguay',
                'dial_code' => '+595',
            ),
            'PE' => array(
                'name' => 'Peru',
                'dial_code' => '+51',
            ),
            'PH' => array(
                'name' => 'Philippines',
                'dial_code' => '+63',
            ),
            'PL' => array(
                'name' => 'Poland',
                'dial_code' => '+48',
            ),
            'PT' => array(
                'name' => 'Portugal',
                'dial_code' => '+351',
            ),
            'PR' => array(
                'name' => 'Puerto Rico',
                'dial_code' => '+1',
            ),
            'QA' => array(
                'name' => 'Qatar',
                'dial_code' => '+974',
            ),
            'CG' => array(
                'name' => 'Republic of the Congo',
                'dial_code' => '+242',
            ),
            'RE' => array(
                'name' => 'Réunion',
                'dial_code' => '+262',
            ),
            'RO' => array(
                'name' => 'Romania',
                'dial_code' => '+40',
            ),
            'RU' => array(
                'name' => 'Russia',
                'dial_code' => '+7',
            ),
            'RW' => array(
                'name' => 'Rwanda',
                'dial_code' => '+250',
            ),
            'BL' => array(
                'name' => 'Saint Barthélemy',
                'dial_code' => '+590',
            ),
            'SH' => array(
                'name' => 'Saint Helena',
                'dial_code' => '+290',
            ),
            'KN' => array(
                'name' => 'Saint Kitts and Nevis',
                'dial_code' => '+1',
            ),
            'MF' => array(
                'name' => 'Saint Martin',
                'dial_code' => '+590',
            ),
            'PM' => array(
                'name' => 'Saint Pierre and Miquelon',
                'dial_code' => '+508',
            ),
            'VC' => array(
                'name' => 'Saint Vincent and the Grenadines',
                'dial_code' => '+1',
            ),
            'WS' => array(
                'name' => 'Samoa',
                'dial_code' => '+685',
            ),
            'SM' => array(
                'name' => 'San Marino',
                'dial_code' => '+378',
            ),
            'ST' => array(
                'name' => 'São Tomé and Príncipe',
                'dial_code' => '+239',
            ),
            'SA' => array(
                'name' => 'Saudi Arabia',
                'dial_code' => '+966',
            ),
            'SN' => array(
                'name' => 'Senegal',
                'dial_code' => '+221',
            ),
            'RS' => array(
                'name' => 'Serbia',
                'dial_code' => '+381',
            ),
            'SC' => array(
                'name' => 'Seychelles',
                'dial_code' => '+248',
            ),
            'SL' => array(
                'name' => 'Sierra Leone',
                'dial_code' => '+232',
            ),
            'SG' => array(
                'name' => 'Singapore',
                'dial_code' => '+65',
            ),
            'SK' => array(
                'name' => 'Slovakia',
                'dial_code' => '+421',
            ),
            'SI' => array(
                'name' => 'Slovenia',
                'dial_code' => '+386',
            ),
            'SB' => array(
                'name' => 'Solomon Islands',
                'dial_code' => '+677',
            ),
            'SO' => array(
                'name' => 'Somalia',
                'dial_code' => '+252',
            ),
            'ZA' => array(
                'name' => 'South Africa',
                'dial_code' => '+27',
            ),
            'KR' => array(
                'name' => 'South Korea',
                'dial_code' => '+82',
            ),
            'ES' => array(
                'name' => 'Spain',
                'dial_code' => '+34',
            ),
            'LK' => array(
                'name' => 'Sri Lanka',
                'dial_code' => '+94',
            ),
            'LC' => array(
                'name' => 'St. Lucia',
                'dial_code' => '+1',
            ),
            'SD' => array(
                'name' => 'Sudan',
                'dial_code' => '+249',
            ),
            'SR' => array(
                'name' => 'Suriname',
                'dial_code' => '+597',
            ),
            'SZ' => array(
                'name' => 'Swaziland',
                'dial_code' => '+268',
            ),
            'SE' => array(
                'name' => 'Sweden',
                'dial_code' => '+46',
            ),
            'CH' => array(
                'name' => 'Switzerland',
                'dial_code' => '+41',
            ),
            'SY' => array(
                'name' => 'Syria',
                'dial_code' => '+963',
            ),
            'TW' => array(
                'name' => 'Taiwan',
                'dial_code' => '+886',
            ),
            'TJ' => array(
                'name' => 'Tajikistan',
                'dial_code' => '+992',
            ),
            'TZ' => array(
                'name' => 'Tanzania',
                'dial_code' => '+255',
            ),
            'TH' => array(
                'name' => 'Thailand',
                'dial_code' => '+66',
            ),
            'BS' => array(
                'name' => 'The Bahamas',
                'dial_code' => '+1',
            ),
            'GM' => array(
                'name' => 'The Gambia',
                'dial_code' => '+220',
            ),
            'TL' => array(
                'name' => 'Timor-Leste',
                'dial_code' => '+670',
            ),
            'TG' => array(
                'name' => 'Togo',
                'dial_code' => '+228',
            ),
            'TK' => array(
                'name' => 'Tokelau',
                'dial_code' => '+690',
            ),
            'TO' => array(
                'name' => 'Tonga',
                'dial_code' => '+676',
            ),
            'TT' => array(
                'name' => 'Trinidad and Tobago',
                'dial_code' => '+1',
            ),
            'TN' => array(
                'name' => 'Tunisia',
                'dial_code' => '+216',
            ),
            'TR' => array(
                'name' => 'Turkey',
                'dial_code' => '+90',
            ),
            'TM' => array(
                'name' => 'Turkmenistan',
                'dial_code' => '+993',
            ),
            'TC' => array(
                'name' => 'Turks and Caicos Islands',
                'dial_code' => '+1',
            ),
            'TV' => array(
                'name' => 'Tuvalu',
                'dial_code' => '+688',
            ),
            'UG' => array(
                'name' => 'Uganda',
                'dial_code' => '+256',
            ),
            'UA' => array(
                'name' => 'Ukraine',
                'dial_code' => '+380',
            ),
            'AE' => array(
                'name' => 'United Arab Emirates',
                'dial_code' => '+971',
            ),
            'GB' => array(
                'name' => 'United Kingdom',
                'dial_code' => '+44',
            ),
            'US' => array(
                'name' => 'United States',
                'dial_code' => '+1',
            ),
            'UY' => array(
                'name' => 'Uruguay',
                'dial_code' => '+598',
            ),
            'VI' => array(
                'name' => 'US Virgin Islands',
                'dial_code' => '+1',
            ),
            'UZ' => array(
                'name' => 'Uzbekistan',
                'dial_code' => '+998',
            ),
            'VU' => array(
                'name' => 'Vanuatu',
                'dial_code' => '+678',
            ),
            'VA' => array(
                'name' => 'Vatican City',
                'dial_code' => '+39',
            ),
            'VE' => array(
                'name' => 'Venezuela',
                'dial_code' => '+58',
            ),
            'VN' => array(
                'name' => 'Vietnam',
                'dial_code' => '+84',
            ),
            'WF' => array(
                'name' => 'Wallis and Futuna',
                'dial_code' => '+681',
            ),
            'YE' => array(
                'name' => 'Yemen',
                'dial_code' => '+967',
            ),
            'ZM' => array(
                'name' => 'Zambia',
                'dial_code' => '+260',
            ),
            'ZW' => array(
                'name' => 'Zimbabwe',
                'dial_code' => '+263',
            ),
        );
        if (substr($phone_number, 0, 1) != '+' && array_key_exists($iso_code, $countries)) {
            $country = $countries[$iso_code];
            $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
            if (substr($phone_number, 0, 2) == '00') {
                $phone_number = '+' . substr($phone_number, 2);
            } elseif (substr($phone_number, 0, 1) == '0') {
                $phone_number = $country['dial_code'] . substr($phone_number, 1);
            } elseif (strlen($phone_number) > 10) {
                $phone_number = '+' . $phone_number;
            } else {
                $phone_number = $country['dial_code'] . $phone_number;
            }
        }

        return $phone_number;
    }
}