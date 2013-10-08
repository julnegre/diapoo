<?php include('funcs.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" name="viewport">
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta name="robots" content="none" />
		<meta http-equiv="Cache-control" content="public" />
		<title>Notre petite Rosie ^^</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
		<link rel="icon" type="image/png" href="favicon.png" />		
		<link rel="stylesheet prefetch" media="screen" href="css/main.css" type="text/css" />
		<link rel="stylesheet prefetch" media="screen" href="css/basic.css" type="text/css" />
		<link rel="stylesheet prefetch" media="screen" href="css/shadowbox.css" type="text/css" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.isotope.js"></script>
		<script type="text/javascript" src="js/shadowbox.js"></script>
		<script type="text/javascript" src="js/detect-mobile.js"></script>
		<script type="text/javascript" src="js/jquery.scrollto.js"></script>
		<script type="text/javascript">document.write('<style>.noscript { display: none; }</style>');</script>
		<link href='http://fonts.googleapis.com/css?family=Alef' rel='stylesheet' type='text/css'>
		<script type="text/javascript">
			/* <![CDATA[ */
			  $(document).ready(function() {
				$("form").submit(function(){
					$("#loading-full,.loading-ball").fadeIn();
				});
				$("#loading-full,.loading-ball").hide();
				if(jQuery.browser.mobile){
				  
				  $("#indic").hide();
				  var viewportWidth = $(window).width();
				  var viewportHeight = $(window).height();
				  $("body").css("width",viewportWidth);
				  $("#container").css("margin-left","0");
				  $("#login").css("width",viewportWidth);
				  $("#login form").css("width",viewportWidth-50);
				  $("#nav").css("position","absolute").css("right","3px").css("width","30px").css("height",$(document).heigth+"px").addClass("shadow").css("top","0px");
				  $("#nav span").css("padding-left","5px").css("padding-top","20px").css("font-size","23px");
				  $("#nav .selected a").css("font-size","30px");
				  $("#nav .selected").css("background-color","#C6B7B7"); 
				  //$("#nav").css("height",$("#nav").innerHeight()+"px"); 
				  $("#nav span a").click(function(){$.scrollTo(0,800);}) 
				  $("#nav").css("overflow-y","auto")
				
				}
				
				$("#indic").click(function(){hideIndic();})	

			});	
			
			function hideIndic(){
				$("#indic").fadeOut('slow'); $("#container").css("margin-left","0");
				clearTimeout(indic)
			}
			function getShadowBox(){
				Shadowbox.init({ skipSetup: true,handleUnsupported:  "remove",autoplayMovies:     true }); 
				//Shadowbox.setup('a[rel="shadowbox"]',{handleOversize: "resize"});	
				Shadowbox.setup();
			}
			
			function getDiap(p){
				$(".loading-ball,#loading-full").fadeIn('slow');
				$("#nav span").removeClass("selected");
				$("#nav span").eq(parseFloat(p)).addClass("selected")
				//$('#thumbs').fadeOut();
				//$('#thumbs').hide();
				$.get('page.php?p='+p, function(data) {
				//if( p=="0")	
				  $('#thumbs').html(data);
				  getShadowBox();
				  $.scrollTo(0,800);
				  $(".loading-ball,#loading-full").fadeOut('slow');
				  /*
				  var $dat = $(data)
					var $container = $('#thumbs');
						$container.imagesLoaded( function(){
							//$('#thumbs').fadeIn('slow');
							if( p=="0"){
							// TODO tester si isotope initialisé
							$container.isotope({
									  itemSelector : '.thumb',
									  animationEngine: 'css',
									  
									   masonry: {
										columnWidth: 0
									  },
									  
									  sortBy: 'original-order'
									});
									
							} else {		
								
								 var $firstTwoElems = $container.data('isotope')
								  .$filteredAtoms.filter( function( i ) {
									return i < 15;
								  });
								$container.isotope( 'remove', $firstTwoElems, function() {
									// console.log('items removed')
								  });
								$container.isotope( 'insert', $dat );
							}
							var $oldDat =$($('#thumbs').html());	
									//$container.isotope('shuffle');
									getShadowBox();
						});
				*/
				});  	     
			}	
			/* ]]> */
		</script>	
	</head>
	<body>
		
<?php

include('login.php');


$url = "http://host/photos/";
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
				<h1><a href="index.php">DIAPORAMA NAME <span class="rosy">^^</span></a></h1>
				<div id="nav">
				<?php 
				$page = (isset($_GET['p']))?$_GET['p']:0;
				$perpage = 15;	
				$max = round(count($img)/$perpage)-1;
				foreach( range(0,$max)  as $pg){

					echo "<span class=\"\"><a href=\"javascript:getDiap('".$pg."')\" >".$pg."</a></span>";
					if( $pg%15==0 )echo "<br/>";
					//if($page!=$max)
					//echo '<div id="page_next"><a href="index.php?p='.($page+1).'">Photos suivantes </a></div>';
				}
				?>
				</div>
				<div id="thumbs" class="navigation"></div>
			</div>
		</div>

	<div id="indic">Cliquez sur une des photos pour lancer le diaporama. Appuyez sur les touches droite / gauche pour vous déplacer dans le diaporama (ou à l'aide des icônes sous la photo) et/ou appuyez sur la touche "Echap" pour fermer la photo.</div>
	<div id="loading-full"></div><div class="loading-ball"></div>
	</body>
	<script type="text/javascript">$(function(){getDiap('0');indic=setTimeout("hideIndic()",7000)});</script>	
</html>
