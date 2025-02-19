<?php

namespace unit\traits;

use common\traits\FormattedDollarTrait;
use Yii;

use function PHPUnit\Framework\assertEquals;

/**
 * Formatted dollar trait test
 */
class FormattedDollarTraitTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider floatDataProvider
     * @return void
     */
    public function testConvertToCents($test, $expected)
    {
        $mock = $this->getMockForTrait(FormattedDollarTrait::class);

        $this->assertEquals($expected, $mock->convertToCents($test));
    }

    public function floatDataProvider()
    {
        return [
            [12.445, 1244],
            [-13.678901234, -1367],
            ["-10.4", -1040],
            ["-10", -1000],
            ["11.445", 1144],
            ["533.3.3533.11,445", 533335331144],
            ["1,40032,0030.445", 140032003044],
            [124.99, 12499],
            [-1.4, -140],
            [14, 1400],
            [.99, 99],
            [2.3, 230],
            [-30, -3000],
        ];
    }

}
