<?php

namespace App\Helpers;

use Request;
use App\Models\Currency as CurrencyDB;
use App\Models\OrderCurrency;
//use Illuminate\Http\Request;

class Currency
{
    // convert currency value
    public static function convert($from, $to, $amount, $decimal_value = 2)
    {
        return $converted_amount;
    }

    // show default currency amount
    public static function default($amount, $options = [])
    {
        $amount2 = $amount;

        $default_currency = config('global.currency');
        if(isset($options['currency'])){
            $currency = $options['currency'];
        }else{
            $currency = Request::session()->get('currency');
        }
        $currency_data = CurrencyDB::where(['currency' => $currency])->first();
        if($currency_data !== null){
            /*
            $amount = number_format($amount * $currency_data->value, $options['number_format']);
            $currency_code = $currency_data->symbol;
            $amount2 = number_format($amount2 * $currency_data->value, $options['number_format']);
            */

            $amount         = $amount * $currency_data->value;
            $currency_code  = $currency_data->symbol;
            $amount2        = $amount2 * $currency_data->value;
        }

        if($currency === null){
            $currency = $default_currency;
            // $currency_code = '<i class="fas fa-dollar-sign"></i>';
            $currency_code = 'USD';
        }

        if(!isset($options['number_format'])){
            $options['number_format'] = 2;
        }

        //echo '<pre>'; echo "--->".$amount; echo "--->".$amount2; print_r($options);

        // decimal value, default currency, need currency, currency placement
        if(isset($options['need_currency']) && $options['need_currency'] == true) {
            if($options['number_format'] != false){
                //$amount = number_format($amount, $options['number_format']);
                if(Request::session()->has('currency') || isset($options['currency'])) {
                    if(!isset($options['currency'])){
                        if(Request::session()->get('currency') == config('global.currency')) {
                            $amount = $amount;
                        }
                        else{
                            $amount = number_format($amount, $options['number_format']);
                        }
                    }else{
                        if($options['currency'] == config('global.currency')) {
                            $amount = $amount;
                        }
                        else{
                            $amount = number_format($amount, $options['number_format']);
                        }
                    }
                }
                else{
                    $amount = $amount;
                }
            }

            if(isset($options['currency_place'])){
                switch($options['currency_place']){
                    case 'left' :
                            $final_amount = $currency_code.$amount;
                            break;
                    case 'right' :
                            $final_amount = $amount.' '.$currency_code;
                            break;
                    default :
                            $final_amount = $currency_code.' '.$amount;
                }
            }else{
                $final_amount = $currency_code.' '.$amount;
            }
        }
        else{
            if($options['number_format'] != false){
                if(Request::session()->has('currency') || isset($options['currency'])) {
                    if(!isset($options['currency'])){
                        if(Request::session()->get('currency') == config('global.currency')) {
                            $final_amount = $amount2;
                        }
                        else{
                            $final_amount = number_format($amount2, $options['number_format']);
                        }
                    }else{
                        if($options['currency'] == config('global.currency')) {
                            $final_amount = $amount2;
                        }
                        else{
                            $final_amount = number_format($amount2, $options['number_format']);
                        }
                    }
                }
                else{
                    $final_amount = $amount2;
                }
                //$final_amount = number_format($amount2, $options['number_format']);
                //$final_amount = number_format($amount2, 3);
            }else{
                $final_amount = $amount2;
            }
        }
        return $final_amount;
    }

    // show default currency amount
    public static function default_symbol()
    {
        // return '<i class="fas fa-dollar-sign"></i>';
        return 'USD';
    }

    // show default currency amount
    public static function selected_currency()
    {
        $default_currency = config('global.currency');
        $currency = Request::session()->get('currency');
        $currency_data = CurrencyDB::where(['currency' => $currency])->first();
        if($currency_data !== null){
            $currency_code = $currency_data->symbol;
        }
        if($currency === null){
            // $currency_code = '<i class="fas fa-dollar-sign"></i>';
            $currency_code = 'USD';
        }
        return $currency_code;
    }

    // show order currency amount
    public static function orderCurrency($amount, $my_order_id = 0, $options = []) {
        $amount2 = $amount;
        $without_amount = $amount;

        $default_currency = config('global.currency');
        $currency = Request::session()->get('currency');
        $currency_data = CurrencyDB::where(['currency' => $currency])->first();
        if($currency_data !== null){
            /*
            $amount = number_format($amount * $currency_data->value, $options['number_format']);
            $currency_code = $currency_data->symbol;
            $amount2 = number_format($amount2 * $currency_data->value, $options['number_format']);
            */

            $amount         = $amount * $currency_data->value;
            $currency_code  = $currency_data->symbol;
            $amount2        = $amount2 * $currency_data->value;
        }

        if($currency === null){
            $currency = $default_currency;
            // $currency_code = '<i class="fas fa-dollar-sign"></i>';
            $currency_code = 'USD';
        }

        if(!isset($options['number_format'])){
            $options['number_format'] = 2;
        }

        //echo '<pre>'; echo "--->".$amount; echo "--->".$amount2; print_r($options);

        // decimal value, default currency, need currency, currency placement
        if(isset($options['need_currency']) && $options['need_currency'] == true) {
            if($options['number_format'] != false){
                //$amount = number_format($amount, $options['number_format']);
                if(Request::session()->has('currency')) {
                    if(Request::session()->get('currency') == config('global.currency')) {
                        $amount = $amount;
                    }
                    else{
                        $amount = number_format($amount, $options['number_format']);
                    }
                }
                else{
                    $amount = $amount;
                }
            }

            if(isset($options['currency_place'])){
                switch($options['currency_place']){
                    case 'left' :
                            $final_amount = $currency_code.$amount;
                            break;
                    case 'right' :
                            $final_amount = $amount.' '.$currency_code;
                            break;
                    default :
                            $final_amount = $currency_code.' '.$amount;
                }
            }else{
                $final_amount = $currency_code.' '.$amount;
            }
        }
        else{
            if($options['number_format'] != false){
                if(Request::session()->has('currency')) {
                    if(Request::session()->get('currency') == config('global.currency')) {
                        $final_amount = $amount2;
                    }
                    else{
                        $final_amount = number_format($amount2, $options['number_format']);
                    }
                }
                else{
                    $final_amount = $amount2;
                }
                //$final_amount = number_format($amount2, $options['number_format']);
                //$final_amount = number_format($amount2, 3);
            }else{
                $final_amount = $amount2;
            }
        }
        return $final_amount;
    }


    //ADMIN MANUAL order currency amount
    public static function manualOrderCurrency($amount, $currency_id = 3, $options = []) {
        $amount2 = $amount;

        $default_currency = config('global.currency');
        $currency         = $default_currency;

        $currency_data    = CurrencyDB::where(['id' => $currency_id])->first();
        if($currency_data !== null){
            $amount         = $amount * $currency_data->value;
            $currency_code  = $currency_data->currency;
            $amount2        = $amount2 * $currency_data->value;
        }

        if($currency === null){
            $currency       = $default_currency;
            $currency_code  = config('global.currency');
        }

        if(!isset($options['number_format'])){
            $options['number_format'] = 0;
        }

        //echo '<pre>'; echo "--->".$amount; echo "--->".$amount2; print_r($options);

        // decimal value, default currency, need currency, currency placement
        if(isset($options['need_currency']) && $options['need_currency'] == true) {
            if($options['number_format'] != false) {
                if($currency_data->currency == config('global.currency')) {
                    $amount = $amount;
                }else{
                    $amount = number_format($amount, $options['number_format']);
                }
                $final_amount = $amount;
            }

            if(isset($options['currency_place'])){
                switch($options['currency_place']){
                    case 'left' :
                            $final_amount = $currency_code.$amount;
                            break;
                    case 'right' :
                            $final_amount = $amount.' '.$currency_code;
                            break;
                    default :
                            $final_amount = $currency_code.' '.$amount;
                }
            }else{
                $final_amount = $currency_code.' '.$amount;
            }
        }
        else{
            if($options['number_format'] != false){
                if($currency_data->currency == config('global.currency')) {
                    $final_amount = $amount2;
                }else{
                    $final_amount = number_format($amount2, $options['number_format']);
                }
            }else{
                $final_amount = $amount2;
            }
            $final_amount = $currency_code.' '.$final_amount;
        }
        return $final_amount;
    }


}