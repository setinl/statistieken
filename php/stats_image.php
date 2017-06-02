<?php

require_once '../generate/php/common.php';
require_once 'passwords/pass_read.php';
require_once 'sql/sql_read.php';

function GetUserDataForImage($id)
{
    $id = intval($id);
    $sql = connectSqlSeti();
    $table = SQL_TABLE_USERS;
    if ($sql === false)
    {
        return null;
    } 
    $command = "SELECT ".SQL_USER_NAME.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_RANK_CREDIT.",".SQL_RANK_RAC." FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_ID."='$id' LIMIT 1";
    $result = $sql->query($command);
    if ($result !== FALSE)
    {
	$row_cnt = $result->num_rows;
//        echo 'row_cnt'.$row_cnt;
	if ($row_cnt > 0)
	{
            while($row = mysqli_fetch_array($result))
            {	
                return $row;
            }
        }        
    }
    return null;
}


$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
//echo $id;
if ($id == null) die('invalid');
if ($id == false) die('invalid');

$row = GetUserDataForImage($id);
if ($row === null) die('id not found / id niet gevonden');

$user_name =  $row[SQL_USER_NAME];
$total_credit =  $row[SQL_TOTAL_CREDIT];
$rac = $row[SQL_RAC];
$rank_credit =  $row[SQL_RANK_CREDIT];
$rank_rac = $row[SQL_RANK_RAC];

//echo $user_name.$total_credit.$rac.$rank_credit.$rank_rac;

// Load And Create Image From Source
$im = imagecreatefromjpeg('../img/stats.jpg');
if ($im===false)
{
    die('invalid image');
}

header('Content-Type: image/jpeg');

// $im = imagecreatetruecolor(400, 30);
 
 // Allocate A Color For The Text Enter RGB Value
$textSetiColor = imagecolorallocate($im, 0, 0, 0);
$textSetiColorShadow = imagecolorallocate($im, 255, 255, 255);
 
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);

//imagefilledrectangle($im, 0, 0, 399, 29, $white);



// Set Path to Font File
$font = '../font/Jepanten.ttf';


// Set Text to Be Printed On Image
$text = $user_name;
$size=12;
$angle=0;
$left=10;
$top=15;

// Print Seti On Image
imagettftext($im, $size,$angle,$left+1,$top+1, $textSetiColorShadow, $font, $text);
imagettftext($im, $size,$angle,$left,$top, $textSetiColor, $font, $text);


// Set Text to Be Printed On Image
$text ="Rac: ".$rac." (".$rank_rac.")";
$size=12;
$angle=0;
$left=80;
$top=44;

// Print Seti On Image
imagettftext($im, $size,$angle,$left+1,$top+1, $textSetiColorShadow, $font, $text);
imagettftext($im, $size,$angle,$left,$top, $textSetiColor, $font, $text);


// Set Text to Be Printed On Image
$text ="Credits: ".$total_credit." (".$rank_credit.")";
$size=12;
$angle=0;
$left=80;
$top=64;

// Print Seti On Image
imagettftext($im, $size,$angle,$left+1,$top+1, $textSetiColorShadow, $font, $text);
imagettftext($im, $size,$angle,$left,$top, $textSetiColor, $font, $text);




$text ="SETI@Netherlands";
$size=18;
$angle=0;
$left=80;
$top=110;

// Print Seti On Image
imagettftext($im, $size,$angle,$left+2,$top+2, $textSetiColorShadow, $font, $text);
imagettftext($im, $size,$angle,$left,$top, $textSetiColor, $font, $text);
//imagettftext($im, 20, 0, 10, 60, $black, $font, $text);


// Send Image to Browser
//imagepng($im);
imagejpeg($im);

// Clear Memory
imagedestroy($im);

?>