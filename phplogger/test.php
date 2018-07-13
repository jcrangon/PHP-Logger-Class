<?php
session_start();

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

// creating a superglobal containing the logger ref
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