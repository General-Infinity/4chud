<?php
//require 'lib/geoip2.phar';
//use GeoIp2\Database\Reader;
$memeflag=false;
$dst="flags";
$postText = htmlspecialchars($_POST["message"]);
if(!$postText){
    exit("You forgot to input text!");
}
$postName = htmlspecialchars($_POST["name"]);
$postFlag = htmlspecialchars($_POST["flag"]);
$postNum = file_get_contents("counter.txt");
$postOptions = htmlspecialchars($_POST["options"]);
$fu = false;
if(isset($_FILES['image'])){
    $errors= array();
    $file_name = $_FILES['image']['name'];
    $file_size =$_FILES['image']['size'];
    $file_tmp =$_FILES['image']['tmp_name'];
    $file_type=$_FILES['image']['type'];
    $tmp = explode('.', $file_name);
    $file_ext = end($tmp);
    $extensions= array("jpeg","jpg","png", "webm", "gif", "");

    if(in_array($file_ext,$extensions)=== false){
        $error="extension not allowed, please choose a JPEG or PNG file.\n";
    }

    if($file_size > 2097152){
        $error="File size must not be above 2MB \n";
    }
    
    if(empty($error)==true && $file_ext != ""){
        $fu = true;
        file_put_contents("counter.txt", $postNum+1);
        $postNum = file_get_contents("counter.txt");
        move_uploaded_file($file_tmp,"images/".$postNum.".".$file_ext);
        echo "File uploaded!\n";
    }elseif(empty($error)==false){
        print($error);
    }
}
$ip = $_SERVER['REMOTE_ADDR'];
$postTemplate = file_get_contents("template.html");
$ast="memeflags/".$postFlag.".";
/*
if (file_exists($ast."gif") && file_exists($ast."txt")){
    $dst = "memeflags";
    $cc = $postFlag.".gif";
    $cn = file_get_contents($ast."txt");
}else{
    if ($ip != "::1") {
        $reader = new Reader('lib/GeoLite2-Country.mmdb');
        $record = $reader->country($ip);
        $cc = $record->country->isoCode;
        $cn = $record->country->name;
        $dst = "flags";
        $cc .= ".gif";
        $cc = strtolower($cc);
    }else {
        $cc = "lh.gif";
        $cn = "Localhost";
    }
}
*/
if(!$postName) $postName = "Anonymous";

file_put_contents("counter.txt", $postNum+1);
$postHTML = str_replace("<_POSTNAME_>",$postName, $postTemplate);
$postHTML = str_replace("<_POSTTEXT_>",$postText, $postHTML);
$postHTML = str_replace("<_POSTNUM_>",$postNum, $postHTML);
$postHTML = str_replace("<_POSTDATE_>",date("Y/m/d g:i:s"), $postHTML);
$postHTML = str_replace("<_POSTFLAG_>","<img src='$dst/$cc' title='$cn'>", $postHTML);
if ($fu==true){
    $wow = $postNum.".".$file_ext;
    $st = '<br><img width="100px" height="100px" style="float: left;" src="images/thingtochange"><br><br><br><br>';
    $st = str_replace("thingtochange", $wow, $st);
    $postHTML = str_replace("<!--POSTIMAGE-->",$st, $postHTML);
}
if(preg_match("/&gt;.*\n/", $postText) == 1){
    preg_match_all("/&gt;.*\n/", $postText, $matches);
    for($i=0; $i<=count($matches); $i++){
        $allofgreen=$matches[0][$i];
        $postHTML = str_replace($allofgreen,"<p style='color: green;'>$allofgreen</p>", $postHTML);
    }
}
file_put_contents("messages.html", $postHTML . file_get_contents("messages.html"));
echo("Message sent!");