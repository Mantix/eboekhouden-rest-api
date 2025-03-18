<?php

namespace Mantix\EBoekhoudenRestApi;

/**
 * Exception thrown when an error occurs in the EBoekhouden API
 */
class EBoekhoudenException extends \Exception {
    /**
     * @var string|null The error code from the API
     */
    private ?string $errorCode;

    /**
     * @var array|null The full error response
     */
    private ?array $errorResponse;

    /**
     * Constructor
     *
     * @param string $message The error message
     * @param int $code The error code
     * @param \Throwable|null $previous The previous exception
     * @param string|null $errorCode The API error code
     * @param array|null $errorResponse The full error response
     */
    public function __construct(
        string $message,
        int $code = 0,
        ?\Throwable $previous = null,
        ?string $errorCode = null,
        ?array $errorResponse = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->errorResponse = $errorResponse;
    }

    /**
     * Get the API error code
     *
     * @return string|null The error code
     */
    public function getErrorCode(): ?string {
        return $this->errorCode;
    }

    /**
     * Get the full error response
     *
     * @return array|null The error response
     */
    public function getErrorResponse(): ?array {
        return $this->errorResponse;
    }
}
