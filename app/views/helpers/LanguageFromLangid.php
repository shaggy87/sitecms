<?php
class Zend_View_Helper_LanguageFromLangid {
    function LanguageFromLangid($langid, $short = 0)
    {
    	$lang_short = Zend_Registry::get("langs_short");
		$lang_long = Zend_Registry::get("langs_long");
		
        if (!isset($lang_short[$langid])) $langid = 0;
        return $short ? $lang_short[$langid] : $lang_long[$langid];
    }
}
?>