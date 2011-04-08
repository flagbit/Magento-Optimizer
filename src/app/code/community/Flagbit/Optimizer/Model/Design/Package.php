<?php
/**
 * Magento Optimizer
 * 
 * @category    Flagbit
 * @package     Flagbit_Optimizer
 * @copyright   Copyright (c) 2011 Flagbit GmbH & Co. KG (http://www.flagbit.de)
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License, version 3
 */

/**
 * Package model
 *
 * @author      Nicolai Essig <essig@flagbit.de>
 */
class Flagbit_Optimizer_Model_Design_Package extends Mage_Core_Model_Design_Package
{
    /**
     * Merge specified css files and return URL to the merged file on success
     *
     * @param $files
     * @return string
     */
    public function getMergedCssUrl($files)
    {
        // secure or unsecure
        $isSecure = Mage::app()->getRequest()->isSecure();
        $mergerDir = $isSecure ? 'css_secure' : 'css';
        $targetDir = $this->_initMergerDir($mergerDir);
        if (!$targetDir) {
            return '';
        }

        // base hostname & port
        $baseMediaUrl = Mage::getBaseUrl('media', $isSecure);
        $hostname = parse_url($baseMediaUrl, PHP_URL_HOST);
        $port = parse_url($baseMediaUrl, PHP_URL_PORT);
        if (false === $port) {
            $port = $isSecure ? 443 : 80;
        }

        $filetimeSum = 0;
        foreach($files as $file) {
        	if(!file_exists($file)) {
        		continue;
        	}
        	$filetimeSum += filemtime($file);
        }
        
        // merge into target file
        $targetFilename = md5(implode(',', $files) . "|{$hostname}|{$port}" . md5($filetimeSum)) . '.css';
        if(version_compare(Mage::getVersion(),'1.5','<')) {
            if (Mage::helper('core')->mergeFiles($files, $targetDir . DS . $targetFilename, false, array($this, 'beforeMergeCss'), 'css')) {
            	return $baseMediaUrl . $mergerDir . '/' . $targetFilename;
        	}
        } else {
            if ($this->_mergeFiles($files, $targetDir . DS . $targetFilename, false, array($this, 'beforeMergeCss'), 'css')) {
            	return $baseMediaUrl . $mergerDir . '/' . $targetFilename;
        	}
        }
        return '';
    }
}
