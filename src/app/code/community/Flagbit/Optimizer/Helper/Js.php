<?php

class Flagbit_Optimizer_Helper_Js extends Mage_Core_Helper_Js {
	
    public function getTranslatorScript()
    {
        if (!Mage::getStoreConfig('dev/optimizer/merge_translator') || (Mage::getStoreConfig('dev/optimizer/merge_translator') && !Mage::getStoreConfig('dev/js/merge_files'))) {
            return parent::getTranslatorScript();
        }

        $params = func_get_args();
		if (!isset($params[0]) || $params[0] == false) {
            return '';
        }

        return 'var Translator = new Translate('.$this->getTranslateJson().');';
    }

}
