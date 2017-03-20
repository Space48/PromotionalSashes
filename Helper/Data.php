<?php
namespace Space48\PromotionalSashes\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\DataObject;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SASH_ENABLED_CONFIG = 'sashes/promosashes/is_enabled';

    const NEW_SASH_TEXT_CONFIG = 'sashes/newsash/new_text';
    const NEW_SASH_TEXT_COLOUR_CONFIG = 'sashes/newsash/new_text_colour';
    const NEW_SASH_BACKGROUND_COLOUR_CONFIG = 'sashes/newsash/new_background_colour';

    const SALE_SASH_TEXT_CONFIG = 'sashes/salesash/sale_text';
    const SALE_SASH_TEXT_COLOUR_CONFIG = 'sashes/salesash/sale_text_colour';
    const SALE_SASH_BACKGROUND_COLOUR_CONFIG = 'sashes/salesash/sale_background_colour';

    const PERSONALISE_SASH_TEXT_CONFIG = 'sashes/personalisesash/personalise_text';
    const PERSONALISE_SASH_TEXT_COLOUR_CONFIG = 'sashes/personalisesash/personalise_text_colour';
    const PERSONALISE_SASH_BACKGROUND_COLOUR_CONFIG = 'sashes/personalisesash/personalise_background_colour';


    /**
     * @return bool
     */
    public function isModuleEnabled()
    {

        if ( $this->scopeConfig->getValue($this::SASH_ENABLED_CONFIG, ScopeInterface::SCOPE_STORE) ) {
            return true;
        }
        return false;
    }


    /**
     * @return DataObject
     */
    public function getPromotionalSashConfig()
    {
        $sashConfig = $this->getDataObject();
        $sashConfig->setNewSashConfig(
            array(
                'text'              => $this->getConfig($this::NEW_SASH_TEXT_CONFIG),
                'text_colour'       => $this->getConfig($this::NEW_SASH_TEXT_COLOUR_CONFIG),
                'background_colour' => $this->getConfig($this::NEW_SASH_BACKGROUND_COLOUR_CONFIG)
            )
        );
        $sashConfig->setSaleSashConfig(
            array(
                'text'              => $this->getConfig($this::SALE_SASH_TEXT_CONFIG),
                'text_colour'       => $this->getConfig($this::SALE_SASH_TEXT_COLOUR_CONFIG),
                'background_colour' => $this->getConfig($this::SALE_SASH_BACKGROUND_COLOUR_CONFIG),
            )
        );

        $sashConfig->setPersonaliseSashConfig(
            array(
                'text'              => $this->getConfig($this::PERSONALISE_SASH_TEXT_CONFIG),
                'text_colour'       => $this->getConfig($this::PERSONALISE_SASH_TEXT_COLOUR_CONFIG),
                'background_colour' => $this->getConfig($this::PERSONALISE_SASH_BACKGROUND_COLOUR_CONFIG),
            )
        );


        return $sashConfig;
    }

    /**
     * @return DataObject
     */
    protected function getDataObject()
    {
        return new DataObject();
    }

    /**
     * @param $configPath
     * @return mixed
     */
    protected function getConfig($configPath)
    {
        return $this->scopeConfig->getValue($configPath, 'default', 0);
    }
}
