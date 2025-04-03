<?php

namespace Mantix\EBoekhoudenRestApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * EBoekhouden API client
 */
class Client {
    /**
     * @var GuzzleClient The HTTP client
     */
    private GuzzleClient $client;

    /**
     * @var string|null The API token
     */
    private string $accessToken;

    /**
     * @var string|null The API session token
     */
    private ?string $sessionToken = null;

    /**
     * @var string The source identifier
     */
    private string $source;

    /**
     * @var string The API base URL
     */
    private string $baseUrl = 'https://api.e-boekhouden.nl/v1/';

    /**
     * Constructor
     *
     * @param string $accessToken The API access token
     * @param string $source The source identifier (max 10 characters)
     * @param array $clientOptions Additional options for the HTTP client
     */
    public function __construct(string $accessToken, string $source, array $clientOptions = []) {
        $this->accessToken = $accessToken;
        $this->source = $source;

        $this->client = new GuzzleClient(array_merge([
            'base_uri' => $this->baseUrl,
            'http_errors' => false,
        ], $clientOptions));
    }

    /**
     * Create a session
     *
     * @return array The session response
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function createSession(): array {
        $response = $this->client->post('session', [
            'json' => [
                'accessToken' => $this->accessToken,
                'source' => $this->source,
            ],
        ]);

        $data = $this->handleResponse($response);

        $this->sessionToken = $data['token'];

        return $data;
    }

    /**
     * End the current session
     *
     * @return void
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function endSession(): void {
        $response = $this->client->delete('session', [
            'headers' => $this->getAuthHeaders(),
        ]);

        $this->handleResponse($response);
        $this->sessionToken = null;
    }

    /**
     * Get all administrations
     *
     * @param int $limit The number of items to retrieve
     * @param int $offset The number of items to skip
     * @return array The administrations
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getAdministrations(int $limit = 100, int $offset = 0): array {
        return $this->get('administration', [
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Get all linked administrations
     *
     * @param int $limit The number of items to retrieve
     * @param int $offset The number of items to skip
     * @return array The linked administrations
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getLinkedAdministrations(int $limit = 100, int $offset = 0): array {
        return $this->get('administration/linked', [
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Get all cost centers
     *
     * @param array $params Request parameters
     * @return array The cost centers
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getCostCenters(array $params = []): array {
        return $this->get('costcenter', $params);
    }

    /**
     * Get a specific cost center
     *
     * @param int $id The cost center ID
     * @return array The cost center
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getCostCenter(int $id): array {
        return $this->get("costcenter/{$id}");
    }

    /**
     * Get all email templates
     *
     * @param int $limit The number of items to retrieve
     * @param int $offset The number of items to skip
     * @return array The email templates
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getEmailTemplates(int $limit = 100, int $offset = 0): array {
        return $this->get('emailtemplate', [
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Create an invoice
     *
     * @param array $data The invoice data
     * @return array The created invoice
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function createInvoice(array $data): array {
        return $this->post('invoice', $data);
    }

    /**
     * Get all invoices
     *
     * @param array $params Request parameters
     * @return array The invoices
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getInvoices(array $params = []): array {
        return $this->get('invoice', $params);
    }

    /**
     * Get a specific invoice
     *
     * @param int $id The invoice ID
     * @return array The invoice
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getInvoice(int $id): array {
        return $this->get("invoice/{$id}");
    }

    /**
     * Get all invoice templates
     *
     * @param array $params Request parameters
     * @return array The invoice templates
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getInvoiceTemplates(array $params = []): array {
        return $this->get('invoicetemplate', $params);
    }

    /**
     * Get all ledgers
     *
     * @param array $params Request parameters
     * @return array The ledgers
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getLedgers(array $params = []): array {
        return $this->get('ledger', $params);
    }

    /**
     * Create a ledger
     *
     * @param array $data The ledger data
     * @return array The created ledger
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function createLedger(array $data): array {
        return $this->post('ledger', $data);
    }

    /**
     * Get a specific ledger
     *
     * @param int $id The ledger ID
     * @return array The ledger
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getLedger(int $id): array {
        return $this->get("ledger/{$id}");
    }

    /**
     * Update a ledger
     *
     * @param int $id The ledger ID
     * @param array $data The ledger data
     * @return void
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function updateLedger(int $id, array $data): void {
        $this->patch("ledger/{$id}", $data);
    }

    /**
     * Get the balance of a ledger
     *
     * @param int $id The ledger ID
     * @param array $params Request parameters
     * @return array The ledger balance
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getLedgerBalance(int $id, array $params = []): array {
        return $this->get("ledger/{$id}/balance", $params);
    }

    /**
     * Get all mutations
     *
     * @param array $params Request parameters
     * @return array The mutations
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getMutations(array $params = []): array {
        return $this->get('mutation', $params);
    }

    /**
     * Create a mutation
     *
     * @param array $data The mutation data
     * @return array The created mutation
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function createMutation(array $data): array {
        return $this->post('mutation', $data);
    }

    /**
     * Get a specific mutation
     *
     * @param int $id The mutation ID
     * @return array The mutation
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getMutation(int $id): array {
        return $this->get("mutation/{$id}");
    }

    /**
     * Get all outstanding invoices
     *
     * @param string $credDeb "C" for creditors, "D" for debtors
     * @param int $limit The number of items to retrieve
     * @param int $offset The number of items to skip
     * @return array The outstanding invoices
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getOutstandingInvoices(string $credDeb, int $limit = 100, int $offset = 0): array {
        return $this->get('mutation/invoice/outstanding', [
            'credDeb' => $credDeb,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Get all products
     *
     * @param array $params Request parameters
     * @return array The products
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getProducts(array $params = []): array {
        return $this->get('product', $params);
    }

    /**
     * Get a specific product
     *
     * @param int $id The product ID
     * @return array The product
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getProduct(int $id): array {
        return $this->get("product/{$id}");
    }

    /**
     * Get all relations
     *
     * @param array $params Request parameters
     * @return array The relations
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getRelations(array $params = []): array {
        return $this->get('relation', $params);
    }

    /**
     * Create a relation
     *
     * @param array $data The relation data
     * @return array The created relation
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function createRelation(array $data): array {
        return $this->post('relation', $data);
    }

    /**
     * Get a specific relation
     *
     * @param int $id The relation ID
     * @return array The relation
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getRelation(int $id): array {
        return $this->get("relation/{$id}");
    }

    /**
     * Update a relation
     *
     * @param int $id The relation ID
     * @param array $data The relation data
     * @return void
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function updateRelation(int $id, array $data): void {
        $this->patch("relation/{$id}", $data);
    }

    /**
     * Get all units
     *
     * @param array $params Request parameters
     * @return array The units
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    public function getUnits(array $params = []): array {
        return $this->get('unit', $params);
    }

    /**
     * Make a GET request
     *
     * @param string $endpoint The API endpoint
     * @param array $params The query parameters
     * @return array The response data
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    private function get(string $endpoint, array $params = []): array {
        $this->ensureSessionToken();

        $response = $this->client->get($endpoint, [
            'headers' => $this->getAuthHeaders(),
            'query' => $params,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Make a POST request
     *
     * @param string $endpoint The API endpoint
     * @param array $data The request data
     * @return array The response data
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    private function post(string $endpoint, array $data): array {
        $this->ensureSessionToken();

        $response = $this->client->post($endpoint, [
            'headers' => $this->getAuthHeaders(),
            'json' => $data,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Make a PATCH request
     *
     * @param string $endpoint The API endpoint
     * @param array $data The request data
     * @return array The response data
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    private function patch(string $endpoint, array $data): array {
        $this->ensureSessionToken();

        $response = $this->client->patch($endpoint, [
            'headers' => $this->getAuthHeaders(),
            'json' => $data,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Handle an API response
     *
     * @param \Psr\Http\Message\ResponseInterface $response The HTTP response
     * @return array The response data
     * @throws EBoekhoudenException
     */
    private function handleResponse(\Psr\Http\Message\ResponseInterface $response): array {
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        if ($statusCode >= 200 && $statusCode < 300) {
            return $data;
        }

        $errorMessage = '';
        $errorCode = '';

        if (isset($data['code'])) {
            $errorCode = $data['code'];
        }

        if (isset($data['message'])) {
            $errorMessage = $data['message'];
        } elseif (isset($data['title'])) {
            $errorMessage = $data['title'];
        } else {
            $errorMessage = "HTTP Error {$statusCode}";
        }

        throw new EBoekhoudenException($errorMessage, $statusCode, null, $errorCode, $data);
    }

    /**
     * Get the authentication headers
     *
     * @return array The headers
     */
    private function getAuthHeaders(): array {
        return [
            'Authorization' => "Bearer {$this->sessionToken}",
        ];
    }

    /**
     * Ensure a session token is available
     *
     * @return void
     * @throws GuzzleException
     * @throws EBoekhoudenException
     */
    private function ensureSessionToken(): void {
        if ($this->sessionToken === null) {
            $this->createSession();
        }
    }
}
