<?php

/**
 * @param $a
 * @param $b
 * @return string
 */
function lsum($a, $b) {
    $result = '';
    $lena = strlen($a);
    $lenb = strlen($b);
    $lenMax = max($lena, $lenb);

    if($lena > $lenb) {
        $b = str_repeat('0', $lena - $lenb) . $b;
    } else if($lena < $lenb) {
        $a = str_repeat('0', $lenb - $lena) . $a;
    }

    $last = 0;
    for($i = $lenMax - 1; $i >= 0; $i --) {
        $ia = intval($a[$i]);
        $ib = intval($b[$i]);
        $n = $ia + $ib + $last;
        $result =  ($n % 10) . $result;
        $last = ($n -  ($n % 10)) > 0 ? 1 : 0;
    }
    if($last > 0) {
        $result = '1' . $result;
    }

    return $result;
}


echo "Test ---- \n";
$r = lsum('2', '2');
if( $r == '4') {
    echo "OK\n";
} else {
    echo "Fail({ $r})\n";
}

$r = lsum('20', '2');
if($r == '22') {
    echo "OK\n";
} else {
    echo "Fail({ $r})\n";
}

$r = lsum('20', '55') ;
if($r == '75') {
    echo "OK\n";
} else {
    echo "Fail({ $r})\n";
}

$r = lsum('20', '192');
if($r == '212') {
    echo "OK\n";
} else {
    echo "Fail({ $r})\n";
}

$r = lsum('2009212001', '2009212001');
if($r == '4018424002') {
    echo "OK\n";
} else {
    echo "Fail({ $r})\n";
}

$r = lsum('999999999999999999999999', '1');
if($r == '1000000000000000000000000') {
    echo "OK\n";
} else {
    echo "Fail({ $r})\n";
}

$r = lsum('1000000000000000000000000', '11');
if($r == '1000000000000000000000011') {
    echo "OK\n";
} else {
    echo "Fail({ $r})\n";
}

