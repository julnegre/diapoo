<?php include('funcs.php'); ?>
<html>
    <head>
		<meta content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" name="viewport">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta name="robots" content="none" />
		<meta http-equiv="Cache-control" content="public" />
		<title>Diapoo demo ^^</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
		<link rel="icon" type="image/png" href="favicon.png" />		
        <link rel="stylesheet prefetch" media="screen" href="css/main.css" type="text/css" />
        <link rel="stylesheet prefetch" media="screen" href="css/basic.css" type="text/css" />
        <link rel="stylesheet" href="css/swipebox.css">
        <link href='http://fonts.googleapis.com/css?family=Alef' rel='stylesheet' type='text/css'>

        <script src="/js/vendor/custom.modernizr.js"></script>    
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.isotope.js"></script>
        <script type="text/javascript" src="js/jquery.swipebox.js"></script>
        <script src="js/ios-orientationchange-fix.js"></script>
        <script type="text/javascript" src="js/jquery.scrollto.js"></script>
   
        <script type="text/javascript">
            /* <![CDATA[ */
            $(document).ready(function() {
                $("form").submit(function() {
                    $("#loading-full,.loading-ball").fadeIn();
                });
                $("#loading-full,.loading-ball").hide();

                $("#indic").click(function() {
                    hideIndic();
                })

                lastPostFunc();

                $(window).scroll(function() {
                    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                        lastPostFunc();
                    }
                });
                indic = setTimeout("hideIndic()", 7000)

            });

            var load = true;
            function lastPostFunc() {
                if (!load)
                    return;
                load = false;
                //$("#loading-full,.loading-ball").fadeIn();
                $(".thumb:last").fadeTo("slow", 0.3);
                $(".thumb:last").addClass("rotate");
                var url = "";
                if ($(".thumb").length != 0)
                    url = "page.php?p=" + (($(".thumb").length / 25));
                else {
                    url = "page.php?p=0";
                    $("#loading-full,.loading-ball").fadeIn();
                }
                $.get(url,
                        function(data) {
                            $(".thumb:last").removeClass("rotate")
                            $(".thumb:last").fadeTo("slow", 1);
                            //if (data != "")
                            //$('#thumbs').append( $(data) ).isotope( 'addItems', $(data) );
                            //$("#thumbs").isotope( 'appended', $( data ) ); 

                            if (data != "") {
                                if ($(".thumb").length != 0)
                                    $(".thumb:last").after(data);
                                else {
                                    $("#thumbs").html(data);
                                    $("#loading-full,.loading-ball").fadeOut();
                                }
                            }
                            getSwipeBox();
                            load = true;
                        });
            }
            ;

            function hideIndic() {
                $("#indic").fadeOut('slow');
                $("#container").css("margin-left", "0");
                clearTimeout(indic)
            }
            var swipeboxinstance = null;
            function getSwipeBox() {
                swipeboxinstance = null;

                if ($("#swipebox-overlay").length > 0)
                    return;
                
                swipeboxinstance = $(".swipebox").swipebox({
                    //useCSS : true, // false will force the use of jQuery for animations
                    hideBarsDelay: 0, // 0 to always show caption and action bar
                    //videoMaxWidth : 1140, // videos max width
                    beforeOpen: function() {
                        if ($("#swipebox-overlay").length > 0)
                            $("#swipebox-overlay").remove();
                    }, // called before opening
                    afterClose: function() {
                    } // called after closing
                });

            }

            /* ]]> */
        </script>	
        <style>
            .rotate {                       
                -moz-animation:spin .5s infinite linear;
                -webkit-animation:spin .5s infinite linear;
            }                        
            @-moz-keyframes spin {
                0% { -moz-transform:rotate(0deg); }
                100% { -moz-transform:rotate(360deg); }
            }
            @-moz-keyframes spinoff {
                0% { -moz-transform:rotate(0deg); }
                100% { -moz-transform:rotate(-360deg); }
            }
            @-webkit-keyframes spin {
                0% { -webkit-transform:rotate(0deg); }
                100% { -webkit-transform:rotate(360deg); }
            }
            @-webkit-keyframes spinoff {
                0% { -webkit-transform:rotate(0deg); }
                100% { -webkit-transform:rotate(-360deg); }
            }

        </style>                    
    </head>
    <body>
		
<?php

include('login.php');


$url = "http://julnegre.fr/diapoo/";
//$url = "http://localhost/diapo/photos/";
$Directory = dirname(__FILE__)."/photos";
$MyDirectory = opendir($Directory) or die('Erreur');
$img = array();
$id=0;
while($Entry = @readdir($MyDirectory)) {
	if( !is_dir($Directory.'/'.$Entry) && $Entry != '.' && $Entry != '..'&&(strtoupper(substr($Entry,-3))=="JPG"||strtoupper(substr($Entry,-4))=="WEBM")) {
		$stat = @exif_read_data($Directory.'/'.$Entry, "FILE", true); //filemtime($Directory."/".$Entry)
		list($d,$h) =explode(" ",$stat["EXIF"]["DateTimeOriginal"]);
		if( is_null($h) )
			list($d,$h) =explode(" ",date("Y:m:d H:i:s",filemtime($Directory."/".$Entry)));
		if(strtoupper(substr($Entry,-4))=="WEBM") 
			$img[$d."-".$h] = array("video"=>$url.$Entry);
		else
			$img[$d."-".$h] = array("small"=>$url."../small/".$Entry,"normal"=>$url.$Entry,"too_small"=>$url."../too_small/".$Entry);
	}
}
closedir($MyDirectory);
krsort($img);
?>

	<div id="controls" class="controls"></div>
		<div id="page">
			<div id="container">
				<h1><a href="index.php">Diapoo demo <span class="rosy">^^</span></a></h1>
				<div id="thumbs" class="navigation"></div>
			</div>
		</div>

	<div id="indic">Cliquez sur une des photos pour lancer le diaporama. Appuyez sur les touches droite / gauche pour vous déplacer dans le diaporama (ou à l'aide des icônes sous la photo) et/ou appuyez sur la touche "Echap" pour fermer la photo.</div>
	<div id="loading-full"></div><div class="loading-ball"></div>
	</body>
	<script type="text/javascript">$(function(){indic=setTimeout("hideIndic()",7000)});</script>	
</html>
