<?php
namespace Space48\PromotionalSashes;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Space48\PromotionalSashes\Helper\Data as Helper;



class productCategoryTest extends \PHPUnit_Framework_TestCase
{
    private $objectManager;

    private $categoryId;

    private $category;

    /** @var  $product \Magento\Catalog\Model\Product */
    private $product;

    /** @var  $config \Magento\Config\Model\ResourceModel\Config */
    private $config;

    /** @var  $helper \Space48\PromotionalSashes\Helper\Data */
    private $helper;

    /** @var  $sash \Space48\PromotionalSashes\Model\Sash */
    private $sash;


    public function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->product = $this->objectManager->create('Magento\Catalog\Model\Product');
        $this->config = $this->objectManager->create('Magento\Config\Model\ResourceModel\Config');
        $this->helper = $this->objectManager->create('Space48\PromotionalSashes\Helper\Data');
        $this->sash = $this->objectManager->create('Space48\PromotionalSashes\Model\Sash');
        // Enable sashes
        $this->config->saveConfig(Helper::SASH_ENABLED_CONFIG, '1', 'default', 0);
        $this->config->saveConfig(Helper::NEW_SASH_TEXT_CONFIG, 'new test', 'default', 0);
        if ( ! $this->sash->getNewCategory()->getName() ) {
            $this->createCategory('New', 'new', 'new');
        }
    }

    public function testIsInNewCategoryReturnsFalseWhenProductNotInNewCategory()
    {
        $this->categoryId = $this->sash->getNewCategory()->getId();
        $productId = $this->createProduct(null);

        $this->assertFalse($this->sash->isInNewCategory($productId));
    }

    public function testIsInNewCategoryReturnsTrueWhenProductInNewCategory()
    {
        $this->categoryId = $this->sash->getNewCategory()->getId();
        $productId = $this->createProduct($this->categoryId, 'test1', 'testsku1');

        $this->assertTrue($this->sash->isInNewCategory($productId));
    }

    public function testGetNewSashConfigReturnsTextWhenProductInNewCategory()
    {
        $this->categoryId = $this->sash->getNewCategory()->getId();
        $productId = $this->createProduct($this->categoryId, 'test2', 'testsku2');
        $config = $this->sash->getNewSashConfig($productId);
        $this->assertSame('new test', $config['text']);
    }

    public function testGetNewSashConfigReturnsFalseWhenProductNotInNewCategory()
    {
        $productId = $this->createProduct(null, 'test3', 'testsku3');
        $config = $this->sash->getNewSashConfig($productId);
        $this->assertFalse($config);
    }

    public function testGetNewProductIdsReturnsIdForProductInNewCategory()
    {
        $this->categoryId = $this->sash->getNewCategory()->getId();
        $productId = $this->createProduct($this->categoryId, 'test4', 'testsku4');

        $ids = $this->sash->getNewProductIds();
        $this->assertContains($productId, $ids);
    }

    public function testGetNewProductIdsDoesNotContainAProductIdNotInNewCategory()
    {

        $productId = $this->createProduct(null, 'test5', 'testsku5');

        $ids = $this->sash->getNewProductIds();
        $this->assertNotContains($productId, $ids);
    }


    public function createCategory($name, $urlKey, $urlPath)
    {
        /** @var  $category \Magento\Catalog\Model\Category */
        $this->category = $this->objectManager->create('Magento\Catalog\Model\Category', [
            'data' => [

                "parent_id"       => 2,
                "name"            => $name,
                "is_active"       => true,
                "position"        => 2,
                "include_in_menu" => false,
            ]
        ]);

        $this->category->setCustomAttributes([
            "display_mode"               => "PRODUCTS",
            "is_anchor"                  => "1",
            "custom_use_parent_settings" => "0",
            "custom_apply_to_products"   => "0",
            "url_key"                    => $urlKey,
            "url_path"                   => $urlPath,
            "automatic_sorting"          => "0",
        ]);

        /** @var  $repository CategoryRepositoryInterface */
        $repository = $this->objectManager->get(CategoryRepositoryInterface::class);
        $repository->save($this->category);

    }

    public function createProduct($categoryId, $name = 'Test Product', $sku = 'test-SKU')
    {
        $this->product->setName($name);
        $this->product->setTypeId('simple');
        $this->product->setAttributeSetId(4);
        $this->product->setSku($sku);
        $this->product->setWebsiteIds(array(1));
        $this->product->setVisibility(4);
        $this->product->setPrice(array(1));
        $this->product->setStatus(1);
        $this->product->setPersonalise(1);
        $this->product->setCategoryIds([$categoryId]);
        $this->product->setStockData(array(
                'use_config_manage_stock' => 0,
                'manage_stock'            => 1,
                'min_sale_qty'            => 1,
                'max_sale_qty'            => 2,
                'is_in_stock'             => 1,
                'qty'                     => 100
            )
        );

        $this->product->save();
        return $this->product->getId();
    }
}