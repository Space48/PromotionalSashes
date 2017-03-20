<?php
namespace Space48\PromotionalSashes\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
class DataTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Space48\PromotionalSashes\Helper\Data */
    private $configHelper;
    /** @var  \Magento\Framework\DataObject */
    private $dataObject;

    public function setUp(){
        $this->configHelper = $this->getMockBuilder('Space48\PromotionalSashes\Helper\Data')
            ->disableOriginalConstructor()
            ->setMethods(array('getDataObject','getConfig'))
            ->getMock();

        $objectManager = new ObjectManager($this);
        $this->dataObject = $objectManager->getObject('Magento\Framework\DataObject');
    }

    public function testGetPromotionalSashConfigReturnsInstanceOfDataObject(){
        $this->configHelper->method('getDataObject')
            ->willReturn($this->dataObject);
        $this->configHelper->method('getConfig')
            ->willReturn('some text');

        $result = $this->configHelper->getPromotionalSashConfig();

        $this->assertInstanceOf('\Magento\Framework\DataObject',$result);
    }

}
