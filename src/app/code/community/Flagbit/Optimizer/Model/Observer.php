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
 * Observer
 *
 * @author      Michael Türk <tuerk@flagbit.de>
 * @author      Nicolai Essig <essig@flagbit.de>
 */
class Flagbit_Optimizer_Model_Observer {

    protected $_jsCache = array();

    /**
     * Callback method
     * 
     * @param string $matches
     * @return int key of array
     */
    protected function _javascriptCollector($matches){
        if(!trim($matches[2])){
            return $matches[0];
        }
        
        $key = '###SITEOPTIMIZER_JS_PLACEHOLDER' . count($this->_jsCache) . '###';
        $this->_jsCache[$key] = $matches[0];
        
        return $key;
    }
    
    /**
     * Parse HTML Output
     * 
     * @param object $observer
     * @return void
     */
    public function parseOutput($observer)
    {
        if (!Mage::getStoreConfig('dev/optimizer/remove_empty_spaces') || $observer->getBlock()->getNameInLayout() != 'root') {
            return;
        }
	
        $content = $observer->getTransport()->getHtml();
        $content = $this->optimizeHtml($content);
        $observer->getTransport()->setHtml($content);
    }
    	
    /**
     * Optimize HTML Code
     *
     * @param string $htmlCode
     * @return string
     */
    public function optimizeHTML($htmlCode)
    {
        // cache and remove Javascripts
        $htmlCode = preg_replace_callback('/\<script([^\>]*)\>(.*)\<\/script\>/iUms', array(&$this, '_javascriptCollector'), $htmlCode);
        
        // strip whitespaces
        $htmlCode = preg_replace("/(\s)+/", " ", $htmlCode);	
        $htmlCode = preg_replace('/<\?xml(.*)\?>/iU',"\\0\n", $htmlCode);
        
        // include Javascripts
        if(count($this->_jsCache)){
            $htmlCode = str_replace(array_keys($this->_jsCache), array_values($this->_jsCache), $htmlCode);
        }
        
        return $htmlCode;
    }	
}
