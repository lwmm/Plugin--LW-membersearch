<?php 
require_once 'PHPUnit2/Framework/TestSuite.php';
require_once dirname(__FILE__) . "/Domain/Member/Model/memberCommandHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Member/Model/memberQueryHandlerTest.php";

$testClassNames = array(
    "memberCommandHandlerTest",
    "memberQueryHandlerTest"
    );

foreach ($testClassNames as $test) {
    $phpunit = new PHPUnit2_Framework_TestSuite($test);
    $phpunit->run();
}