<?php

namespace Mantix\EBoekhoudenRestApi;

/**
 * Represents a filter operator and value for API query parameters.
 */
class FilterValue {
    private string $operator;

    /** @var mixed */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct(string $operator, $value) {
        $this->operator = $operator;
        $this->value = $value;
    }

    public function getOperator(): string {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }
}
