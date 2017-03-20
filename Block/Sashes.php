<?php
namespace Space48\PromotionalSashes\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Space48\PromotionalSashes\Model\Sash;
use Space48\PromotionalSashes\Helper\Data as Helper;


class Sashes extends Template
{

    /** @var Sash */
    private $sash;
    private $helper;

    public function __construct(
        Context $context,
        Sash $sash,
        Helper $helper
    )
    {
        parent::__construct($context);
        $this->sash = $sash;
        $this->helper = $helper;
    }

    public function getNewSash($productId)
    {
        if ( $newSashConfig = $this->sash->getNewSashConfig($productId) ) {
            return $newSashConfig;
        }
        return false;
    }

    public function getSaleSash($productId)
    {
        if ( $saleSashConfig = $this->sash->getSaleSashConfig($productId) ) {
            return $saleSashConfig;
        }
        return false;
    }

    public function getPersonaliseSash($productId)
    {
        if ( $personaliseSashConfig = $this->sash->getPersonaliseSashConfig($productId) ) {
            return $personaliseSashConfig;
        }
        return false;
    }

    public function isPromotionalSasesEnabled()
    {
        return $this->helper->isModuleEnabled();
    }
}
