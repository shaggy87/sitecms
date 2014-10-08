<?php

class Zend_View_Helper_CheckFileExists extends Zend_View_Helper_Abstract
{
	
	function CheckFileExists($filename)
	{
        $paths = $this->view->getScriptPaths();
        foreach ($paths as $path) {
            if (file_exists($path . $filename)) {
				return true;
            }
        }
        return false;
	}
}