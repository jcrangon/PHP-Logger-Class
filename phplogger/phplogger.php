<?php
class phplogger{
	
	private $_pathtologfolder;
	private $_logfile;
	private $_phplogfile;
	private $_active;
	private $_globalactive;
	private $_verbosity;
	private $_log_content;
	private $_colors;
	private $_debug_color;
	private $_info_color;
	private $_warning_color;
	private $_error_color;
	private $_fatal_color;
	private $_txt_color;
	private $_bkgd_color;
	private $_ramlog_color;
	
	const DEBUG="#75ef40";
	const INFO="#4045ed";
	const WARNING="#f2ee1d";
	const ERROR="#ef8a07";
	const FATAL="#ea2020";
	const TXT="#cec6c6";
	const BKGD="#171715";
	const RAMLOGCOLOR="cec6c6";
	
	public function __construct($path,$verbose){
		$this->setPathtofolder($path);
		$this->setLogfile($this->_pathtologfolder);
		$this->setVerbosity($verbose);
		$this->setColortab();
		$this->setColors($this->_colors);
		$this->init_log_content();
	}
	
	
	// initers
	protected function init_log_content(){
		$this->_log_content="";
	}
	
	
	// setters
	protected function setColortab(){
		$this->_colors=array(
				"DEBUG" => SELF::DEBUG,
				"INFO" => SELF::INFO,
				"WARNING" => SELF::WARNING,
				"ERROR" => SELF::ERROR,
				"FATAL" => SELF::FATAL,
				"TXT" => SELF::TXT,
				"BKGD" => SELF::BKGD,
				"RAMLOGCOLOR" => SELF::RAMLOGCOLOR
			);
	}
	
	public function setColors(array $tab){
		foreach($tab as $k=>$v){
			switch($k){
				case "DEBUG":
					$this->_debug_color=$v;
					$this->_colors["DEBUG"]=$v;
				break;
				case "INFO":
					$this->_info_color=$v;
					$this->_colors["INFO"]=$v;
				break;
				case "WARNING":
					$this->_warning_color=$v;
					$this->_colors["WARNING"]=$v;
				break;
				case "ERROR":
					$this->_error_color=$v;
					$this->_colors["ERROR"]=$v;
				break;
				case "FATAL":
					$this->_fatal_color=$v;
					$this->_colors["FATAL"]=$v;
				break;
				case "TXT":
					$this->_txt_color=$v;
					$this->_colors["TXT"]=$v;
				break;
				case "BKGD":
					$this->_bkgd_color=$v;
					$this->_colors["BKGD"]=$v;
				break;
				case "RAMLOGCOLOR":
					$this->_ramlog_color=$v;
					$this->_colors["RAMLOGCOLOR"]=$v;
				break;
			}
		}
	}
	protected function setGlobalactive(){
		$this->checkSession();
		if($this->_active===1){
			$this->_globalactive=1;
			$this->addLog_content("----- Global Active Activated   =");
		}
		elseif($this->_active===0){
			$this->_globalactive=0;
			$this->addLog_content("----- Global Active Deactivated  =");
		}
		else{
			DIE ("phplogger: Cannot set Global Activation...");
		}
	}
	protected function setLogfile($pathtofolder){
		$this->_logfile=$pathtofolder.'log.txt';
	}
	protected function setPathtofolder($path){
		if(!is_string($path)){
			DIE ("phplogger: Wrong path to folder argument...");
		}
		$lastchar=substr($path, -1);
		if($lastchar!="/"){$path.="/";}
		if(!file_exists($path."log.txt")){
			if(!$fh=fopen($path."log.txt","w+")){
				DIE ("Directory Not Writeable - Check permissions...");
			}
			else{
				fclose($fh);
			}
		}
		if(!$fh=fopen($path."log.txt","a")){
			DIE ("Log file Not Writeable - Check permissions...");
		}
		else{
			fclose($fh);
		}
		$this->_pathtologfolder=$path;
	}
	
	public function setVerbosity($verbose){
		if($verbose<1 || $verbose>3){
			DIE ("phplogger: Wrong verbosity argument...");
		}
		$this->_verbosity=$verbose;
	}
	
	
	
	
	// getters
	public function getActivationState(){
		return $this->_active;
	}
	public function getVerbosityLevel(){
		return $this->_verbosity;
	}
	public function getLog_content(){
		return strip_tags($this->_log_content);
	}
	public function getHTMLlog_content(){
		$lg=$this->nl2br2($this->_log_content);
		return "<PRE><span style=\"color:".$this->_ramlog_color."\">".$lg."</span></PRE>";
	}
	public function getColors_in_use(){
		return $this->_colors;
	}
	
	
	
	
	// protected methods
	protected function addLog_content($logstmt){
		$logstmt=strip_tags(strval($logstmt))."\n";
		$this->_log_content.=$logstmt;
	}
	protected function clean_file_location($location){
		$location=rtrim(strval($location));
		$lastchar=substr($location, -1);
		if($lastchar=="/"){$location=substr($location,0,strlen($location)-1);}
		$tabloc=explode("/",$location);
		switch(true){
			case sizeof($tabloc)>=4:
				$loc=".../".$tabloc[sizeof($tabloc)-4]."/".$tabloc[sizeof($tabloc)-3]."/".$tabloc[sizeof($tabloc)-2]."/".$tabloc[sizeof($tabloc)-1];
			break;
			case sizeof($tabloc)>=3:
				$loc=".../".$tabloc[sizeof($tabloc)-3]."/".$tabloc[sizeof($tabloc)-2]."/".$tabloc[sizeof($tabloc)-1];
			break;
			case sizeof($tabloc)>=2:
				$loc=".../".$tabloc[sizeof($tabloc)-2]."/".$tabloc[sizeof($tabloc)-1];
			break;
			default:
				$loc=".../".$tabloc[sizeof($tabloc)-1];
		}
		return $loc;
	}
	protected function clearLog_content(){
		$this->_log_content="";
	}
	protected function checkSession(){
		$status=session_status();
		if($status!=2){ 
			DIE("Logger Conf Error: PHP Session Not Started!");
		}
	}
	protected function nl2br2($string) {
		$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
		return $string;
	}
	protected function txtlog_writeln($ln,$start=0){
		if(!$fh=fopen($this->_logfile,"a")){
			DIE ("Log file Not Writeable - Check permissions...");
		}
		else{
			$this->addLog_content("----- Txtlog := ".$ln);
			if($start===1){$ln="\n".$ln."\n";}else{$ln=$ln."\n";}
			fwrite($fh,$ln);
			fclose($fh);
		}
	}
	protected function mkline($desc,$var,$f,$l){
		$desc=strval($desc);
		if(is_bool($var)){
			if($var){
				$var="TRUE";
			}
			else{
				$var="FALSE";
			}
		}
		$var=print_r($var,true);
		$f=$this->clean_file_location($f);
		$l="Line ".$l;
		$line="";
		$now=date("d-m-Y H:i:s");
		$_SERVER["REMOTE_ADDR"] = array_key_exists( 'REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR']  : '127.0.0.1'; 
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
			$exploded= explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$ipAddress = array_pop($exploded);
		}
		switch($this->_verbosity){
			case 1:
				$line=$desc." = ".$var;;
			break;
			
			case 2:
				$line="wlog @ Line".$l.": \n".$desc." = ".$var;
				$line="[".$now."] -> ".$line;
			break;
			
			case 3:
				$line="wlog @ ".$f." Line ".$l." : \n".$desc." = ".$var;
				$line="[".$now."] -> [".$ipAddress."]- ".$line;
			break;
		}
		return $line;
	}
	
	// public methods
	public function activate($active=1){
		if($active!==1 && $active!==0){
			DIE ("phplogger: Wrong Activation argument...");
		}
		$this->_active=$active;
		switch($this->_active){
			case 1:
				$this->addLog_content("\n*** Class Inner Logger Activated ***");
				if(isset($GLOBALS["loggerref"])){
					$this->setGlobalactive();
				}
				ini_set('display_errors', 'Off');
				ini_set('log_errors', "On");
				ini_set('error_log', $this->_logfile);
				error_reporting(E_ALL);
				$this->addLog_content("----- PHP ERR REPORTING Activated =");
			break;
			
			case 0:
				$this->addLog_content("*** Class Inner Logger Deactivated ***");
				ini_set('display_errors', 'Off');
				ini_set('log_errors', "Off");
				error_reporting(0);
				$this->addLog_content("----- PHP ERR REPORTING Deactivated =");
				if(isset($GLOBALS["loggerref"])){
					$this->setGlobalactive();
					unset($GLOBALS["loggerref"]);
					$this->addLog_content("----- \$GLOBALS['loggerref'] has been destroyed =");
				}
			break;
		}
	}
	
	public function kill($active=0){
		if($active!==0){
			DIE ("phplogger: Wrong Deactivation argument...");
		}
		$this->_active=$active;
		$this->addLog_content("*** Class Inner Logger Deactivated ***");
		ini_set('display_errors', 'Off');
		ini_set('log_errors', "Off");
		error_reporting(0);
		$this->addLog_content("----- PHP ERR REPORTING Deactivated =");
		if(isset($GLOBALS["loggerref"])){
			$this->setGlobalactive();
			unset($GLOBALS["loggerref"]);
			$this->addLog_content("----- \$GLOBALS['loggerref'] has been destroyed =");
		}
	}
	
	public function txtlog_clear(){
		if($this->_active===1){
			if(!$fh=fopen($this->_logfile,"w")){
				DIE ("Log file Not Writeable - Check permissions...");
			}
			else{
				fclose($fh);
				$this->addLog_content("----- Txtlog cleared =");
			}
		}
	}
	
	public function txtlog_txt_content(){
		if($this->_active===1){
			if(!$fh=fopen($this->_logfile,"r")){
				DIE ("Log file Not Writeable - Check permissions...");
			}
			else{
				$this->addLog_content("----- Txtlog Text Content Sent =");
				return strip_tags(file_get_contents($this->_logfile));
			}
		}
	}
	
	public function txtlog_html_content(){
		if($this->_active===1){
			if(!$fh=fopen($this->_logfile,"r")){
				DIE ("Log file Not Writeable - Check permissions...");
			}
			else{
				$this->addLog_content("----- Txtlog HTML Content Sent =");
				return "<PRE><p style='color:".$this->_txt_color."'>".$this->nl2br2(file_get_contents($this->_logfile))."</p></PRE>";
			}
		}
	}

	public function start($location){
		if($this->_active===1){
			$location=$this->clean_file_location($location);
			$this->txtlog_writeln("<span style=\"color:".$this->_txt_color."\">[</span><span style=\"color:".$this->_debug_color.";\">DEBUG</span><span style=\"color:".$this->_txt_color."\">]   </span><span style=\"color:".$this->_txt_color."\"><b>.............. Starting Log for ".$location."</b></span>",1);
			
		}
	}
	
	public function stop($location){
		if($this->_active===1){
			$location=$this->clean_file_location($location);
			$this->txtlog_writeln("<span style=\"color:".$this->_txt_color."\">[</span><span style=\"color:".$this->_debug_color.";\">DEBUG</span><span style=\"color:".$this->_txt_color."\">]   </span><span style=\"color:".$this->_txt_color."\"><b>.............. End of Log for ".$location."</b></span>");
			
		}
	}
	
	public function add($desc,$var,$f,$l){
		if($this->_active===1){
			$ln=$this->mkline($desc,$var,$f,$l);
			$ln="<span style=\"color:".$this->_txt_color."\">".$ln."</span>";
			$this->txtlog_writeln($ln);
		}
	}
	
	public function info($desc,$var,$f,$l){
		if($this->_active===1){
			$ln=$this->mkline($desc,$var,$f,$l);
			$ln="<span style=\"color:".$this->_txt_color."\">[</span><span style=\"color:".$this->_info_color.";\">INFO</span><span style=\"color:".$this->_txt_color."\">]    </span><span style=\"color:".$this->_txt_color."\">".$ln."</span>";
			$this->txtlog_writeln($ln);
		}
	}

	public function warning($desc,$var,$f,$l){
		if($this->_active===1){
			$ln=$this->mkline($desc,$var,$f,$l);
			$ln="<span style=\"color:".$this->_txt_color."\">[</span><span style=\"color:".$this->_warning_color.";\">WARNING</span><span style=\"color:".$this->_txt_color."\">] </span><span style=\"color:".$this->_txt_color."\">".$ln."</span>";
			$this->txtlog_writeln($ln);
		}
	}

	public function error($desc,$var,$f,$l){
		if($this->_active===1){
			$ln=$this->mkline($desc,$var,$f,$l);
			$ln="<span style=\"color:".$this->_txt_color."\">[</span><span style=\"color:".$this->_error_color.";\">ERROR</span><span style=\"color:".$this->_txt_color."\">]   </span><span style=\"color:".$this->_txt_color."\">".$ln."</span>";
			$this->txtlog_writeln($ln);
		}
	}
	
	public function fatal($desc,$var,$f,$l){
		if($this->_active===1){
			$ln=$this->mkline($desc,$var,$f,$l);
			$ln="<span style=\"color:".$this->_txt_color."\">[</span><span style=\"color:".$this->_fatal_color.";\">FATAL</span><span style=\"color:".$this->_txt_color."\">]   </span><span style=\"color:".$this->_txt_color."\">".$ln."</span>";
			$this->txtlog_writeln($ln);
		}
	}

}
?>
