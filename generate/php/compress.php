<?php

function PackBase36($number)
{
	str_replace('.','a',$number);
	return base_convert(str_replace('.','a',$number) , 11, 36 );
}

function UnPackBase36($number)
{
	$convert = base_convert ($number , 36, 11 );
	return str_replace('a','.',$convert);
}

?>