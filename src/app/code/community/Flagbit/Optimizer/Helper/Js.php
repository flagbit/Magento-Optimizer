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
 * Js Helper
 *
 * @author      Michael Türk <tuerk@flagbit.de>
 * @author      Nicolai Essig <essig@flagbit.de>
 */
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
