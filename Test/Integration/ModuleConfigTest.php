<?php
namespace Space48\PromotionalSashes;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\TestFramework\ObjectManager;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ModuleConfigTest extends \PHPUnit_Framework_TestCase
{
    private $moduleName = 'Space48_PromotionalSashes';
    private $defaultScope;
    private $objectManager;

    /** @var \Magento\Framework\App\Config  */
    private $config;

    public function setUp()
    {
        $this->defaultScope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
        $this->objectManager = ObjectManager::getInstance();

        $this->config = $this->objectManager->create('Magento\Framework\App\Config');
    }

    public function testTheModuleIsRegistered()
    {
        $registrar = new ComponentRegistrar();
        $registrar->getPaths(ComponentRegistrar::MODULE);
        $this->assertArrayHasKey($this->moduleName, $registrar->getPaths(ComponentRegistrar::MODULE));
    }


}