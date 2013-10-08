<?php
$url = "http://photos.rosie.fr/photos/";
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
		
$id=0;		
$page = (isset($_GET['p']))?$_GET['p']:0;
$perpage = 15;	
$max = round(count($img)/$perpage)-1;		
foreach( $img as $k => $i ){	
	if( $id >= ($page*$perpage) && $id < ($page+1)*$perpage){	
		// ((int)($_GET['p'].$id))
		if( isset($i["video"]) )
		echo '
		<div class="element">
			<a rel="shadowbox['.$_GET['p'].'];player=iframe" href="'.$i["video"].'" title="'.$k.'" tt="'.$i["video"].'">
				<img src="css/play.png" alt="'.$k.'" class="thumb" height="150px" />
			</a>
			<a href="'.$i["video"].'" target="_blank" class="save">
				<img src="css/disk.png" title="Enregistrer" alt="Enregistrer"/>
			</a>
		</div>';			
		
		else 
		echo '
		<div class="element">
			<a rel="shadowbox['.$_GET['p'].']" href="'.$i["small"].'" title="'.$k.'" tt="'.$i["small"].'">
				<img src="'.$i["too_small"].'" alt="'.$k.'" class="thumb" height="150px" />
			</a>
			<a href="'.$i["normal"].'" target="_blank" class="save">
				<img src="css/disk.png" title="Enregistrer" alt="Enregistrer"/>
			</a>
		</div>';
	}						
	$id++;
}
?>
					
