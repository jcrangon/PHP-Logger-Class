<?php
session_start();

// function that will be used to test
// the capacity of the logger to be accessed from inside
// individual functions of the context
function testloggerinfunct(){
	if(isset($GLOBALS["loggerref"])){$logger=$GLOBALS["loggerref"];$log=true;}else{$log=false;}
	// Function code ...
	// adapt the followinw line to add a line in the log file:
	if($log){$logger->info("In ze function","",__FILE__,__LINE__);}

}

// includes :
require("./phplogger.php");


// instanciation :
$logger= new phplogger("./",1); // param: path/to/phplogger/folder, verbosity level 1-3


// creating a global containing the logger ref
// allows using the object inside individual functions
// called by the current script
$GLOBALS["loggerref"]=&$logger;


// log activation - PHP generated error will be logged from this point:
$logger->activate();



// method start() :
$logger->start(__FILE__);



// method add(), info(), warning(), error(), fatal()
$logger->add("just adding a line in the log ","",__FILE__,__LINE__);

$boolean=true;
$logger->info("Value of \$boolean ",$boolean,__FILE__,__LINE__);

$phrase="il etait un petit navire...";
$logger->warning("\$phrase",$phrase,__FILE__,__LINE__);




// logging inside an individual function
$logger->error("Calling function testloggerinfunct()","",__FILE__,__LINE__);
testloggerinfunct();
$logger->fatal("Back from testloggerinfunct()","",__FILE__,__LINE__);




// displaying arrays
$tab=array(1,2,3,4,5,6,7,8,9,10);
unset($tab[5]);

$logger->info("\$tab",$tab,__FILE__,__LINE__);




// end of logging for this script :
$logger->stop(__FILE__);



// get log.txt raw content
$txt_content=$logger->txtlog_txt_content();



// get log.txt PHP content
$html_content=$logger->txtlog_html_content();
echo "Content of log.txt: <br/>";
echo"<div style='padding:3px;background-color:#171715; word-wrap: break-word;'>";
echo $html_content;
echo"</div>";



// clear log.txt
$logger->txtlog_clear();



// colors management
// GET colors in use :
$tabcolors_in_use=$logger->getColors_in_use();
echo "<PRE>".print_r($tabcolors_in_use,true)."</PRE>";



//set colors
$tab_use_these_colors=array(
		"TXT"=>"#FF0000",
		"RAMLOGCOLOR"=>"rgba(255,0,0,1)"
	);
$logger->setColors($tab_use_these_colors);



// check the changes
$tabcolors_in_use=$logger->getColors_in_use();
echo "<PRE>".print_r($tabcolors_in_use,true)."</PRE>";



// log deactivation :
$logger->kill();



// RAW ram log content :
$ram_log=$logger->getLog_content();



// HTML ram log content :
$php_ram_log=$logger->getHTMLlog_content();
echo "Content of RAM log : <br/>";
echo"<div style='padding:3px;background-color:#171715; word-wrap: break-word;'>";
echo $php_ram_log;
echo"</div>";
?>
