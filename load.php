<?php 

function runCheck($url,$debug = FALSE) {
	$file = file_get_contents($url);
	$start = strpos($file,'view-id-view_caught_on_camera');
	if($start) {
		$end = strpos($file,'<div class="item-list">',$start);
		$ourSect = substr($file,$start,$end-$start);
		$items = explode('views-row ',$ourSect);
		if($debug) { print_r($items); }
		array_shift($items);
		$x = 0;
		foreach($items as $item) {
			$lines = explode("\n",$item);

			foreach($lines as $ln => $line) {
				if(strpos($line,'field-image')>0) { $il = $ln; }
				if(strpos($line,'field-title')>0) { $tl = $ln; }
			}


			$start = strpos($lines[$il],'a href="')+5;
			$out[$x]['link'] = substr($lines[$il],$start,strpos($lines[$il],'"',$start)-$start);

			$start = strpos($lines[$il],'src="')+5;
			$out[$x]['image'] = substr($lines[$il],$start,strpos($lines[$il],'"',$start)-$start);
			$out[$x]['title'] = trim(strip_tags($lines[$tl]));

			$x2 = $tl+1;
			$out[$x]['text'] = NULL; 
			while($x2<count($lines)) {
				$out[$x]['text'] .= utf8_encode(trim(strip_tags($lines[$x2])))."\n";
				$x2++;
			}
			$out[$x]['text'] = substr($out[$x]['text'],0,-2);
			$out[$x]['date'] = 'Not supplied';
			$x++;
		}
	}
	return $out;
}

$ourArr = runCheck('http://www.nottinghamshire.police.uk/appeals');
$ourArr = array_merge($ourArr,runCheck('http://www.nottinghamshire.police.uk/appeals?page=0,1'));
$ourArr = array_merge($ourArr,runCheck('http://www.nottinghamshire.police.uk/appeals?page=0,2'));

echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
	<array>
		';
		if(isset($ourArr) && count($ourArr)) {
			foreach($ourArr as $item) {
				echo '<dict>
					<key>name</key>
					<string>'.$item['title'].'</string>
					<key>image</key>
					<string>'.$item['image'].'</string>
					<key>link</key>
					<string>'.$item['link'].'</string>
					<key>date</key>
					<string>'.$item['date'].'</string>
					<key>article</key>
					<string><![CDATA['.$item['text'].']]></string>
				</dict>'."\n";
			}
		}
		echo '
	</array>
</plist>'; ?>