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
     * @return mixed The value
     */
    public static function eq($value) {
        return $value;
    }

    /**
     * Create a not equal filter
     *
     * @param mixed $value The value to filter on
     * @return string The formatted filter
     */
    public static function notEq($value): string {
        return "[not_eq]{$value}";
    }

    /**
     * Create a like filter (string only)
     *
     * @param string $value The value to filter on
     * @return string The formatted filter
     */
    public static function like(string $value): string {
        // Escape % character in URL
        $escapedValue = str_replace('%', '%25', $value);
        return "[like]{$escapedValue}";
    }

    /**
     * Create a not like filter (string only)
     *
     * @param string $value The value to filter on
     * @return string The formatted filter
     */
    public static function notLike(string $value): string {
        // Escape % character in URL
        $escapedValue = str_replace('%', '%25', $value);
        return "[not_like]{$escapedValue}";
    }

    /**
     * Create a greater than filter (numeric only)
     *
     * @param int|float $value The value to filter on
     * @return string The formatted filter
     */
    public static function gt($value): string {
        return "[gt]{$value}";
    }

    /**
     * Create a greater than or equal filter (numeric only)
     *
     * @param int|float $value The value to filter on
     * @return string The formatted filter
     */
    public static function gte($value): string {
        return "[gte]{$value}";
    }

    /**
     * Create a less than filter (numeric only)
     *
     * @param int|float $value The value to filter on
     * @return string The formatted filter
     */
    public static function lt($value): string {
        return "[lt]{$value}";
    }

    /**
     * Create a less than or equal filter (numeric only)
     *
     * @param int|float $value The value to filter on
     * @return string The formatted filter
     */
    public static function lte($value): string {
        return "[lte]{$value}";
    }

    /**
     * Create a range filter (numeric only)
     *
     * @param int|float $min The minimum value
     * @param int|float $max The maximum value
     * @return string The formatted filter
     */
    public static function range($min, $max): string {
        return "[range]{$min},{$max}";
    }

    /**
     * Create a range filter for dates
     *
     * @param string $startDate The start date (format: YYYY-MM-DD)
     * @param string $endDate The end date (format: YYYY-MM-DD)
     * @return string The formatted filter
     */
    public static function dateRange(string $startDate, string $endDate): string {
        return "[range]{$startDate},{$endDate}";
    }
}
