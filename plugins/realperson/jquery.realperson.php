<?php
function rpHash($value) {
    $hash = 5381;
    $value = strtoupper($value);
    for($i = 0; $i < strlen($value); $i++) {
        $hash = (leftShift32($hash, 5) + $hash) + ord(substr($value, $i));
    }
    return $hash; }

function leftShift32($number, $steps) {
    $binary = decbin($number);
    $binary = str_pad($binary, 32, "0", STR_PAD_LEFT);
    $binary = $binary.str_repeat("0", $steps);
    $binary = substr($binary, strlen($binary) - 32);
    return ($binary{0} == "0" ? bindec($binary) :
        -(pow(2, 31) - bindec(substr($binary, 1)))); 
}