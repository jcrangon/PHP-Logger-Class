# PHP-Logger-Class
A PHP Class allowing logging of the entire context
```PHP
<?php
session_start();

// function that will be used to test
// the capacity of the logger to be accessed from inside
// individual functions of the context
function testloggerinfunct(){
	if(isset($GLOBALS["loggerref"])){$logger=$GLOBALS["loggerref"];$log=true;}else{$log=false;}
	// Function code ...
	// adapt the followinw line to add a line in the log file:
	if($log){$logger->add("In ze function","",__FILE__,__LINE__);}

}

// includes :
require("./phplogger.php");


// instanciation :
$logger= new phplogger("./",1); // param: path/to/phplogger/folder, verbosity level 1-3

// creating a global containing the logger ref
// allows using the object inside individual functions
// called by the current script
$GLOBALS["loggerref"]=&$logger;

// log activation :
$logger->activate();


// method start() :
$logger->start(__FILE__);

// method add()
$boolean=true;
$logger->add("Value of \$boolean ",$boolean,__FILE__,__LINE__);

$phrase="il etait un petit navire...";
$logger->add("\$phrase",$phrase,__FILE__,__LINE__);

// logging inside an individual function
$logger->add("Calling function testloggerinfunct()","",__FILE__,__LINE__);
testloggerinfunct();
$logger->add("Back from testloggerinfunct()","",__FILE__,__LINE__);

// displaying arrays
$tab=array(1,2,3,4,5,6,7,8,9,10);
unset($tab[5]);

$logger->add("\$tab",$tab,__FILE__,__LINE__);


// end of logging for this script :
$logger->stop(__FILE__);

// get log.txt raw content
$txt_content=$logger->txtlog_txt_content();

// get log.txt PHP content
$html_content=$logger->txtlog_html_content();
echo "Content of log.txt: <br/>";
echo $html_content;

// clear log.txt
$logger->txtlog_clear();

// log deactivation :
$logger->kill();

// RAW ram log content :
$ram_log=$logger->getLog_content();

// HTML ram log content :
$php_ram_log=$logger->getHTMLlog_content();
echo "contenu dela RAM log : <br/>";
echo $php_ram_log;
?>
```
RESULTS :
Content of log.txt: 

.............. Starting Log for .../Debug/class/phplogger/test.php
Value of $boolean  = TRUE
$phrase = il etait un petit navire...
Calling function testloggerinfunct() = 
In ze function = 
Back from testloggerinfunct() = 
$tab = Array
(
    [0] => 1
    [1] => 2
    [2] => 3
    [3] => 4
    [4] => 5
    [6] => 7
    [7] => 8
    [8] => 9
    [9] => 10
)

.............. End of log for .../Debug/class/phplogger/test.php
contenu dela RAM log : 

*** Class Inner Logger Activated ***
----- Global Active Activated   =
----- PHP ERR REPORTING Activated =
----- Txtlog := .............. Starting Log for .../Debug/class/phplogger/test.php
----- Txtlog := Value of $boolean  = TRUE
----- Txtlog := $phrase = il etait un petit navire...
----- Txtlog := Calling function testloggerinfunct() = 
----- Txtlog := In ze function = 
----- Txtlog := Back from testloggerinfunct() = 
----- Txtlog := $tab = Array
(
    [0] => 1
    [1] => 2
    [2] => 3
    [3] => 4
    [4] => 5
    [6] => 7
    [7] => 8
    [8] => 9
    [9] => 10
)

----- Txtlog := .............. End of log for .../Debug/class/phplogger/test.php
----- Txtlog Text Content Sent =
----- Txtlog HTML Content Sent =
----- Txtlog cleared =
*** Class Inner Logger Deactivated ***
----- PHP ERR REPORTING Deactivated =
----- Global Active Deactivated  =
----- $GLOBALS['loggerref'] has been destroyed =
