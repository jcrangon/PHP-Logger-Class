# PHP-Logger-Class
A PHP Class allowing logging of the entire context
<PRE>
Version 1.5 includes monitor.php, a simple utility to monitor logs including self inner class logs
</PRE>
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
require("./autoloader/autoloader.php");

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
$logger->info("just adding a line in the log ","",__FILE__,__LINE__);

$boolean=true;
$logger->info("Value of \$boolean ",$boolean,__FILE__,__LINE__);

// get log.txt PHP content
$txtlog_html_content=$logger->txtlog_html_content();
echo "Content of log.txt: <br/>";
echo"<div style='padding:3px;background-color:#171715; word-wrap: break-word;'>";
echo $txtlog_html_content;
echo"</div>";

// get phperrlog.txt PHP content
$errlog_html_content=$logger->errlog_html_content();
echo "Content of phperrlog.txt: <br/>";
echo"<div style='padding:3px;background-color:#171715; word-wrap: break-word;'>";
echo $errlog_html_content;
echo"</div>";

// HTML ram log content :
$php_ram_log=$logger->getHTMLlog_content();
echo "Content of RAM log : <br/>";
echo"<div style='padding:3px;background-color:#171715; word-wrap: break-word;'>";
echo $php_ram_log;
echo"</div>";

// colors management - NOTE: Default colors have been chosen for best use with the monitor
// GET colors in use :
$tabcolors_in_use=$logger->getColors_in_use();
echo "<PRE>".print_r($tabcolors_in_use,true)."</PRE>";

//set ANY colors or keep the default
$tab_use_these_colors=array(
		"TXT"=>"#FF0000",
		"PHPTXT"=>"FFFF00",
		"RAMLOGCOLOR"=>"rgba(255,0,255,1)"
	);
$logger->setColors($tab_use_these_colors);

// check the changes
$tabcolors_in_use=$logger->getColors_in_use();
echo "<PRE>".print_r($tabcolors_in_use,true)."</PRE>";

$phrase="il etait un petit navire...";
$logger->warning("\$phrase",$phrase,__FILE__,__LINE__);

// creating a php notice: 
$my_undeclared_string.="coding is fun";

// creating a fatal error - uncomment to test
//fun2();

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

// get phperrlog.txt
$errlog_txt_content=$logger->errlog_txt_content();

// get log.txt PHP content
$txtlog_html_content=$logger->txtlog_html_content();
echo "Content of log.txt: <br/>";
echo"<div style='padding:3px;background-color:#171715; word-wrap: break-word;'>";
echo $txtlog_html_content;
echo"</div>";

// get phperrlog.txt PHP content
$errlog_html_content=$logger->errlog_html_content();
echo "Content of phperrlog.txt: <br/>";
echo"<div style='padding:3px;background-color:#171715; word-wrap: break-word;'>";
echo $errlog_html_content;
echo"</div>";

// clear log.txt
//$logger->txtlog_clear();


// log deactivation :
// use:
$logger->kill();
// or:
$logger->activate(0);

// RAW ram log content :
$ram_log=$logger->getLog_content();

// HTML ram log content :
$php_ram_log=$logger->getHTMLlog_content();

// can also be store in a session variable $_SESSION[**class name**_classloghtmlcontent]to be accessed
// by the monitor after the script has run -> navigate to ./monitor.php to check results
$logger->save_ramlog_to_session();

echo "Content of RAM log : <br/>";
echo"<div style='padding:3px;background-color:#171715; word-wrap: break-word;'>";
echo $php_ram_log;
echo"</div>";
?>
```
<pre>
Content of log.txt: 

[DEBUG] .............. Starting Log for .../class/phplogger/phplogger-V1.5/test.php
[INFO] just adding a line in the log = 
[INFO] Value of $boolean = TRUE

Content of phperrlog.txt: 

Content of RAM log : 
----- *** Inner log Initialisation *** 
----- *** Class name Initialisation *** 
----- Success Setting path to folder $path = ./
----- Setting txt log file = ./log.txt
----- Setting php error log file = ./phperrlog.txt
----- Loading verbosity level = 1
----- Setting fontsize = 14px
----- Setting font family = Times New Roman, Georgia, serif

*** Class Inner Logger Activated ***
----- checkSession() - Succes = 
----- Global Active Activated =
----- PHP ERR REPORTING Activated =
----- Txtlog := [DEBUG] .............. Starting Log for .../class/phplogger/phplogger-V1.5/test.php
----- Txtlog := [INFO] just adding a line in the log = 
----- Txtlog := [INFO] Value of $boolean = TRUE
----- Txtlog HTML Content Sent =
----- Errlog HTML Content Sent =
----- getHTMLlog_content() - HTML inner logger text sent = 
Array
(
    [DEBUG] => #75ef40
    [INFO] => #4045ed
    [WARNING] => #f2ee1d
    [ERROR] => #ef8a07
    [FATAL] => #ea2020
    [TXT] => #cec6c6
    [PHPTXT] => #268e26
    [BKGD] => #171715
    [RAMLOGCOLOR] => rgba(255,0,0,1)
)
Array
(
    [DEBUG] => #75ef40
    [INFO] => #4045ed
    [WARNING] => #f2ee1d
    [ERROR] => #ef8a07
    [FATAL] => #ea2020
    [TXT] => #FF0000
    [PHPTXT] => FFFF00
    [BKGD] => #171715
    [RAMLOGCOLOR] => rgba(255,0,255,1)
)
Content of log.txt: 

[DEBUG] .............. Starting Log for .../class/phplogger/phplogger-V1.5/test.php
[INFO] just adding a line in the log = 
[INFO] Value of $boolean = TRUE
[WARNING] $phrase = il etait un petit navire...
[ERROR] Calling function testloggerinfunct() = 
[INFO] In ze function = 
[FATAL] Back from testloggerinfunct() = 
[INFO] $tab = Array
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

[DEBUG] .............. End of Log for .../class/phplogger/phplogger-V1.5/test.php

Content of phperrlog.txt: 
[19-Jul-2018 23:37:19 Europe/Paris] PHP Notice: Undefined variable: my_undeclared_string in /var/www/html/devnetx-prod/Snipets/php/Debug/class/phplogger/phplogger-V1.5/test.php on line 80

Content of RAM log : 
----- *** Inner log Initialisation *** 
----- *** Class name Initialisation *** 
----- Success Setting path to folder $path = ./
----- Setting txt log file = ./log.txt
----- Setting php error log file = ./phperrlog.txt
----- Loading verbosity level = 1
----- Setting fontsize = 14px
----- Setting font family = Times New Roman, Georgia, serif

*** Class Inner Logger Activated ***
----- checkSession() - Succes = 
----- Global Active Activated =
----- PHP ERR REPORTING Activated =
----- Txtlog := [DEBUG] .............. Starting Log for .../class/phplogger/phplogger-V1.5/test.php
----- Txtlog := [INFO] just adding a line in the log = 
----- Txtlog := [INFO] Value of $boolean = TRUE
----- Txtlog HTML Content Sent =
----- Errlog HTML Content Sent =
----- getHTMLlog_content() - HTML inner logger text sent = 
----- getColors_in_use() - _colors table sent = Array
(
[DEBUG] => #75ef40
[INFO] => #4045ed
[WARNING] => #f2ee1d
[ERROR] => #ef8a07
[FATAL] => #ea2020
[TXT] => #cec6c6
[PHPTXT] => #268e26
[BKGD] => #171715
[RAMLOGCOLOR] => rgba(255,0,0,1)
)

----- Setting individual color variables - setColors() = Array
(
[DEBUG] => #75ef40
[INFO] => #4045ed
[WARNING] => #f2ee1d
[ERROR] => #ef8a07
[FATAL] => #ea2020
[TXT] => #FF0000
[PHPTXT] => FFFF00
[BKGD] => #171715
[RAMLOGCOLOR] => rgba(255,0,255,1)
)

----- getColors_in_use() - _colors table sent = Array
(
[DEBUG] => #75ef40
[INFO] => #4045ed
[WARNING] => #f2ee1d
[ERROR] => #ef8a07
[FATAL] => #ea2020
[TXT] => #FF0000
[PHPTXT] => FFFF00
[BKGD] => #171715
[RAMLOGCOLOR] => rgba(255,0,255,1)
)

----- Txtlog := [WARNING] $phrase = il etait un petit navire...
----- Txtlog := [ERROR] Calling function testloggerinfunct() = 
----- Txtlog := [INFO] In ze function = 
----- Txtlog := [FATAL] Back from testloggerinfunct() = 
----- Txtlog := [INFO] $tab = Array
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

----- Txtlog := [DEBUG] .............. End of Log for .../class/phplogger/phplogger-V1.5/test.php
----- Txtlog Text Content Sent =
----- Errlog Text Content Sent =
----- Txtlog HTML Content Sent =
----- Errlog HTML Content Sent =
*** Class Inner Logger Deactivated ***
----- PHP ERR REPORTING Deactivated =
----- checkSession() - Succes = 
----- Global Active Deactivated =
----- $GLOBALS['loggerref'] has been destroyed =
*** Class Inner Logger Deactivated ***
----- PHP ERR REPORTING Deactivated =
----- getLog_content() - Raw inner logger text sent = 
----- getHTMLlog_content() - HTML inner logger text sent = 
