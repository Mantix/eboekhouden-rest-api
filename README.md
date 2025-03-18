# e-Boekhouden REST API Client voor PHP

Een PHP client voor de nieuwe REST API van e-Boekhouden.

## Installatie

Je kunt dit package installeren via Composer:

```bash
composer require mantix/eboekhouden-rest-api
```

## API-token aanmaken

Voordat je deze package kunt gebruiken, moet je eerst een API-token aanmaken in je e-Boekhouden account:

1. Log in op je e-Boekhouden account
2. Ga naar Beheer > API-tokens
3. Klik op "Nieuw API-token toevoegen"
4. Geef het token een naam en klik op "Aanmaken"
5. Sla het token goed op, want het wordt maar één keer getoond

## Basis gebruik

```php
use Mantix\EBoekhoudenRestApi\Client;

// Initialiseer de client
$client = new Client(
    'JE_API_TOKEN', 
    'JouwApp'    // Source identifier, max 10 karakters
);

// Automatisch wordt er een sessie aangemaakt wanneer nodig

// Haal relaties op
$relations = $client->getRelations();

// Beëindig de sessie als je klaar bent
$client->endSession();
```

## Foutafhandeling

Gebruik try/catch blokken om API fouten af te handelen:

```php
use Mantix\EBoekhoudenRestApi\Client;
use Mantix\EBoekhoudenRestApi\EBoekhoudenException;

$client = new Client('JE_API_TOKEN', 'JouwApp');

try {
    $relations = $client->getRelations();
} catch (EBoekhoudenException $e) {
    echo "API fout: " . $e->getMessage();
    echo "API foutcode: " . $e->getErrorCode();
    print_r($e->getErrorResponse()); // Volledige foutrespons
} catch (\Exception $e) {
    echo "Algemene fout: " . $e->getMessage();
}
```

## Filter gebruik

Gebruik de `Filter` class om geavanceerde filters toe te passen op je API verzoeken:

```php
use Mantix\EBoekhoudenRestApi\Filter;

// Zoek relaties met "Bedrijf" in de naam
$relations = $client->getRelations([
    'name' => Filter::like('%Bedrijf%'),
]);

// Zoek facturen binnen een datumbereik
$invoices = $client->getInvoices([
    'date' => Filter::dateRange('2023-01-01', '2023-12-31'),
]);
```

## Beschikbare methodes

### Sessie beheer
- `createSession()`
- `endSession()`

### Administratie
- `getAdministrations(int $limit = 100, int $offset = 0)`
- `getLinkedAdministrations(int $limit = 100, int $offset = 0)`

### Kostenplaatsen
- `getCostCenters(array $params = [])`
- `getCostCenter(int $id)`

### E-mail sjablonen
- `getEmailTemplates(int $limit = 100, int $offset = 0)`

### Facturen
- `createInvoice(array $data)`
- `getInvoices(array $params = [])`
- `getInvoice(int $id)`

### Factuur sjablonen
- `getInvoiceTemplates(array $params = [])`

### Grootboekrekeningen
- `getLedgers(array $params = [])`
- `createLedger(array $data)`
- `getLedger(int $id)`
- `updateLedger(int $id, array $data)`
- `getLedgerBalance(int $id, array $params = [])`

### Mutaties
- `getMutations(array $params = [])`
- `createMutation(array $data)`
- `getMutation(int $id)`
- `getOutstandingInvoices(string $credDeb, int $limit = 100, int $offset = 0)`

### Producten
- `getProducts(array $params = [])`
- `getProduct(int $id)`

### Relaties
- `getRelations(array $params = [])`
- `createRelation(array $data)`
- `getRelation(int $id)`
- `updateRelation(int $id, array $data)`

### Eenheden
- `getUnits(array $params = [])`

## Geavanceerd gebruik

### Facturen aanmaken

```php
$invoiceData = [
    'relationId' => 12345,
    'date' => date('Y-m-d'),
    'termOfPayment' => 14,
    'templateId' => 6789,
    'items' => [
        [
            'description' => 'Product 1',
            'pricePerUnit' => 100.00,
            'quantity' => 2,
            'vatCode' => 'HOOG_VERK_21',
            'ledgerId' => 8000,
        ],
        [
            'description' => 'Product 2',
            'pricePerUnit' => 75.00,
            'quantity' => 1,
            'vatCode' => 'HOOG_VERK_21',
            'ledgerId' => 8000,
        ],
    ],
    'email' => [
        'subject' => 'Uw factuur',
        'body' => 'Beste klant,<br><br>Hierbij ontvangt u uw factuur.<br><br>Met vriendelijke groet,',
        'attachUbl' => true,
    ],
];

$invoice = $client->createInvoice($invoiceData);
```

### Mutaties aanmaken

```php
$mutationData = [
    'type' => '2', // 2 = Verzonden factuur
    'date' => date('Y-m-d'),
    'ledgerId' => 1300,
    'invoiceNumber' => 'F2023-001',
    'description' => 'Factuur voor diensten',
    'inExVat' => 'EX', // Bedragen zijn exclusief BTW
    'relationId' => 12345,
    'rows' => [
        [
            'ledgerId' => 8000,
            'vatCode' => 'HOOG_VERK_21',
            'amount' => 150.00,
            'description' => 'Consultancy werkzaamheden',
        ],
    ],
];

$mutation = $client->createMutation($mutationData);
```

### Paginering

De meeste endpoints ondersteunen paginering met de `limit` en `offset` parameters:

```php
// Eerste pagina (eerste 100 items)
$page1 = $client->getRelations([
    'limit' => 100,
    'offset' => 0,
]);

// Tweede pagina (volgende 100 items)
$page2 = $client->getRelations([
    'limit' => 100,
    'offset' => 100,
]);
```

## Licentie

MIT