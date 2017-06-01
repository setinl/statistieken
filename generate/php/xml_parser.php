<?php

// data, begin tag, end tag, skip data block, if TRUE; remove block at the beginning

function xmlExtract(&$data, $xml_tag, $xml_tag_end, $skip) {
	// Extract the block.
	$startpos = strpos($data,$xml_tag)+strlen($xml_tag);
	if ($startpos === false)  return "";

	$stoppos  = strpos($data,$xml_tag_end,$startpos);
	if ($stoppos === false)  return "";
	
	$block = substr($data,$startpos,$stoppos-$startpos);

	if ($skip) {
		// new string minus the block, begin xml_tag and xml_tag_end
		$endblock = $stoppos + strlen($xml_tag_end);
		$data = substr($data,$endblock);
	}
	
	return $block;
}

function xmlFindTag($data, $xml_tag) {

	$pos = strpos($data,$xml_tag);
	if ($pos === false) return ERR_NOT_FOUND;
	
	return STATUS_OK;
}

?>