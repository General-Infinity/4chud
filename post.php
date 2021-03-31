<?php
$postName = htmlspecialchars($_POST["name"]);
$postOptions = htmlspecialchars($_POST["options"]);
if(isset($_POST['file'])) {
    $postFile = $_POST['file'];
}
$ip = $_SERVER['REMOTE_ADDR'];
$postText = htmlspecialchars($_POST["message"]);
$postTemplate = file_get_contents("template.html");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "ip-api.com/json"); ///$ip
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$info = curl_exec($ch);
curl_close($ch);
$jsonip = json_decode($info);
$cc = $jsonip->{"countryCode"};
$cn = $jsonip->{"country"};
$cc .= ".gif";
$cc = strtolower($cc);
if(!$postName){
    $postName = "Anonymous";
}
if(!$postText){
    exit("You forgot to input text!");
}
if(preg_match("/&gt;./", $postText) == 1){
    $postHTML = str_replace("style=''","style='color: green;'", $postHTML);
}

$postHTML = str_replace("<_POSTNAME_>",$postName, $postTemplate);
$postHTML = str_replace("<_POSTTEXT_>",$postText, $postHTML);
//$postHTML = str_replace("<_POSTNUM_>",$postNum, $postHTML);
$postHTML = str_replace("<_POSTDATE_>",date("Y/m/d g:i:s"), $postHTML);
$postHTML = str_replace("<_POSTFLAG_>","<img src='flags/$cc' title='$cn'>", $postHTML);
file_put_contents("messages.html", $postHTML . file_get_contents("messages.html"));
echo("Message sent!");