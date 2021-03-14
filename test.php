<?php
function total_price($price_usd, $needed_currencies='usd,rub,byn') {

$total_dm_result = '';

function num2word($num = 0, $words = array())
{
    $num     = (int) $num;
    $cases   = array(2, 0, 1, 1, 1, 2);
    return $words[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
}

function splitter($val)
{
    $str = (string) $val ;
    $splitted = explode(".",$str);
    $whole = (integer)$splitted[0] ;
    $num = (integer) $splitted[1];
    return array($whole,$num);
}

function sum_to_text($float_sum, $currency='byn') {

    $cur_dict = [
        'rub' => [['рубль РФ', 'рубля РФ', 'рублей РФ'],['копейка', 'копейки', 'копеек']],
        'byn' => [['рубль РБ', 'рубля РБ', 'рублей РБ'],['копейка', 'копейки', 'копеек']],
        'usd' => [['доллар', 'доллара', 'долларов'],['цент', 'цента', 'центов']],
    ];

    $flags_dict = [
        'rub' => '🇷🇺',
        'byn' => '🇺🇸',
        'usb' => '🇧🇾'
    ];

    $cur = (isset($cur_dict[$currency])) ? $cur_dict[$currency] : $cur_dict['rub'];
    $flag = (isset($flags_dict[$currency])) ? $flags_dict[$currency] : $flags_dict['rub'];

    $result = '';

    $splited_sum_array = splitter($float_sum);
    foreach($splited_sum_array as $key=>$value) {
        if ($value <= 0) {
            break;
        }
        $propis = num2word($value, $cur[$key]);
        $result = $result . "{$value} {$propis} ";
    }
    $result = trim($result) . " " . $flag;
    return $result;
}

$cur_arr = explode(",", $needed_currencies);
$last_element = end($cur_arr);

foreach($cur_arr as $cur) {
    $price = $price_usd;
    $price_propis = sum_to_text($price, strtolower(trim($cur)));
    $sep = ($cur == $last_element) ? "." : ", ";
    $total_dm_result = $total_dm_result . "{$price_propis}{$sep}";
}

return trim($total_dm_result);
}


function get_unique_years($array_of_arrays) {
    foreach($array_of_arrays as $single_array) {
        $result_array = array_merge($result_array, $single_array);
    }
    $result_array = array_unique($result_array);
    sort($result_array);
    return $result_array;
}

$test_ar = array(
    array('year' => '2011', 'price' => '0.86'),
    array('year' => '2012', 'price' => '1.55')
);



function seek_by_year($arr_of_data, $opts=[]) {
    
    /* Defaults */
    $seek_options = array(
        's_key' => 'year',
        's_value' => '2011',
        'v_key' => 'price'
    );

    if (!is_array($arr_of_data)) {
        return 0;
    }

    if(isset($opts)) {
        $seek_options = array_replace($seek_options, $opts);
    }


    foreach($arr_of_data as $arr) {
        if ($arr[$seek_options['s_key']] == $seek_options['s_value']) {
            return $arr[$seek_options['v_key']];
        }
    }

    return 0;
}

var_dump(
    seek_by_year(
        $test_ar, ['s_value'=>'2011']
    )
);
?>