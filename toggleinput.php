#!/bin/env php
<?php

$input = "AT Translated Set 2 keyboard";
//$input = "Logitech MX Ergo";
$homedir= getenv("HOME");

$configfile = "$homedir/.config/toggleinput";
$debug=false;

//color in console
$_DEF ="\e[39m";
$_WHITE="\033[0;37m";
$_YELL="\033[0;33m";
$_RED ="\033[0;31m";


function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


exec("xinput",$results);
 


if( $debug ) echo "<textarea style='width:90%; height:80%;'>";
 
foreach($results as $result )
{
    $found = strpos($result, $input);
    if($found > 0)
    {
        //EXEMPLE : Logitech MX Ergo                        	id=17	[slave  pointer  (2)]
        $parts = explode("id=",$result);
        $right = $parts[1];
        $parts = explode("[slave",$right);
        $id = trim($parts[0]);
        $slave = $parts[1];
        $master = get_string_between($slave, "(", ")"); //get the master number
        // echo $result ."\n";
        // echo "id = "; var_dump( $id );
        // echo "master = "; var_dump( $master);
        $state = null;        
        if( $master ) $state = "off";

        $content = file_get_contents($configfile);

        if( $content===false)  //file is not found !
        {
            $content = "$id;$master;off";
            $success = file_put_contents($configfile,$content);               
            echo "$_YELL";
            print "saved as off in file '$configfile'\n";
            echo "$_DEF"; 
        }
        
            //here, contents is ok with something like: 23;3;off
          $parts = explode(";", $content );
          $id = $parts[0];
          $master = $parts[1];
          $state = strtolower( $parts[2]);
         
          //Toggle state for the device
            $oldstate = $state;
            if($state==="off") $state="on";
            else if($state==="on") $state="off";

          $content = "$id;$master;$state"; //to be stored in conf file
          //var_dump($master); die("debug");
          if($state==="off") //TURN OFF the device
          {
            echo "$_YELL";          
            print "float device '$input' ...\nxinput float $id\n";
            exec("xinput float $id",$output);
            echo "$_DEF"; 
          }else if($state==="on")//TURN on the device
          {   
                echo "$_YELL";
                echo "reattach device '$input' ... \nxinput reattach $id $master\n";            
                echo "$_DEF";
                exec("xinput reattach $id $master",$output);                             
          }

            print "saved to '$configfile'\n";
          $success = file_put_contents($configfile,$content);        
       
    }//endif


}//next
if( $debug )  echo "</textarea>";

?>