<?php

namespace Mantix\EBoekhoudenRestApi\Tests;

use Mantix\EBoekhoudenRestApi\Filter;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase {
    public function testEq() {
        // For equals, the filter just returns the value unchanged
        $this->assertEquals('test', Filter::eq('test'));
        $this->assertEquals(123, Filter::eq(123));
    }

    public function testNotEq() {
        $this->assertEquals('[not_eq]test', Filter::notEq('test'));
        $this->assertEquals('[not_eq]123', Filter::notEq(123));
    }

    public function testLike() {
        $this->assertEquals('[like]%test%', Filter::like('%test%'));
        $this->assertEquals('[like]test%', Filter::like('test%'));
    }

    public function testLikeWithPercentSign() {
        // % should be escaped to %25 for URL
        $this->assertEquals('[like]%25test%25', Filter::like('%test%'));
    }

    public function testNotLike() {
        $this->assertEquals('[not_like]%test%', Filter::notLike('%test%'));
        $this->assertEquals('[not_like]test%', Filter::notLike('test%'));
    }

    public function testNotLikeWithPercentSign() {
        // % should be escaped to %25 for URL
        $this->assertEquals('[not_like]%25test%25', Filter::notLike('%test%'));
    }

    public function testGt() {
        $this->assertEquals('[gt]123', Filter::gt(123));
        $this->assertEquals('[gt]12.34', Filter::gt(12.34));
    }

    public function testGte() {
        $this->assertEquals('[gte]123', Filter::gte(123));
        $this->assertEquals('[gte]12.34', Filter::gte(12.34));
    }

    public function testLt() {
        $this->assertEquals('[lt]123', Filter::lt(123));
        $this->assertEquals('[lt]12.34', Filter::lt(12.34));
    }

    public function testLte() {
        $this->assertEquals('[lte]123', Filter::lte(123));
        $this->assertEquals('[lte]12.34', Filter::lte(12.34));
    }

    public function testRange() {
        $this->assertEquals('[range]10,20', Filter::range(10, 20));
        $this->assertEquals('[range]1.5,7.8', Filter::range(1.5, 7.8));
    }

    public function testDateRange() {
        $this->assertEquals(
            '[range]2023-01-01,2023-12-31',
            Filter::dateRange('2023-01-01', '2023-12-31')
        );
    }
}
