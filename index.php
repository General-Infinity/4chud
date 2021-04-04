<!DOCTYPE html>
<html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link rel="stylesheet" href="style.css">
<body>
    [<a href="/" title="Home">/</a>]
<h1 class="boardtitle">/ - Home</h1>
<p>yeah its very stupid</p>
<a href="javascript:posting();"><p>Post message</p></a>
<form id="posts" action="post.php" enctype="multipart/form-data" target="upload_target">
<div style="display: none;" id="mydiv">
    <div id="mydivheader">Post<img alt="X" style="float: right; cursor: pointer;" src="cross.png" id="cbtn" onclick="document.getElementById('mydiv').style.display='none';" title="Close Window"></div>
    <input id="name" placeholder="Name" size="41" name="name"><br>
    <input id="options" placeholder="Options" size="41" name="options"><br>
    <textarea id="message"  placeholder="Text goes here..." rows="10" cols="40" name="message"></textarea><br>
    <select style="float: left;" name="flag">
        <option value="gl">Geographical Location</option>
        
        <option value="fk">Forkiestan</option>
        <option value="soy">Soyim</option>
        <option value="vb">Vaporwave Bhutan</option>
        <option value="yg">Yugoslavia</option>
        <optgroup label="Spaceflags">
        <option value="os">Outer Space</option>
        <option value="moon">Moon</option>
        <option value="ce">Ceres</option>
        <option value="po">Pluto</option>
        </optgroup>
        <option value="cb">Combine</option>
        <option value="jew">Jew</option>
    </select><br>
    <input style="float: left;" type="file" name="image" id="image">
    <input id="submis" style="float: right;"type="submit" value="submit"/>
</div>
</form>
<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
<script>
    //document.getElementById("mydiv").style.display="none";
    $(function() {
        $("form").on("submit", function(e) {
            var form = document.getElementById('posts');
            var fd = new FormData(form);
            //var fd = $(this).serialize();
            $.ajax({
                url: "post.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data) {
                    $("#message").hide();
                    alert(data);
                    location.reload();
                }
            });
            return false;
        });
    });
    function posting(){
        document.getElementById("mydiv").style.display="inline-block";
    }
    //Make the DIV element draggagle:
    dragElement(document.getElementById("mydiv"));

    function dragElement(elmnt) {
        var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        if (document.getElementById(elmnt.id + "header")) {
            /* if present, the header is where you move the DIV from:*/
            document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
        } else {
            /* otherwise, move the DIV from anywhere inside the DIV:*/
            elmnt.onmousedown = dragMouseDown;
        }

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }
</script>

</body>
</html>

<?php
echo(file_get_contents("messages.html"));
