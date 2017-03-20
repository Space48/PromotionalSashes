<?php
namespace Space48\PromotionalSashes\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements InstallDataInterface
{
    private $eavSetup;
    private $attributeCode = 'personalise';

    public function __construct(
        EavSetup $eavSetup
    )
    {
        $this->eavSetup = $eavSetup;
    }

    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context)
    {

        $setup->startSetup();
        $this->eavSetup->addAttribute(Product::ENTITY, $this->attributeCode, [
            'type'                    => 'int',
            'label'                   => 'Personalisable',
            'input'                   => 'select',
            'required'                => false,
            'sort_order'              => 3,
            'source'                  => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
            'backend'                 => '',
            'global'                  => ScopedAttributeInterface::SCOPE_GLOBAL,
            'filterable_in_search'    => '0',
            'used_in_product_listing' => true,
            'visible_on_front'        => true,
            'searchable'              => '0',
            'filterable'              => '0',
            'note'                    => 'Choose yes if a product can be personalised'
        ]);
        $setup->endSetup();
    }

} 