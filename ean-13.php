<?php
// Sending Headers
header ("Content-type: image/png");
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0
$code = $_GET["code"];
// Check validity of $code
if (strlen($code) != 13)
{
	$im = @ImageCreate (304, 100)
    	or die ("Cannot Initialize new GD image stream");
	$bg = ImageColorAllocate ($im, 255, 255, 255);
	$fg = ImageColorAllocate ($im, 0, 0, 0);
	ImageString ($im, 5, 3, 10, "Code $code is not valid", $fg);
	ImageString ($im, 5, 3, 30, "13 digits?", $fg);
	ImagePng ($im);
	break;
}


for ($i = 1; $i <= 13; $i++)
{
	if ((substr($code, $i-1, 1) <= 0) && ((substr($code, $i-1, 1) >= 9)))
	{
		$im = @ImageCreate (304, 100)
    		or die ("Cannot Initialize new GD image stream");
		$bg = ImageColorAllocate ($im, 255, 255, 255);
		$fg = ImageColorAllocate ($im, 0, 0, 0);
		ImageString ($im, 5, 3, 10, "Code $code is not valid", $fg);
		ImageString ($im, 5, 3, 30, "only digits!", $fg);
		ImagePng ($im);
		break;		
	} 
}


// Define bitcode for Numbers
$left[0]['O'] = "0001101";
$left[0]['E'] = "0100111";
$left[1]['O'] = "0011001";
$left[1]['E'] = "0110011";
$left[2]['O'] = "0010011";
$left[2]['E'] = "0011011";
$left[3]['O'] = "0111101";
$left[3]['E'] = "0100001";
$left[4]['O'] = "0100011";
$left[4]['E'] = "0011101";
$left[5]['O'] = "0110001";
$left[5]['E'] = "0111001";
$left[6]['O'] = "0101111";
$left[6]['E'] = "0000101";
$left[7]['O'] = "0111011";
$left[7]['E'] = "0010001";
$left[8]['O'] = "0110111";
$left[8]['E'] = "0001001";
$left[9]['O'] = "0001011";
$left[9]['E'] = "0010111";
$right[0] = "1110010";
$right[1] = "1100110";
$right[2] = "1101100";
$right[3] = "1000010";
$right[4] = "1011100";
$right[5] = "1001110";
$right[6] = "1010000";
$right[7] = "1000100";
$right[8] = "1001000";
$right[9] = "1110100";


// Calculate Checksum
/*$oddeven = 1;

for ($i = 1; $i <= 12; $i++)
{
    $num = substr($code, $i-1, 1);
    if ($oddeven == 1)
    {
	$intsum = $num * $oddeven;
	@$extsum += $intsum;
	$oddeven = 3;
    }
    else
    {
	$intsum = $num * $oddeven;
	$extsum = $extsum + $intsum;
	$oddeven = 1;
    }
}

$check = (floor($extsum/10)*10+10) - $extsum;

if ($check == 10)
{
    $check = 0;
}
$code = $code . $check;
*/
// Build Array from $code string

for ($i = 1; $i <= 13; $i++)
{
    $c[$i] = substr($code, $i-1, 1);
}

// Set parity

if ($c[1] == 0)
{
    $parity = "OOOOO";
}
else if ($c[1] == 1)
{
    $parity = "OEOEE";
}
else if ($c[1] == 2)
{
    $parity = "OEEOE";
}
else if ($c[1] == 3)
{
    $parity = "OEEEO";
}
else if ($c[1] == 4)
{
    $parity = "EOOEE";
}
else if ($c[1] == 5)
{
    $parity = "EEOOEE";
}
else if ($c[1] == 6)
{
    $parity = "EEEOO";
}
else if ($c[1] == 7)
{
    $parity = "EOEOE";
}
else if ($c[1] == 8)
{
    $parity = "EOEEO";
}
else if ($c[1] == 9)
{
    $parity = "EEOEO";
}

// Start generating bitcode for barcode
$barbit = "101"; // Startguard

$barbit = $barbit . $left[$c[2]]['O']; // 2nd char is always odd


for ($i = 3; $i <= 7; $i++) // generate first 5 digits with parity in bitcode
{
    $par = substr($parity, $i - 3, 1);
    $barbit = $barbit . $left[$c[$i]][$par];
}

$barbit = $barbit . "01010"; // Middleguard

for ($i = 8; $i <= 13; $i++) // generate bitcode for 5 digits and 1 checksum
{
    $barbit = $barbit . $right[$c[$i]];
}

$barbit = $barbit . "101"; // Endguard

// Create Image
$im = @ImageCreate (220, 75)
    or die ("Cannot Initialize new GD image stream");
$bg = ImageColorAllocate ($im, 255, 255, 255);
$fg = ImageColorAllocate ($im, 0, 0, 0);

$start = 14;
for ($i = 1; $i <= 95; $i++)
{
    $end = $start + 2;
    $bit = substr($barbit, $i-1, 1);
    if ($bit == 0)
    {
	Imagefilledrectangle ($im, $start, 0, $end, 50, $bg);
    }
    else
    {
	Imagefilledrectangle ($im, $start, 0, $end, 50, $fg);
    }
    $start = $end;
}

Imagefilledrectangle ($im, 299, 0, 304, 100, $bg);
Imagefilledrectangle ($im, 23, 80, 148, 100, $bg);
Imagefilledrectangle ($im, 163, 80, 289, 100, $bg);
Imagefilledrectangle ($im, 0, 92, 304, 100, $bg);
ImageString ($im, 2, 20, 55, "$c[1] $c[2] $c[3] $c[4] $c[5] $c[6] $c[7] $c[8] $c[9] $c[10] $c[11] $c[12] $c[13]", $fg);
/*ImageString ($im, 2, 37, 83, "$c[2] $c[3] $c[4] $c[5] $c[6] $c[7]", $fg);
ImageString ($im, 2, 177, 83, "$c[8] $c[9] $c[10] $c[11] $c[12] $c[13]", $fg);*/
ImagePng ($im);
?>
