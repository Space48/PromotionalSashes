<?php
namespace Space48\PromotionalSashes\Block\Product;


class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    public function getPromotionalSashes($productId){
        $block = $this->getLayout()->getBlock('promotional-sashes');
        $block->setProductId($productId);

        return $block->toHtml();
    }
}

