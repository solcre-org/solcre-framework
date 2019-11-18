<?php

namespace Solcre\SolcreFramework2\Utility;

use DateTime;

class Date
{
    public static function current(): DateTime
    {
        return new DateTime('NOW');
    }

    public static function getTimezones(): array
    {
        return [
            [
                'id'      => 0,
                'display' => 'Unknown'
            ],
            [
                'id'      => 1,
                'display' => 'Africa/Abidjan'
            ],
            [
                'id'      => 2,
                'display' => 'Africa/Accra'
            ],
            [
                'id'      => 3,
                'display' => 'Africa/Addis_Ababa'
            ],
            [
                'id'      => 4,
                'display' => 'Africa/Algiers'
            ],
            [
                'id'      => 5,
                'display' => 'Africa/Asmara'
            ],
            [
                'id'      => 6,
                'display' => 'Africa/Asmera'
            ],
            [
                'id'      => 7,
                'display' => 'Africa/Bamako'
            ],
            [
                'id'      => 8,
                'display' => 'Africa/Bangui'
            ],
            [
                'id'      => 9,
                'display' => 'Africa/Banjul'
            ],
            [
                'id'      => 10,
                'display' => 'Africa/Bissau'
            ],
            [
                'id'      => 11,
                'display' => 'Africa/Blantyre'
            ],
            [
                'id'      => 12,
                'display' => 'Africa/Brazzaville'
            ],
            [
                'id'      => 13,
                'display' => 'Africa/Cairo'
            ],
            [
                'id'      => 14,
                'display' => 'Africa/Casablanca'
            ],
            [
                'id'      => 15,
                'display' => 'Africa/Ceuta'
            ],
            [
                'id'      => 16,
                'display' => 'Africa/Conakry'
            ],
            [
                'id'      => 17,
                'display' => 'Africa/Dakar'
            ],
            [
                'id'      => 18,
                'display' => 'Africa/Dar_es_Salaam'
            ],
            [
                'id'      => 19,
                'display' => 'Africa/Djibouti'
            ],
            [
                'id'      => 20,
                'display' => 'Africa/Douala'
            ],
            [
                'id'      => 21,
                'display' => 'Africa/El_Aaiun'
            ],
            [
                'id'      => 22,
                'display' => 'Africa/Freetown'
            ],
            [
                'id'      => 23,
                'display' => 'Africa/Gaborone'
            ],
            [
                'id'      => 24,
                'display' => 'Africa/Harare'
            ],
            [
                'id'      => 25,
                'display' => 'Africa/Johannesburg'
            ],
            [
                'id'      => 26,
                'display' => 'Africa/Kampala'
            ],
            [
                'id'      => 27,
                'display' => 'Africa/Khartoum'
            ],
            [
                'id'      => 28,
                'display' => 'Africa/Kigali'
            ],
            [
                'id'      => 29,
                'display' => 'Africa/Lagos'
            ],
            [
                'id'      => 30,
                'display' => 'Africa/Libreville'
            ],
            [
                'id'      => 31,
                'display' => 'Africa/Luanda'
            ],
            [
                'id'      => 32,
                'display' => 'Africa/Lusaka'
            ],
            [
                'id'      => 33,
                'display' => 'Africa/Malabo'
            ],
            [
                'id'      => 34,
                'display' => 'Africa/Maputo'
            ],
            [
                'id'      => 35,
                'display' => 'Africa/Maseru'
            ],
            [
                'id'      => 36,
                'display' => 'Africa/Mbabane'
            ],
            [
                'id'      => 37,
                'display' => 'Africa/Mogadishu'
            ],
            [
                'id'      => 38,
                'display' => 'Africa/Monrovia'
            ],
            [
                'id'      => 39,
                'display' => 'Africa/Nairobi'
            ],
            [
                'id'      => 40,
                'display' => 'Africa/Ndjamena'
            ],
            [
                'id'      => 41,
                'display' => 'Africa/Niamey'
            ],
            [
                'id'      => 42,
                'display' => 'Africa/Nouakchott'
            ],
            [
                'id'      => 43,
                'display' => 'Africa/Ouagadougou'
            ],
            [
                'id'      => 44,
                'display' => 'Africa/Porto-Novo'
            ],
            [
                'id'      => 45,
                'display' => 'Africa/Sao_Tome'
            ],
            [
                'id'      => 46,
                'display' => 'Africa/Timbuktu'
            ],
            [
                'id'      => 47,
                'display' => 'Africa/Tripoli'
            ],
            [
                'id'      => 48,
                'display' => 'Africa/Tunis'
            ],
            [
                'id'      => 49,
                'display' => 'Africa/Windhoek'
            ],
            [
                'id'      => 50,
                'display' => 'America/Adak'
            ],
            [
                'id'      => 51,
                'display' => 'America/Anchorage'
            ],
            [
                'id'      => 52,
                'display' => 'America/Anguilla'
            ],
            [
                'id'      => 53,
                'display' => 'America/Antigua'
            ],
            [
                'id'      => 54,
                'display' => 'America/Araguaina'
            ],
            [
                'id'      => 55,
                'display' => 'America/Argentina/Buenos_Aires'
            ],
            [
                'id'      => 56,
                'display' => 'America/Argentina/Catamarca'
            ],
            [
                'id'      => 57,
                'display' => 'America/Argentina/ComodRivadavia'
            ],
            [
                'id'      => 58,
                'display' => 'America/Argentina/Cordoba'
            ],
            [
                'id'      => 59,
                'display' => 'America/Argentina/Jujuy'
            ],
            [
                'id'      => 60,
                'display' => 'America/Argentina/La_Rioja'
            ],
            [
                'id'      => 61,
                'display' => 'America/Argentina/Mendoza'
            ],
            [
                'id'      => 62,
                'display' => 'America/Argentina/Rio_Gallegos'
            ],
            [
                'id'      => 63,
                'display' => 'America/Argentina/San_Juan'
            ],
            [
                'id'      => 64,
                'display' => 'America/Argentina/Tucuman'
            ],
            [
                'id'      => 65,
                'display' => 'America/Argentina/Ushuaia'
            ],
            [
                'id'      => 66,
                'display' => 'America/Aruba'
            ],
            [
                'id'      => 67,
                'display' => 'America/Asuncion'
            ],
            [
                'id'      => 68,
                'display' => 'America/Atikokan'
            ],
            [
                'id'      => 69,
                'display' => 'America/Atka'
            ],
            [
                'id'      => 70,
                'display' => 'America/Bahia'
            ],
            [
                'id'      => 71,
                'display' => 'America/Barbados'
            ],
            [
                'id'      => 72,
                'display' => 'America/Belem'
            ],
            [
                'id'      => 73,
                'display' => 'America/Belize'
            ],
            [
                'id'      => 74,
                'display' => 'America/Blanc-Sablon'
            ],
            [
                'id'      => 75,
                'display' => 'America/Boa_Vista'
            ],
            [
                'id'      => 76,
                'display' => 'America/Bogota'
            ],
            [
                'id'      => 77,
                'display' => 'America/Boise'
            ],
            [
                'id'      => 78,
                'display' => 'America/Buenos_Aires'
            ],
            [
                'id'      => 79,
                'display' => 'America/Cambridge_Bay'
            ],
            [
                'id'      => 80,
                'display' => 'America/Campo_Grande'
            ],
            [
                'id'      => 81,
                'display' => 'America/Cancun'
            ],
            [
                'id'      => 82,
                'display' => 'America/Caracas'
            ],
            [
                'id'      => 83,
                'display' => 'America/Catamarca'
            ],
            [
                'id'      => 84,
                'display' => 'America/Cayenne'
            ],
            [
                'id'      => 85,
                'display' => 'America/Cayman'
            ],
            [
                'id'      => 86,
                'display' => 'America/Chicago'
            ],
            [
                'id'      => 87,
                'display' => 'America/Chihuahua'
            ],
            [
                'id'      => 88,
                'display' => 'America/Coral_Harbour'
            ],
            [
                'id'      => 89,
                'display' => 'America/Cordoba'
            ],
            [
                'id'      => 90,
                'display' => 'America/Costa_Rica'
            ],
            [
                'id'      => 91,
                'display' => 'America/Cuiaba'
            ],
            [
                'id'      => 92,
                'display' => 'America/Curacao'
            ],
            [
                'id'      => 93,
                'display' => 'America/Danmarkshavn'
            ],
            [
                'id'      => 94,
                'display' => 'America/Dawson'
            ],
            [
                'id'      => 95,
                'display' => 'America/Dawson_Creek'
            ],
            [
                'id'      => 96,
                'display' => 'America/Denver'
            ],
            [
                'id'      => 97,
                'display' => 'America/Detroit'
            ],
            [
                'id'      => 98,
                'display' => 'America/Dominica'
            ],
            [
                'id'      => 99,
                'display' => 'America/Edmonton'
            ],
            [
                'id'      => 100,
                'display' => 'America/Eirunepe'
            ],
            [
                'id'      => 101,
                'display' => 'America/El_Salvador'
            ],
            [
                'id'      => 102,
                'display' => 'America/Ensenada'
            ],
            [
                'id'      => 103,
                'display' => 'America/Fort_Wayne'
            ],
            [
                'id'      => 104,
                'display' => 'America/Fortaleza'
            ],
            [
                'id'      => 105,
                'display' => 'America/Glace_Bay'
            ],
            [
                'id'      => 106,
                'display' => 'America/Godthab'
            ],
            [
                'id'      => 107,
                'display' => 'America/Goose_Bay'
            ],
            [
                'id'      => 108,
                'display' => 'America/Grand_Turk'
            ],
            [
                'id'      => 109,
                'display' => 'America/Grenada'
            ],
            [
                'id'      => 110,
                'display' => 'America/Guadeloupe'
            ],
            [
                'id'      => 111,
                'display' => 'America/Guatemala'
            ],
            [
                'id'      => 112,
                'display' => 'America/Guayaquil'
            ],
            [
                'id'      => 113,
                'display' => 'America/Guyana'
            ],
            [
                'id'      => 114,
                'display' => 'America/Halifax'
            ],
            [
                'id'      => 115,
                'display' => 'America/Havana'
            ],
            [
                'id'      => 116,
                'display' => 'America/Hermosillo'
            ],
            [
                'id'      => 117,
                'display' => 'America/Indiana/Indianapolis'
            ],
            [
                'id'      => 118,
                'display' => 'America/Indiana/Knox'
            ],
            [
                'id'      => 119,
                'display' => 'America/Indiana/Marengo'
            ],
            [
                'id'      => 120,
                'display' => 'America/Indiana/Petersburg'
            ],
            [
                'id'      => 121,
                'display' => 'America/Indiana/Vevay'
            ],
            [
                'id'      => 122,
                'display' => 'America/Indiana/Vincennes'
            ],
            [
                'id'      => 123,
                'display' => 'America/Indiana/Winamac'
            ],
            [
                'id'      => 124,
                'display' => 'America/Indianapolis'
            ],
            [
                'id'      => 125,
                'display' => 'America/Inuvik'
            ],
            [
                'id'      => 126,
                'display' => 'America/Iqaluit'
            ],
            [
                'id'      => 127,
                'display' => 'America/Jamaica'
            ],
            [
                'id'      => 128,
                'display' => 'America/Jujuy'
            ],
            [
                'id'      => 129,
                'display' => 'America/Juneau'
            ],
            [
                'id'      => 130,
                'display' => 'America/Kentucky/Louisville'
            ],
            [
                'id'      => 131,
                'display' => 'America/Kentucky/Monticello'
            ],
            [
                'id'      => 132,
                'display' => 'America/Knox_IN'
            ],
            [
                'id'      => 133,
                'display' => 'America/La_Paz'
            ],
            [
                'id'      => 134,
                'display' => 'America/Lima'
            ],
            [
                'id'      => 135,
                'display' => 'America/Los_Angeles'
            ],
            [
                'id'      => 136,
                'display' => 'America/Louisville'
            ],
            [
                'id'      => 137,
                'display' => 'America/Maceio'
            ],
            [
                'id'      => 138,
                'display' => 'America/Managua'
            ],
            [
                'id'      => 139,
                'display' => 'America/Manaus'
            ],
            [
                'id'      => 140,
                'display' => 'America/Martinique'
            ],
            [
                'id'      => 141,
                'display' => 'America/Mazatlan'
            ],
            [
                'id'      => 142,
                'display' => 'America/Mendoza'
            ],
            [
                'id'      => 143,
                'display' => 'America/Menominee'
            ],
            [
                'id'      => 144,
                'display' => 'America/Merida'
            ],
            [
                'id'      => 145,
                'display' => 'America/Mexico_City'
            ],
            [
                'id'      => 146,
                'display' => 'America/Miquelon'
            ],
            [
                'id'      => 147,
                'display' => 'America/Moncton'
            ],
            [
                'id'      => 148,
                'display' => 'America/Monterrey'
            ],
            [
                'id'      => 149,
                'display' => 'America/Montevideo'
            ],
            [
                'id'      => 150,
                'display' => 'America/Montreal'
            ],
            [
                'id'      => 151,
                'display' => 'America/Montserrat'
            ],
            [
                'id'      => 152,
                'display' => 'America/Nassau'
            ],
            [
                'id'      => 153,
                'display' => 'America/New_York'
            ],
            [
                'id'      => 154,
                'display' => 'America/Nipigon'
            ],
            [
                'id'      => 155,
                'display' => 'America/Nome'
            ],
            [
                'id'      => 156,
                'display' => 'America/Noronha'
            ],
            [
                'id'      => 157,
                'display' => 'America/North_Dakota/Center'
            ],
            [
                'id'      => 158,
                'display' => 'America/North_Dakota/New_Salem'
            ],
            [
                'id'      => 159,
                'display' => 'America/Panama'
            ],
            [
                'id'      => 160,
                'display' => 'America/Pangnirtung'
            ],
            [
                'id'      => 161,
                'display' => 'America/Paramaribo'
            ],
            [
                'id'      => 162,
                'display' => 'America/Phoenix'
            ],
            [
                'id'      => 163,
                'display' => 'America/Port-au-Prince'
            ],
            [
                'id'      => 164,
                'display' => 'America/Port_of_Spain'
            ],
            [
                'id'      => 165,
                'display' => 'America/Porto_Acre'
            ],
            [
                'id'      => 166,
                'display' => 'America/Porto_Velho'
            ],
            [
                'id'      => 167,
                'display' => 'America/Puerto_Rico'
            ],
            [
                'id'      => 168,
                'display' => 'America/Rainy_River'
            ],
            [
                'id'      => 169,
                'display' => 'America/Rankin_Inlet'
            ],
            [
                'id'      => 170,
                'display' => 'America/Recife'
            ],
            [
                'id'      => 171,
                'display' => 'America/Regina'
            ],
            [
                'id'      => 172,
                'display' => 'America/Rio_Branco'
            ],
            [
                'id'      => 173,
                'display' => 'America/Rosario'
            ],
            [
                'id'      => 174,
                'display' => 'America/Santiago'
            ],
            [
                'id'      => 175,
                'display' => 'America/Santo_Domingo'
            ],
            [
                'id'      => 176,
                'display' => 'America/Sao_Paulo'
            ],
            [
                'id'      => 177,
                'display' => 'America/Scoresbysund'
            ],
            [
                'id'      => 178,
                'display' => 'America/Shiprock'
            ],
            [
                'id'      => 179,
                'display' => 'America/St_Johns'
            ],
            [
                'id'      => 180,
                'display' => 'America/St_Kitts'
            ],
            [
                'id'      => 181,
                'display' => 'America/St_Lucia'
            ],
            [
                'id'      => 182,
                'display' => 'America/St_Thomas'
            ],
            [
                'id'      => 183,
                'display' => 'America/St_Vincent'
            ],
            [
                'id'      => 184,
                'display' => 'America/Swift_Current'
            ],
            [
                'id'      => 185,
                'display' => 'America/Tegucigalpa'
            ],
            [
                'id'      => 186,
                'display' => 'America/Thule'
            ],
            [
                'id'      => 187,
                'display' => 'America/Thunder_Bay'
            ],
            [
                'id'      => 188,
                'display' => 'America/Tijuana'
            ],
            [
                'id'      => 189,
                'display' => 'America/Toronto'
            ],
            [
                'id'      => 190,
                'display' => 'America/Tortola'
            ],
            [
                'id'      => 191,
                'display' => 'America/Vancouver'
            ],
            [
                'id'      => 192,
                'display' => 'America/Virgin'
            ],
            [
                'id'      => 193,
                'display' => 'America/Whitehorse'
            ],
            [
                'id'      => 194,
                'display' => 'America/Winnipeg'
            ],
            [
                'id'      => 195,
                'display' => 'America/Yakutat'
            ],
            [
                'id'      => 196,
                'display' => 'America/Yellowknife'
            ],
            [
                'id'      => 197,
                'display' => 'Antarctica/Casey'
            ],
            [
                'id'      => 198,
                'display' => 'Antarctica/Davis'
            ],
            [
                'id'      => 199,
                'display' => 'Antarctica/DumontDUrville'
            ],
            [
                'id'      => 200,
                'display' => 'Antarctica/Mawson'
            ],
            [
                'id'      => 201,
                'display' => 'Antarctica/McMurdo'
            ],
            [
                'id'      => 202,
                'display' => 'Antarctica/Palmer'
            ],
            [
                'id'      => 203,
                'display' => 'Antarctica/Rothera'
            ],
            [
                'id'      => 204,
                'display' => 'Antarctica/South_Pole'
            ],
            [
                'id'      => 205,
                'display' => 'Antarctica/Syowa'
            ],
            [
                'id'      => 206,
                'display' => 'Antarctica/Vostok'
            ],
            [
                'id'      => 207,
                'display' => 'Arctic/Longyearbyen'
            ],
            [
                'id'      => 208,
                'display' => 'Asia/Aden'
            ],
            [
                'id'      => 209,
                'display' => 'Asia/Almaty'
            ],
            [
                'id'      => 210,
                'display' => 'Asia/Amman'
            ],
            [
                'id'      => 211,
                'display' => 'Asia/Anadyr'
            ],
            [
                'id'      => 212,
                'display' => 'Asia/Aqtau'
            ],
            [
                'id'      => 213,
                'display' => 'Asia/Aqtobe'
            ],
            [
                'id'      => 214,
                'display' => 'Asia/Ashgabat'
            ],
            [
                'id'      => 215,
                'display' => 'Asia/Ashkhabad'
            ],
            [
                'id'      => 216,
                'display' => 'Asia/Baghdad'
            ],
            [
                'id'      => 217,
                'display' => 'Asia/Bahrain'
            ],
            [
                'id'      => 218,
                'display' => 'Asia/Baku'
            ],
            [
                'id'      => 219,
                'display' => 'Asia/Bangkok'
            ],
            [
                'id'      => 220,
                'display' => 'Asia/Beirut'
            ],
            [
                'id'      => 221,
                'display' => 'Asia/Bishkek'
            ],
            [
                'id'      => 222,
                'display' => 'Asia/Brunei'
            ],
            [
                'id'      => 223,
                'display' => 'Asia/Calcutta'
            ],
            [
                'id'      => 224,
                'display' => 'Asia/Choibalsan'
            ],
            [
                'id'      => 225,
                'display' => 'Asia/Chongqing'
            ],
            [
                'id'      => 226,
                'display' => 'Asia/Chungking'
            ],
            [
                'id'      => 227,
                'display' => 'Asia/Colombo'
            ],
            [
                'id'      => 228,
                'display' => 'Asia/Dacca'
            ],
            [
                'id'      => 229,
                'display' => 'Asia/Damascus'
            ],
            [
                'id'      => 230,
                'display' => 'Asia/Dhaka'
            ],
            [
                'id'      => 231,
                'display' => 'Asia/Dili'
            ],
            [
                'id'      => 232,
                'display' => 'Asia/Dubai'
            ],
            [
                'id'      => 233,
                'display' => 'Asia/Dushanbe'
            ],
            [
                'id'      => 234,
                'display' => 'Asia/Gaza'
            ],
            [
                'id'      => 235,
                'display' => 'Asia/Harbin'
            ],
            [
                'id'      => 236,
                'display' => 'Asia/Hong_Kong'
            ],
            [
                'id'      => 237,
                'display' => 'Asia/Hovd'
            ],
            [
                'id'      => 238,
                'display' => 'Asia/Irkutsk'
            ],
            [
                'id'      => 239,
                'display' => 'Asia/Istanbul'
            ],
            [
                'id'      => 240,
                'display' => 'Asia/Jakarta'
            ],
            [
                'id'      => 241,
                'display' => 'Asia/Jayapura'
            ],
            [
                'id'      => 242,
                'display' => 'Asia/Jerusalem'
            ],
            [
                'id'      => 243,
                'display' => 'Asia/Kabul'
            ],
            [
                'id'      => 244,
                'display' => 'Asia/Kamchatka'
            ],
            [
                'id'      => 245,
                'display' => 'Asia/Karachi'
            ],
            [
                'id'      => 246,
                'display' => 'Asia/Kashgar'
            ],
            [
                'id'      => 247,
                'display' => 'Asia/Katmandu'
            ],
            [
                'id'      => 248,
                'display' => 'Asia/Kolkata'
            ],
            [
                'id'      => 249,
                'display' => 'Asia/Krasnoyarsk'
            ],
            [
                'id'      => 250,
                'display' => 'Asia/Kuala_Lumpur'
            ],
            [
                'id'      => 251,
                'display' => 'Asia/Kuching'
            ],
            [
                'id'      => 252,
                'display' => 'Asia/Kuwait'
            ],
            [
                'id'      => 253,
                'display' => 'Asia/Macao'
            ],
            [
                'id'      => 254,
                'display' => 'Asia/Macau'
            ],
            [
                'id'      => 255,
                'display' => 'Asia/Magadan'
            ],
            [
                'id'      => 256,
                'display' => 'Asia/Makassar'
            ],
            [
                'id'      => 257,
                'display' => 'Asia/Manila'
            ],
            [
                'id'      => 258,
                'display' => 'Asia/Muscat'
            ],
            [
                'id'      => 259,
                'display' => 'Asia/Nicosia'
            ],
            [
                'id'      => 260,
                'display' => 'Asia/Novosibirsk'
            ],
            [
                'id'      => 261,
                'display' => 'Asia/Omsk'
            ],
            [
                'id'      => 262,
                'display' => 'Asia/Oral'
            ],
            [
                'id'      => 263,
                'display' => 'Asia/Phnom_Penh'
            ],
            [
                'id'      => 264,
                'display' => 'Asia/Pontianak'
            ],
            [
                'id'      => 265,
                'display' => 'Asia/Pyongyang'
            ],
            [
                'id'      => 266,
                'display' => 'Asia/Qatar'
            ],
            [
                'id'      => 267,
                'display' => 'Asia/Qyzylorda'
            ],
            [
                'id'      => 268,
                'display' => 'Asia/Rangoon'
            ],
            [
                'id'      => 269,
                'display' => 'Asia/Riyadh'
            ],
            [
                'id'      => 270,
                'display' => 'Asia/Saigon'
            ],
            [
                'id'      => 271,
                'display' => 'Asia/Sakhalin'
            ],
            [
                'id'      => 272,
                'display' => 'Asia/Samarkand'
            ],
            [
                'id'      => 273,
                'display' => 'Asia/Seoul'
            ],
            [
                'id'      => 274,
                'display' => 'Asia/Shanghai'
            ],
            [
                'id'      => 275,
                'display' => 'Asia/Singapore'
            ],
            [
                'id'      => 276,
                'display' => 'Asia/Taipei'
            ],
            [
                'id'      => 277,
                'display' => 'Asia/Tashkent'
            ],
            [
                'id'      => 278,
                'display' => 'Asia/Tbilisi'
            ],
            [
                'id'      => 279,
                'display' => 'Asia/Tehran'
            ],
            [
                'id'      => 280,
                'display' => 'Asia/Tel_Aviv'
            ],
            [
                'id'      => 281,
                'display' => 'Asia/Thimbu'
            ],
            [
                'id'      => 282,
                'display' => 'Asia/Thimphu'
            ],
            [
                'id'      => 283,
                'display' => 'Asia/Tokyo'
            ],
            [
                'id'      => 284,
                'display' => 'Asia/Ujung_Pandang'
            ],
            [
                'id'      => 285,
                'display' => 'Asia/Ulaanbaatar'
            ],
            [
                'id'      => 286,
                'display' => 'Asia/Ulan_Bator'
            ],
            [
                'id'      => 287,
                'display' => 'Asia/Urumqi'
            ],
            [
                'id'      => 288,
                'display' => 'Asia/Vientiane'
            ],
            [
                'id'      => 289,
                'display' => 'Asia/Vladivostok'
            ],
            [
                'id'      => 290,
                'display' => 'Asia/Yakutsk'
            ],
            [
                'id'      => 291,
                'display' => 'Asia/Yekaterinburg'
            ],
            [
                'id'      => 292,
                'display' => 'Asia/Yerevan'
            ],
            [
                'id'      => 293,
                'display' => 'Atlantic/Azores'
            ],
            [
                'id'      => 294,
                'display' => 'Atlantic/Bermuda'
            ],
            [
                'id'      => 295,
                'display' => 'Atlantic/Canary'
            ],
            [
                'id'      => 296,
                'display' => 'Atlantic/Cape_Verde'
            ],
            [
                'id'      => 297,
                'display' => 'Atlantic/Faeroe'
            ],
            [
                'id'      => 298,
                'display' => 'Atlantic/Faroe'
            ],
            [
                'id'      => 299,
                'display' => 'Atlantic/Jan_Mayen'
            ],
            [
                'id'      => 300,
                'display' => 'Atlantic/Madeira'
            ],
            [
                'id'      => 301,
                'display' => 'Atlantic/Reykjavik'
            ],
            [
                'id'      => 302,
                'display' => 'Atlantic/St_Helena'
            ],
            [
                'id'      => 303,
                'display' => 'Atlantic/Stanley'
            ],
            [
                'id'      => 304,
                'display' => 'Australia/Adelaide'
            ],
            [
                'id'      => 305,
                'display' => 'Australia/Brisbane'
            ],
            [
                'id'      => 306,
                'display' => 'Australia/Broken_Hill'
            ],
            [
                'id'      => 307,
                'display' => 'Australia/Canberra'
            ],
            [
                'id'      => 308,
                'display' => 'Australia/Currie'
            ],
            [
                'id'      => 309,
                'display' => 'Australia/Darwin'
            ],
            [
                'id'      => 310,
                'display' => 'Australia/Eucla'
            ],
            [
                'id'      => 311,
                'display' => 'Australia/Hobart'
            ],
            [
                'id'      => 312,
                'display' => 'Australia/Lindeman'
            ],
            [
                'id'      => 313,
                'display' => 'Australia/Lord_Howe'
            ],
            [
                'id'      => 314,
                'display' => 'Australia/Melbourne'
            ],
            [
                'id'      => 315,
                'display' => 'Australia/North'
            ],
            [
                'id'      => 316,
                'display' => 'Australia/NSW'
            ],
            [
                'id'      => 317,
                'display' => 'Australia/Perth'
            ],
            [
                'id'      => 318,
                'display' => 'Australia/Queensland'
            ],
            [
                'id'      => 319,
                'display' => 'Australia/South'
            ],
            [
                'id'      => 320,
                'display' => 'Australia/Sydney'
            ],
            [
                'id'      => 321,
                'display' => 'Australia/Tasmania'
            ],
            [
                'id'      => 322,
                'display' => 'Australia/Victoria'
            ],
            [
                'id'      => 323,
                'display' => 'Australia/West'
            ],
            [
                'id'      => 324,
                'display' => 'Australia/Yancowinna'
            ],
            [
                'id'      => 325,
                'display' => 'Europe/Amsterdam'
            ],
            [
                'id'      => 326,
                'display' => 'Europe/Andorra'
            ],
            [
                'id'      => 327,
                'display' => 'Europe/Athens'
            ],
            [
                'id'      => 328,
                'display' => 'Europe/Belfast'
            ],
            [
                'id'      => 329,
                'display' => 'Europe/Belgrade'
            ],
            [
                'id'      => 330,
                'display' => 'Europe/Berlin'
            ],
            [
                'id'      => 331,
                'display' => 'Europe/Bratislava'
            ],
            [
                'id'      => 332,
                'display' => 'Europe/Brussels'
            ],
            [
                'id'      => 333,
                'display' => 'Europe/Bucharest'
            ],
            [
                'id'      => 334,
                'display' => 'Europe/Budapest'
            ],
            [
                'id'      => 335,
                'display' => 'Europe/Chisinau'
            ],
            [
                'id'      => 336,
                'display' => 'Europe/Copenhagen'
            ],
            [
                'id'      => 337,
                'display' => 'Europe/Dublin'
            ],
            [
                'id'      => 338,
                'display' => 'Europe/Gibraltar'
            ],
            [
                'id'      => 339,
                'display' => 'Europe/Guernsey'
            ],
            [
                'id'      => 340,
                'display' => 'Europe/Helsinki'
            ],
            [
                'id'      => 341,
                'display' => 'Europe/Isle_of_Man'
            ],
            [
                'id'      => 342,
                'display' => 'Europe/Istanbul'
            ],
            [
                'id'      => 343,
                'display' => 'Europe/Jersey'
            ],
            [
                'id'      => 344,
                'display' => 'Europe/Kaliningrad'
            ],
            [
                'id'      => 345,
                'display' => 'Europe/Kiev'
            ],
            [
                'id'      => 346,
                'display' => 'Europe/Lisbon'
            ],
            [
                'id'      => 347,
                'display' => 'Europe/Ljubljana'
            ],
            [
                'id'      => 348,
                'display' => 'Europe/London'
            ],
            [
                'id'      => 349,
                'display' => 'Europe/Luxembourg'
            ],
            [
                'id'      => 350,
                'display' => 'Europe/Madrid'
            ],
            [
                'id'      => 351,
                'display' => 'Europe/Malta'
            ],
            [
                'id'      => 352,
                'display' => 'Europe/Mariehamn'
            ],
            [
                'id'      => 353,
                'display' => 'Europe/Minsk'
            ],
            [
                'id'      => 354,
                'display' => 'Europe/Monaco'
            ],
            [
                'id'      => 355,
                'display' => 'Europe/Moscow'
            ],
            [
                'id'      => 356,
                'display' => 'Europe/Nicosia'
            ],
            [
                'id'      => 357,
                'display' => 'Europe/Oslo'
            ],
            [
                'id'      => 358,
                'display' => 'Europe/Paris'
            ],
            [
                'id'      => 359,
                'display' => 'Europe/Podgorica'
            ],
            [
                'id'      => 360,
                'display' => 'Europe/Prague'
            ],
            [
                'id'      => 361,
                'display' => 'Europe/Riga'
            ],
            [
                'id'      => 362,
                'display' => 'Europe/Rome'
            ],
            [
                'id'      => 363,
                'display' => 'Europe/Samara'
            ],
            [
                'id'      => 364,
                'display' => 'Europe/San_Marino'
            ],
            [
                'id'      => 365,
                'display' => 'Europe/Sarajevo'
            ],
            [
                'id'      => 366,
                'display' => 'Europe/Simferopol'
            ],
            [
                'id'      => 367,
                'display' => 'Europe/Skopje'
            ],
            [
                'id'      => 368,
                'display' => 'Europe/Sofia'
            ],
            [
                'id'      => 369,
                'display' => 'Europe/Stockholm'
            ],
            [
                'id'      => 370,
                'display' => 'Europe/Tallinn'
            ],
            [
                'id'      => 371,
                'display' => 'Europe/Tirane'
            ],
            [
                'id'      => 372,
                'display' => 'Europe/Tiraspol'
            ],
            [
                'id'      => 373,
                'display' => 'Europe/Uzhgorod'
            ],
            [
                'id'      => 374,
                'display' => 'Europe/Vaduz'
            ],
            [
                'id'      => 375,
                'display' => 'Europe/Vatican'
            ],
            [
                'id'      => 376,
                'display' => 'Europe/Vienna'
            ],
            [
                'id'      => 377,
                'display' => 'Europe/Vilnius'
            ],
            [
                'id'      => 378,
                'display' => 'Europe/Volgograd'
            ],
            [
                'id'      => 379,
                'display' => 'Europe/Warsaw'
            ],
            [
                'id'      => 380,
                'display' => 'Europe/Zagreb'
            ],
            [
                'id'      => 381,
                'display' => 'Europe/Zaporozhye'
            ],
            [
                'id'      => 382,
                'display' => 'Europe/Zurich'
            ],
            [
                'id'      => 383,
                'display' => 'Indian/Antananarivo'
            ],
            [
                'id'      => 384,
                'display' => 'Indian/Chagos'
            ],
            [
                'id'      => 385,
                'display' => 'Indian/Comoro'
            ],
            [
                'id'      => 386,
                'display' => 'Indian/Kerguelen'
            ],
            [
                'id'      => 387,
                'display' => 'Indian/Mahe'
            ],
            [
                'id'      => 388,
                'display' => 'Indian/Maldives'
            ],
            [
                'id'      => 389,
                'display' => 'Indian/Mauritius'
            ],
            [
                'id'      => 390,
                'display' => 'Indian/Mayotte'
            ],
            [
                'id'      => 391,
                'display' => 'Indian/Reunion'
            ],
            [
                'id'      => 392,
                'display' => 'Pacific/Apia'
            ],
            [
                'id'      => 393,
                'display' => 'Pacific/Auckland'
            ],
            [
                'id'      => 394,
                'display' => 'Pacific/Chatham'
            ],
            [
                'id'      => 395,
                'display' => 'Pacific/Easter'
            ],
            [
                'id'      => 396,
                'display' => 'Pacific/Efate'
            ],
            [
                'id'      => 397,
                'display' => 'Pacific/Enderbury'
            ],
            [
                'id'      => 398,
                'display' => 'Pacific/Fiji'
            ],
            [
                'id'      => 399,
                'display' => 'Pacific/Galapagos'
            ],
            [
                'id'      => 400,
                'display' => 'Pacific/Gambier'
            ],
            [
                'id'      => 401,
                'display' => 'Pacific/Guadalcanal'
            ],
            [
                'id'      => 402,
                'display' => 'Pacific/Guam'
            ],
            [
                'id'      => 403,
                'display' => 'Pacific/Honolulu'
            ],
            [
                'id'      => 404,
                'display' => 'Pacific/Kiritimati'
            ],
            [
                'id'      => 405,
                'display' => 'Pacific/Kosrae'
            ],
            [
                'id'      => 406,
                'display' => 'Pacific/Kwajalein'
            ],
            [
                'id'      => 407,
                'display' => 'Pacific/Majuro'
            ],
            [
                'id'      => 408,
                'display' => 'Pacific/Marquesas'
            ],
            [
                'id'      => 409,
                'display' => 'Pacific/Midway'
            ],
            [
                'id'      => 410,
                'display' => 'Pacific/Nauru'
            ],
            [
                'id'      => 411,
                'display' => 'Pacific/Niue'
            ],
            [
                'id'      => 412,
                'display' => 'Pacific/Norfolk'
            ],
            [
                'id'      => 413,
                'display' => 'Pacific/Noumea'
            ],
            [
                'id'      => 414,
                'display' => 'Pacific/Pago_Pago'
            ],
            [
                'id'      => 415,
                'display' => 'Pacific/Pitcairn'
            ],
            [
                'id'      => 416,
                'display' => 'Pacific/Rarotonga'
            ],
            [
                'id'      => 417,
                'display' => 'Pacific/Saipan'
            ],
            [
                'id'      => 418,
                'display' => 'Pacific/Samoa'
            ],
            [
                'id'      => 419,
                'display' => 'Pacific/Tahiti'
            ],
            [
                'id'      => 420,
                'display' => 'Pacific/Tongatapu'
            ]
        ];
    }

    public static function convertSecondsToDatetime($seconds): DateTime
    {
        return new DateTime("@$seconds");
    }
}
