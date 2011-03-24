<?php

class Flagbit_Optimizer_Model_Observer {

    protected $_jsCache = array();

    public function parseOutput($observer)
    {
        // @TODO: Add store configuration
        if ($observer->getBlock()->getNameInLayout() != 'root' || false) {
            return;
        }
	
        $content = $observer->getTransport()->getHtml();
        $content = $this->optimizeHtml($content);
        //$content = str_replace('& ', '&amp; ', $content);
        $observer->getTransport()->setHtml($content);
    }
    	
    protected function _javascriptCollector($matches){
        if(!trim($matches[2])){
            return $matches[0];
        }
        
        $key = '###SITEOPTIMIZER_JS_PLACEHOLDER' . count($this->_jsCache) . '###';
        $this->_jsCache[$key] = $matches[0];
        
        return $key;
    }
    
    /**
     * optimize HTML Code
     *
     * @param string $htmlCode
     * @return string
     */
    public function optimizeHTML($htmlCode)
    {
        // cache and remove Javascripts
        $htmlCode = preg_replace_callback('/\<script([^\>]*)\>(.*)\<\/script\>/iUms', array(&$this, '_javascriptCollector'), $htmlCode);
        
        // strip Comments and leave IE Conditions
        // $htmlCode = preg_replace("|<\!--?!\[[\s\S]*-->|iU",'',$htmlCode);
        // not needed because TYPO3 had this allready included				
        
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
