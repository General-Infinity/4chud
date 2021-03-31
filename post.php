<?php
$postName = htmlspecialchars($_POST["name"]);
if(!$postText){
    exit("You forgot to input text!");
}
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
$postText = htmlspecialchars($_POST["message"]);
$postTemplate = file_get_contents("template.html");
if($postFlag=="fk") {
    $cc = "fs.gif";
    $cn = "Forkiestani";
}elseif($postFlag=="soy"){
    $cc = "soy.gif";
    $cn = "Soyim";
}else{
    if ($ip != "::1") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "ip-api.com/json/$ip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $info = curl_exec($ch);
        curl_close($ch);
        $jsonip = json_decode($info);
        $cc = $jsonip->{"countryCode"};
        $cn = $jsonip->{"country"};
        $cc .= ".gif";
        $cc = strtolower($cc);
    }else {
        $cc = "lh.gif";
        $cn = "Localhost";
    }
}
if(!$postName){
    $postName = "Anonymous";
}
if(preg_match("/&gt;./", $postText) == 1){
    $postHTML = str_replace("style=''","style='color: green;'", $postHTML);
}
file_put_contents("counter.txt", $postNum+1);
$postHTML = str_replace("<_POSTNAME_>",$postName, $postTemplate);
$postHTML = str_replace("<_POSTTEXT_>",$postText, $postHTML);
$postHTML = str_replace("<_POSTNUM_>",$postNum, $postHTML);
$postHTML = str_replace("<_POSTDATE_>",date("Y/m/d g:i:s"), $postHTML);
$postHTML = str_replace("<_POSTFLAG_>","<img src='flags/$cc' title='$cn'>", $postHTML);
if ($fu==true){
    $wow = $postNum.".".$file_ext;
    $st = '<br><img width="100px" height="100px" style="float: left;" src="images/thingtochange"><br><br><br><br>';
    $st = str_replace("thingtochange", $wow, $st);
    $postHTML = str_replace("<!--POSTIMAGE-->",$st, $postHTML);
}
file_put_contents("messages.html", $postHTML . file_get_contents("messages.html"));
echo("Message sent!");