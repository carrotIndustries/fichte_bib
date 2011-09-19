<?php
require("functions.php");
$pupils = mysql_fetch_superarray(mysql_query("SELECT * from pupils"));
foreach($pupils as $x) {
	
	$txt=$x["class"];
	if(strtolower($txt) != "leh") {
		echo $x["class"];
		echo " ";
		if((ord($txt[0]) >= 49) && (ord($txt[0]) <= 57)) {
			
			$re1='(\\d+)';	# Integer Number 1

			if ($c=preg_match_all ("/".$re1."/is", $txt, $matches)) {
				$i=intval($matches[1][0]);
			}
			
			$re1='.*?';	# Non-greedy match on filler
			$re2='([a-z])';	# Any Single Word Character (Not Whitespace) 1

			if ($c=preg_match_all ("/".$re1.$re2."/is", $txt, $matches)) {
				$w=$matches[1][0];
			}
			echo $i+1 . $w;
			
		}
		else {
			echo "f!xme-" . $txt;
		}
		echo "\n";
	}
}

?>
