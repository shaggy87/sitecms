<?php

class Zend_View_Helper_MakeEndDate {
	function MakeEndDate($t)
	{
		global $valid;
		if ($t == 0) {
			return "<center>-</center>";
		}
		$end = date("Y-M-d H:i:s", $t);
		if (time() >= $t)
			$valid = "active";
		else $valid = "not active";
		return $end;
	}
}