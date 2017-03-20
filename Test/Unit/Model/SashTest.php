<?php
namespace Space48\PromotionalSashes\Test\Unit\Model;


use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class SashTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Space48\PromotionalSashes\Model\Sash */
    protected $sashModel;

    /** @var  \Space48\PromotionalSashes\Helper\Data */
    private $configHelper;

    /** @var \Magento\Framework\DataObject */
    private $dataObject;


    protected function setUp()
    {

        $this->sashModel = $this->getMockBuilder('Space48\PromotionalSashes\Model\Sash')
            ->disableOriginalConstructor()
            ->setMethods(array('getTimeAttributeValue', 'getTimeStampToday'))
            ->getMock();

        $this->configHelper = $this->getMockBuilder('Space48\PromotionalSashes\Helper')
            ->disableOriginalConstructor()
            ->setMethods(array('getPromotionalSashConfig'))
            ->getMock();

        $objectManager = new ObjectManager($this);
        $this->dataObject = $objectManager->getObject('Magento\Framework\DataObject');


    }

    public function dateRangesDataProvider()
    {
        return [
            ['+1 week', '+1 week', false],
            ['-1 week', '-1 week', false],
            ['+1 week', '-1 week', false],
            ['-1 week', '+1 week', true],
            ['+1 week', '', false],
            ['', '+1 week', true],
            ['-1 week', '', true],
            ['', '-1 week', false],
            ['', '', false],
            ['now', 'now', false],
        ];
    }

    /**
     * @dataProvider  dateRangesDataProvider
     * @param $fromDate
     * @param $toDate
     * @param $expected
     */
    public function testIsActiveForDateRangesForARangeOfValuesAgainstToday($fromDate, $toDate, $expected)
    {
        $map = [
            [123, 'special_from_date', strtotime($fromDate)],
            [123, 'special_to_date', strtotime($toDate)]
        ];

        $this->sashModel->method('getTimeAttributeValue')
            ->will($this->returnValueMap($map));
        $this->sashModel->method('getTimeStampToday')
            ->willReturn(strtotime('now'));

        $result = $this->sashModel->isActiveForDateRange(123, 'special_from_date', 'special_to_date');

        if ( $expected === false ) {
            $this->assertFalse($result);
        } elseif ( $expected === true ) {
            $this->assertTrue($result);
        }
    }

    public function newSashConfigDataProvider()
    {
        return [
            [true, 'config'],
            [false, false]
        ];
    }

    /**
     * @dataProvider newSashConfigDataProvider
     * @param $dateCondition
     * @param $expects
     */
    public function testGetNewSashConfigUsingNewsFromWhenInAndOutOfDateRange($dateCondition, $expects)
    {
        $sashConfig = $this->dataObject;
        $sashConfig->setNewSashConfig($this->getTestConfigArray());

        $sashModel = $this->getMockWithIsActiveForDateRange();

        $sashModel->method('isActiveForDateRange')
            ->willReturn($dateCondition);
        $sashModel->method('getPromotionalSashConfig')
            ->willReturn($sashConfig);

        $result = $sashModel->getNewSashConfigUsingNewsFrom(123);
        if ( $expects === false ) {
            $this->assertFalse($result);
        } elseif ( $expects === 'config' ) {
            $this->checkArrayKeysForConfigValues($result);
        }

    }

    public function saleSashConfigDataProvider()
    {
        return [
            ['1.0', true, 'config'],
            ['', true, false],
            ['1.0', false, false],
            ['', false, false],
        ];
    }

    /**
     * @dataProvider saleSashConfigDataProvider
     * @param $salePrice
     * @param $dateCondition
     * @param $expected
     */
    public function testGetSaleSashConfigForDateConditionAndSalePrice($salePrice, $dateCondition, $expected)
    {
        $sashConfig = $this->dataObject;
        $sashConfig->setSaleSashConfig($this->getTestConfigArray());

        $sashModel = $this->getMockWithIsActiveForDateRange();


        $sashModel->method('isActiveForDateRange')
            ->willReturn($dateCondition);
        $sashModel->method('getPromotionalSashConfig')
            ->willReturn($sashConfig);
        $sashModel->method('getProductAttribute')
            ->willReturn($salePrice);

        $result = $sashModel->getSaleSashConfig(123);
        if ( $expected === false ) {
            $this->assertFalse($result);
        } elseif ( $expected === 'config' ) {
            $this->checkArrayKeysForConfigValues($result);
        }

    }

    public function personalisedSashConfigDataProvider()
    {
        return [
            [1, 'config'],
            [0, false],
        ];
    }

    /**
     * @dataProvider personalisedSashConfigDataProvider
     * @param $personaliseAttribute
     * @param $expected
     */
    public function testGetPersonaliseSashConfigForPersonaliseAttributeSettingValues($personaliseAttribute, $expected)
    {
        $sashConfig = $this->dataObject;
        $sashConfig->setPersonaliseSashConfig($this->getTestConfigArray());

        $sashModel = $this->getMockWithIsActiveForDateRange();


        $sashModel->method('getPromotionalSashConfig')
            ->willReturn($sashConfig);
        $sashModel->method('getProductAttribute')
            ->willReturn($personaliseAttribute);

        $result = $sashModel->getPersonaliseSashConfig(123);
        if ( $expected === false ) {
            $this->assertFalse($result);
        } elseif ( $expected === 'config' ) {
            $this->checkArrayKeysForConfigValues($result);
        }

    }

    /**
     * @param $result
     */
    private function checkArrayKeysForConfigValues($result)
    {
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('text_colour', $result);
        $this->assertArrayHasKey('background_colour', $result);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockWithIsActiveForDateRange()
    {
        $sashModel = $this->getMockBuilder('Space48\PromotionalSashes\Model\Sash')
            ->disableOriginalConstructor()
            ->setMethods([
                'isActiveForDateRange',
                'getPromotionalSashConfig',
                'getProductAttribute'
            ])
            ->getMock();
        return $sashModel;
    }

    /**
     * @return array
     */
    private function getTestConfigArray()
    {
        return ['text' => 'New', 'text_colour' => '', 'background_colour' => ''];
    }
}
