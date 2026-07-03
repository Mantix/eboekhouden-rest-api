<?php

namespace Mantix\EBoekhoudenRestApi;

/**
 * Helper class for creating filters for API queries
 */
class Filter {
    /**
     * Create an equal filter
     *
     * @param mixed $value The value to filter on
     * @return FilterValue The formatted filter
     */
    public static function eq($value): FilterValue {
        return new FilterValue('eq', $value);
    }

    /**
     * Create a not equal filter
     *
     * @param mixed $value The value to filter on
     * @return FilterValue The formatted filter
     */
    public static function notEq($value): FilterValue {
        return new FilterValue('not_eq', $value);
    }

    /**
     * Create a like filter (string only)
     *
     * @param string $value The value to filter on
     * @return FilterValue The formatted filter
     */
    public static function like(string $value): FilterValue {
        return new FilterValue('like', $value);
    }

    /**
     * Create a not like filter (string only)
     *
     * @param string $value The value to filter on
     * @return FilterValue The formatted filter
     */
    public static function notLike(string $value): FilterValue {
        return new FilterValue('not_like', $value);
    }

    /**
     * Create a greater than filter (numeric only)
     *
     * @param int|float $value The value to filter on
     * @return FilterValue The formatted filter
     */
    public static function gt($value): FilterValue {
        return new FilterValue('gt', $value);
    }

    /**
     * Create a greater than or equal filter (numeric only)
     *
     * @param int|float $value The value to filter on
     * @return FilterValue The formatted filter
     */
    public static function gte($value): FilterValue {
        return new FilterValue('gte', $value);
    }

    /**
     * Create a less than filter (numeric only)
     *
     * @param int|float $value The value to filter on
     * @return FilterValue The formatted filter
     */
    public static function lt($value): FilterValue {
        return new FilterValue('lt', $value);
    }

    /**
     * Create a less than or equal filter (numeric only)
     *
     * @param int|float $value The value to filter on
     * @return FilterValue The formatted filter
     */
    public static function lte($value): FilterValue {
        return new FilterValue('lte', $value);
    }

    /**
     * Create a range filter (numeric only)
     *
     * @param int|float $min The minimum value
     * @param int|float $max The maximum value
     * @return FilterValue The formatted filter
     */
    public static function range($min, $max): FilterValue {
        return new FilterValue('range', "{$min},{$max}");
    }

    /**
     * Create a range filter for dates
     *
     * @param string $startDate The start date (format: YYYY-MM-DD)
     * @param string $endDate The end date (format: YYYY-MM-DD)
     * @return FilterValue The formatted filter
     */
    public static function dateRange(string $startDate, string $endDate): FilterValue {
        return new FilterValue('range', "{$startDate},{$endDate}");
    }
}
