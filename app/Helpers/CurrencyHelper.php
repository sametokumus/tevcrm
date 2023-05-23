<?php

namespace App\Helpers;

use App\Models\CurrencyLog;

class CurrencyHelper
{
    public static function ChangePrice($main_currency, $target_currency, $price, $eur_log, $usd_log, $gbp_log)
    {
        if ($target_currency == "TRY"){

            if ($main_currency == "TRY"){
                return $price;
            }else if ($main_currency == "EUR"){
                return $price * $eur_log;
            }else if ($main_currency == "USD"){
                return $price * $usd_log;
            }else if ($main_currency == "GBP"){
                return $price * $gbp_log;
            }

        }else if ($target_currency == "EUR"){

            if ($main_currency == "TRY"){
                return $price / $eur_log;
            }else if ($main_currency == "EUR"){
                return $price;
            }else if ($main_currency == "USD"){
                return $price * ($usd_log / $eur_log);
            }else if ($main_currency == "GBP"){
                return $price * ($gbp_log / $eur_log);
            }

        }else if ($target_currency == "USD"){

            if ($main_currency == "TRY"){
                return $price / $eur_log;
            }else if ($main_currency == "EUR"){
                return $price * ($eur_log / $usd_log);
            }else if ($main_currency == "USD"){
                return $price;
            }else if ($main_currency == "GBP"){
                return $price * ($gbp_log / $usd_log);
            }

        }else if ($target_currency == "GBP"){

            if ($main_currency == "TRY"){
                return $price / $eur_log;
            }else if ($main_currency == "EUR"){
                return $price * ($eur_log / $gbp_log);
            }else if ($main_currency == "USD"){
                return $price * ($usd_log / $gbp_log);
            }else if ($main_currency == "GBP"){
                return $price;
            }

        }
    }

}
