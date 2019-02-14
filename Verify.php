<?php
echo "...................";

$string1 = file_get_contents("https://github.com/Jutha1234/phpBot/cus.json");
echo $string1;

$string = file_get_contents("/cus.json");
echo $string;
$json_a = json_decode($string, true);

$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json_a, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);


//$data = '' ;
foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {
        echo "$key:\n";
    } else {
		//$data = $key  ;
        echo "$key => $val\n";
    }
}

