<?php
include('aligho.txt.php');

header('Content-Type: text/plain');

$testSignup = json_decode(fgets(fopen('../../alighoj.csv', 'r')), true);
echo renderAlighoEmailTxt($testSignup);
