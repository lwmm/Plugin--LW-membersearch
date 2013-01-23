<?php 
require_once 'PHPUnit2/Framework/TestSuite.php';
require_once dirname(__FILE__) . "/Domain/Member/Model/memberCommandHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Member/Model/memberQueryHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/Member/Specification/isValid.php";
require_once dirname(__FILE__) . "/Domain/FB/Model/CommandHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/FB/Model/QueryHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/FB/Specification/isValid.php";
require_once dirname(__FILE__) . "/Domain/GB/Model/CommandHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/GB/Model/QueryHandlerTest.php";
require_once dirname(__FILE__) . "/Domain/GB/Specification/isValid.php";

$testClassNames = array(
    "memberCommandHandlerTest",
    "memberQueryHandlerTest",
    "isValidTest_member",
    "CommandHandlerTest_fb",
    "QueryHandlerTest_fb",
    "isValidTest_fb",
    "CommandHandlerTest_gb",
    "QueryHandlerTest_gb",
    "isValidTest_gb"
    );

foreach ($testClassNames as $test) {
    $phpunit = new PHPUnit2_Framework_TestSuite($test);
    $phpunit->run();
}