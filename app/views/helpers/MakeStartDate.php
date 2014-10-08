<?php

class Zend_View_Helper_MakeStartDate {
	function MakeStartDate($t)
	{
		global $valid;
		$start = date("Y-M-d H:i:s", $t);
		if (time() >= $t)
			$valid = "active";
		else $valid = "not active";
		return $start;
	}
}