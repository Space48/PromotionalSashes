<?php
namespace Space48\PromotionalSashes;

use Magento\Framework\ObjectManager\ConfigInterface as ObjectManagerConfig;
use Magento\TestFramework\ObjectManager;

class DiConfigurationTest extends \PHPUnit_Framework_TestCase
{
    private $preferenceType = 'Space48\PromotionalSashes\Block\Product\ListProduct';
    private $preferenceFor = 'Magento\Catalog\Block\Product\ListProduct';


    public function testDiPreferenceAssociation()
    {
        /** @var ObjectManagerConfig $diConfig */
        $diConfig = ObjectManager::getInstance()->get(ObjectManagerConfig::class);
        $preferenceList = $diConfig->getPreferences($this->preferenceType);


        $this->assertSame(
            $this->preferenceType,
            $preferenceList[$this->preferenceFor]
        );
    }
}
