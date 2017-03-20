<?php

namespace Space48\PromotionalSashes\Model;


use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as Category;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as Product;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product as ProductResource;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface as Store;
use Space48\PromotionalSashes\Helper\Data as ConfigHelper;

class Sash extends \Magento\Framework\Model\AbstractModel
{
    private $newCategoryName = 'New';
    /** @var ProductResource */
    private $productResource;

    /** @var Store */
    private $store;

    /** @var ConfigHelper */
    private $configHelper;

    /** @var DateTime */
    private $date;
    /** @var Product $product */
    private $product;
    /** @var Category $category */
    private $category;
    private $newCategoryProductIds;
    private $newCategory;
    private $catalogConfig;

    public function __construct(
        ProductResource $productResource,
        Product $product,
        Category $category,
        Store $store,
        ConfigHelper $configHelper,
        DateTime $dateTime,
        Config $catalogConfig,

        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->store = $store;
        $this->productResource = $productResource;
        $this->configHelper = $configHelper;
        $this->date = $dateTime;
        $this->product = $product;
        $this->category = $category;
        $this->catalogConfig = $catalogConfig;
    }

    /**
     * @param $productId
     * @return bool
     */
    public function getNewSashConfig($productId)
    {
        if ( $this->isInNewCategory($productId) ) {
            return $this->getPromotionalSashConfig()->getNewSashConfig();
        }
        return false;
    }

    public function isInNewCategory($productId)
    {
        return in_array($productId, $this->getNewProductIds());

    }

    public function getNewProductIds()
    {
        if ( ! $this->newCategoryProductIds ) {
            $this->newCategoryProductIds = $this->getProductsIdsByCategoryName($this->getNewCategory());
        }

        return $this->newCategoryProductIds;
    }

    public function getNewCategory()
    {
        if ( ! $this->newCategory ) {
            $this->newCategory = $this->getCategoryInstance($this->newCategoryName);
        }
        return $this->newCategory;
    }

    /**
     * @param $categoryName
     * @return \Magento\Framework\DataObject
     */
    private function getCategoryInstance($categoryName)
    {
        return $this->category
            ->create()
            ->addAttributeToFilter('name', $categoryName)->getFirstItem();
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getProductsIdsByCategoryName($category)
    {
        /** @var  $productCollection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $productCollection = $this->getProduct();
        return $productCollection
            ->addCategoryFilter($category)->getAllIds();

    }

    public function getProduct()
    {
        return $this->product->create();
    }

    /**
     * @param $productId
     * @return bool
     */
    public function getNewSashConfigUsingNewsFrom($productId)
    {
        if ( $this->isActiveForDateRange($productId, 'news_from_date', 'news_to_date') ) {
            return $this->getPromotionalSashConfig()->getNewSashConfig();
        }
        return false;
    }

    /**
     * @param $productId
     * @return bool
     */
    public function getSaleSashConfig($productId)
    {
        if ( $this->isSalePriceSetAndWithinSaleDateRange($productId) ) {

            return $this->getPromotionalSashConfig()->getSaleSashConfig();
        }

        return false;
    }

    /**
     * @param $productId
     * @return bool
     */
    public function getPersonaliseSashConfig($productId)
    {
        if ( $this->isPersonalisableProduct($productId) ) {
            return $this->getPromotionalSashConfig()->getPersonaliseSashConfig();
        }
        return false;
    }

    /**
     * @param $productId
     * @param $fromAttribute
     * @param $toAttribute
     * @return bool
     */
    public function isActiveForDateRange($productId, $fromAttribute, $toAttribute)
    {
        $timeFrom = $this->getTimeAttributeValue($productId, $fromAttribute);
        $timeTo = $this->getTimeAttributeValue($productId, $toAttribute);

        if ( $timeFrom && $timeTo ) {

            return $this->isFromBeforeTodayAndToAfterToday($timeFrom, $timeTo);

        }
        if ( $timeFrom && ! $timeTo ) {

            return $this->isFromTimeBeforeToday($timeFrom);

        }
        if ( ! $timeFrom && $timeTo ) {

            return $this->isToTimeAfterToday($timeTo);

        }

        return false;
    }

    /**
     * @return int
     */
    protected function getTimeStampToday()
    {

        return $this->date->timestamp();
    }

    /**
     * @param $productId
     * @param $attributeCode
     * @return array|bool|string
     */
    protected function getProductAttribute($productId, $attributeCode)
    {
        return $this->productResource->getResource()
            ->getAttributeRawValue($productId, $attributeCode, $this->store->getStore()->getId());
    }


    /**
     * @return \Magento\Framework\DataObject
     */
    protected function getPromotionalSashConfig()
    {
        return $this->configHelper->getPromotionalSashConfig();
    }


    /**
     * @param $productId
     * @return bool
     */
    private function isSalePriceSetAndWithinSaleDateRange($productId)
    {
        if ( $this->isSalePriceSet($productId)
            && $this->isActiveForDateRange($productId, 'special_from_date', 'special_to_date')
        ) {
            return true;
        }
        return false;
    }


    /**
     * @param $timeFrom
     * @param $timeTo
     * @return bool
     */
    private function isFromBeforeTodayAndToAfterToday($timeFrom, $timeTo)
    {
        if ( $this->isFromTimeBeforeToday($timeFrom) && $this->isToTimeAfterToday($timeTo) ) {
            return true;
        }

        return false;
    }

    /**
     * @param $timeFrom
     * @return bool
     */
    private function isFromTimeBeforeToday($timeFrom)
    {
        if ( ($this->getTimeStampToday() - $timeFrom) > 1 ) {
            return true;
        }
        return false;
    }

    /**
     * @param $timeTo
     * @return bool
     */
    private function isToTimeAfterToday($timeTo)
    {
        if ( ($timeTo - $this->getTimeStampToday()) > 1 ) {
            return true;
        }
        return false;
    }

    /**
     * @param $productId
     * @param $attribute
     * @return int
     */
    protected function getTimeAttributeValue($productId, $attribute)
    {
        return strtotime($this->getProductAttribute($productId, $attribute));
    }

    /**
     * @param $productId
     * @return bool
     */
    private function isSalePriceSet($productId)
    {
        if ( $this->getProductAttribute($productId, 'special_price') ) {
            return true;
        }
        return false;
    }

    /**
     * @param $productId
     * @return bool
     */
    private function isPersonalisableProduct($productId)
    {
        if ( $this->getProductAttribute($productId, 'personalise') ) {
            return true;
        }
        return false;
    }
}
