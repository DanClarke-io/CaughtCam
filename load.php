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
			if(strpos($lines[$il],'a href="')!==FALSE) {
				preg_match_all('#<a\s.*?(?:href=[\'"](.*?)[\'"]).*?>#is', $lines[$il],$matches1); //http://stackoverflow.com/a/18898628
				$out[$x]['link'] = $matches1[1][0];
			}

			if(strpos($lines[$il],'src="')!==FALSE) {
				preg_match_all('#<img\s.*?(?:src=[\'"](.*?)[\'"]).*?>#is', $lines[$il],$matches2); //http://stackoverflow.com/a/18898628
				$out[$x]['image'] = $matches2[1][0];
			}

			$out[$x]['title'] = trim(strip_tags($lines[$tl]));

			$x2 = $tl+1;
			$out[$x]['text'] = NULL; 
			while($x2<count($lines)) {
				$out[$x]['text'] .= trim(strip_tags($lines[$x2]));
				$x2++;
			}
			$out[$x]['text'] = trim($out[$x]['text']);
			$out[$x]['date'] = 'Not yet collected';
			$x++;
		}
	}
	return $out;
}

$ourArr = runCheck('http://www.nottinghamshire.police.uk/appeals');
$ourArr = array_merge($ourArr,runCheck('http://www.nottinghamshire.police.uk/appeals?page=0,1'));
$ourArr = array_merge($ourArr,runCheck('http://www.nottinghamshire.police.uk/appeals?page=0,2'));

echo json_encode($ourArr,JSON_PRETTY_PRINT);