<?php

namespace Mantix\EBoekhoudenRestApi\Tests;

use Mantix\EBoekhoudenRestApi\Filter;
use Mantix\EBoekhoudenRestApi\FilterValue;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase {
    public function testEq() {
        $filter = Filter::eq('test');
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('eq', $filter->getOperator());
        $this->assertEquals('test', $filter->getValue());

        $filter = Filter::eq(123);
        $this->assertEquals('eq', $filter->getOperator());
        $this->assertEquals(123, $filter->getValue());
    }

    public function testNotEq() {
        $filter = Filter::notEq('test');
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('not_eq', $filter->getOperator());
        $this->assertEquals('test', $filter->getValue());

        $filter = Filter::notEq(123);
        $this->assertEquals('not_eq', $filter->getOperator());
        $this->assertEquals(123, $filter->getValue());
    }

    public function testLike() {
        $filter = Filter::like('%test%');
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('like', $filter->getOperator());
        $this->assertEquals('%test%', $filter->getValue());

        $filter = Filter::like('test%');
        $this->assertEquals('like', $filter->getOperator());
        $this->assertEquals('test%', $filter->getValue());
    }

    public function testNotLike() {
        $filter = Filter::notLike('%test%');
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('not_like', $filter->getOperator());
        $this->assertEquals('%test%', $filter->getValue());

        $filter = Filter::notLike('test%');
        $this->assertEquals('not_like', $filter->getOperator());
        $this->assertEquals('test%', $filter->getValue());
    }

    public function testGt() {
        $filter = Filter::gt(123);
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('gt', $filter->getOperator());
        $this->assertEquals(123, $filter->getValue());

        $filter = Filter::gt(12.34);
        $this->assertEquals('gt', $filter->getOperator());
        $this->assertEquals(12.34, $filter->getValue());
    }

    public function testGte() {
        $filter = Filter::gte(123);
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('gte', $filter->getOperator());
        $this->assertEquals(123, $filter->getValue());

        $filter = Filter::gte(12.34);
        $this->assertEquals('gte', $filter->getOperator());
        $this->assertEquals(12.34, $filter->getValue());
    }

    public function testLt() {
        $filter = Filter::lt(123);
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('lt', $filter->getOperator());
        $this->assertEquals(123, $filter->getValue());

        $filter = Filter::lt(12.34);
        $this->assertEquals('lt', $filter->getOperator());
        $this->assertEquals(12.34, $filter->getValue());
    }

    public function testLte() {
        $filter = Filter::lte(123);
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('lte', $filter->getOperator());
        $this->assertEquals(123, $filter->getValue());

        $filter = Filter::lte(12.34);
        $this->assertEquals('lte', $filter->getOperator());
        $this->assertEquals(12.34, $filter->getValue());
    }

    public function testRange() {
        $filter = Filter::range(10, 20);
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('range', $filter->getOperator());
        $this->assertEquals('10,20', $filter->getValue());

        $filter = Filter::range(1.5, 7.8);
        $this->assertEquals('range', $filter->getOperator());
        $this->assertEquals('1.5,7.8', $filter->getValue());
    }

    public function testDateRange() {
        $filter = Filter::dateRange('2023-01-01', '2023-12-31');
        $this->assertInstanceOf(FilterValue::class, $filter);
        $this->assertEquals('range', $filter->getOperator());
        $this->assertEquals('2023-01-01,2023-12-31', $filter->getValue());
    }
}
