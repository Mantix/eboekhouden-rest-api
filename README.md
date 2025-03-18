# e-Boekhouden REST API Client voor PHP

Een moderne PHP client voor de nieuwe REST API van e-Boekhouden.

## Installatie

Je kunt dit package installeren via Composer:

```bash
composer require mantix/eboekhouden-rest-api
```

## Configuratie

### API-token aanmaken

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

## Filters gebruiken

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

### Beschikbare filters

Deze library ondersteunt alle filters die beschikbaar zijn in de API. Hieronder een overzicht:

#### Tekst filters
| Filter | Methode | Voorbeeld |
|---|---|---|
| Gelijk aan | `Filter::eq('waarde')` | `'code' => Filter::eq('REL001')` |
| Niet gelijk aan | `Filter::notEq('waarde')` | `'type' => Filter::notEq('B')` |
| Bevat | `Filter::like('%waarde%')` | `'name' => Filter::like('%Bedrijf%')` |
| Begint met | `Filter::like('waarde%')` | `'name' => Filter::like('Bedrijf%')` |
| Eindigt met | `Filter::like('%waarde')` | `'name' => Filter::like('%BV')` |
| Bevat niet | `Filter::notLike('%waarde%')` | `'name' => Filter::notLike('%test%')` |

#### Numerieke filters
| Filter | Methode | Voorbeeld |
|---|---|---|
| Gelijk aan | `Filter::eq(123)` | `'id' => Filter::eq(123)` |
| Niet gelijk aan | `Filter::notEq(123)` | `'id' => Filter::notEq(123)` |
| Groter dan | `Filter::gt(123)` | `'amount' => Filter::gt(100)` |
| Groter dan of gelijk aan | `Filter::gte(123)` | `'amount' => Filter::gte(100)` |
| Kleiner dan | `Filter::lt(123)` | `'amount' => Filter::lt(500)` |
| Kleiner dan of gelijk aan | `Filter::lte(123)` | `'amount' => Filter::lte(500)` |
| Bereik | `Filter::range(min, max)` | `'amount' => Filter::range(100, 500)` |

#### Datum filters
| Filter | Methode | Voorbeeld |
|---|---|---|
| Gelijk aan | `Filter::eq('2023-01-01')` | `'date' => Filter::eq('2023-01-01')` |
| Niet gelijk aan | `Filter::notEq('2023-01-01')` | `'date' => Filter::notEq('2023-01-01')` |
| Na | `Filter::gt('2023-01-01')` | `'date' => Filter::gt('2023-01-01')` |
| Op of na | `Filter::gte('2023-01-01')` | `'date' => Filter::gte('2023-01-01')` |
| Voor | `Filter::lt('2023-01-01')` | `'date' => Filter::lt('2023-01-01')` |
| Op of voor | `Filter::lte('2023-01-01')` | `'date' => Filter::lte('2023-01-01')` |
| Bereik | `Filter::dateRange(start, eind)` | `'date' => Filter::dateRange('2023-01-01', '2023-12-31')` |

## Beschikbare API methodes

### Sessie beheer
- `createSession()` - Start een nieuwe sessie
- `endSession()` - Sluit de huidige sessie

### Administratie
- `getAdministrations(int $limit = 100, int $offset = 0)` - Haal alle administraties op
- `getLinkedAdministrations(int $limit = 100, int $offset = 0)` - Haal gekoppelde administraties op

### Kostenplaatsen
- `getCostCenters(array $params = [])` - Haal kostenplaatsen op met optionele filters
- `getCostCenter(int $id)` - Haal een specifieke kostenplaats op

### E-mail sjablonen
- `getEmailTemplates(int $limit = 100, int $offset = 0)` - Haal email sjablonen op

### Facturen
- `createInvoice(array $data)` - Maak een nieuwe factuur aan
- `getInvoices(array $params = [])` - Haal facturen op met optionele filters
- `getInvoice(int $id)` - Haal een specifieke factuur op

### Factuur sjablonen
- `getInvoiceTemplates(array $params = [])` - Haal factuur sjablonen op met optionele filters

### Grootboekrekeningen
- `getLedgers(array $params = [])` - Haal grootboekrekeningen op met optionele filters
- `createLedger(array $data)` - Maak een nieuwe grootboekrekening aan
- `getLedger(int $id)` - Haal een specifieke grootboekrekening op
- `updateLedger(int $id, array $data)` - Werk een bestaande grootboekrekening bij
- `getLedgerBalance(int $id, array $params = [])` - Haal het saldo van een grootboekrekening op

### Mutaties
- `getMutations(array $params = [])` - Haal mutaties op met optionele filters
- `createMutation(array $data)` - Maak een nieuwe mutatie aan
- `getMutation(int $id)` - Haal een specifieke mutatie op
- `getOutstandingInvoices(string $credDeb, int $limit = 100, int $offset = 0)` - Haal openstaande facturen op

### Producten
- `getProducts(array $params = [])` - Haal producten op met optionele filters
- `getProduct(int $id)` - Haal een specifiek product op

### Relaties
- `getRelations(array $params = [])` - Haal relaties op met optionele filters
- `createRelation(array $data)` - Maak een nieuwe relatie aan
- `getRelation(int $id)` - Haal een specifieke relatie op
- `updateRelation(int $id, array $data)` - Werk een bestaande relatie bij

### Eenheden
- `getUnits(array $params = [])` - Haal eenheden op met optionele filters

## Voorbeelden

### Facturen aanmaken

```php
$invoiceData = [
    'relationId' => 12345,                   // Verplicht: ID van de relatie
    'date' => date('Y-m-d'),                 // Factuur datum (standaard: vandaag)
    'termOfPayment' => 14,                   // Betalingstermijn in dagen
    'templateId' => 6789,                    // Verplicht: ID van het factuursjabloon
    'invoiceNumber' => 'F2023-001',          // Optioneel; indien leeg wordt automatisch gegenereerd
    'reference' => 'Bestelling #12345',      // Referentie op de factuur
    'items' => [                             // Verplicht: minimaal 1 item
        [
            'description' => 'Product 1',    // Verplicht: omschrijving
            'pricePerUnit' => 100.00,        // Verplicht: prijs per eenheid (excl. BTW)
            'quantity' => 2,                 // Aantal (standaard: 1)
            'vatCode' => 'HOOG_VERK_21',     // Verplicht: BTW-code
            'ledgerId' => 8000,              // Verplicht: ID van de grootboekrekening
            'costCenterId' => 123,           // Optioneel: kostenplaats ID
            'unitId' => 456,                 // Optioneel: eenheid ID (stuk, uur, etc.)
            'productId' => 789,              // Optioneel: product ID
            'discountPercentage' => 10,      // Optioneel: kortingspercentage
        ],
        [
            'description' => 'Product 2',
            'pricePerUnit' => 75.00,
            'quantity' => 1,
            'vatCode' => 'HOOG_VERK_21',
            'ledgerId' => 8000,
        ],
    ],
    'email' => [                             // Optioneel: verstuur factuur direct per e-mail
        'fromEmail' => 'info@example.com',   // Optioneel: afzender e-mail
        'fromName' => 'Mijn Bedrijf',        // Optioneel: afzender naam
        'subject' => 'Uw factuur',           // Verplicht als email object aanwezig is
        'body' => 'Beste klant,<br><br>Hierbij ontvangt u uw factuur.<br><br>Met vriendelijke groet,', // Verplicht als email object aanwezig is
        'attachUbl' => true,                 // Voeg UBL-bestand toe
    ],
    'mutation' => [                          // Optioneel: verwerk factuur meteen als mutatie
        'description' => 'Factuur voor producten', // Omschrijving van de mutatie
        'ledgerId' => 1300,                  // Grootboekrekening voor de mutatie
        'checkPaymentReference' => true,     // Controleer of referentie uniek is
        'paymentReference' => 'REF12345',    // Unieke referentie voor de betaling
    ],
];

$invoice = $client->createInvoice($invoiceData);
```

### Relaties aanmaken

```php
$relationData = [
    'type' => 'B',                           // 'B' (zakelijk) of 'P' (particulier)
    'name' => 'Mijn Nieuwe Klant BV',        // Verplicht: (bedrijfs)naam
    'contact' => 'John Doe',                 // Contactpersoon
    'gender' => 'm',                         // 'm' (man), 'v' (vrouw) of 'a' (afdeling)
    'address' => 'Hoofdstraat 1',            // Adres
    'postalCode' => '1234 AB',               // Postcode
    'city' => 'Amsterdam',                   // Plaats
    'country' => 'Nederland',                // Land
    'phoneNumber' => '0201234567',           // Telefoonnummer
    'emailAddress' => 'info@example.com',    // E-mailadres
    'vatNumber' => 'NL123456789B01',         // BTW-nummer
    'termOfPayment' => 14,                   // Betalingstermijn in dagen
    'ledgerId' => 1200,                      // Grootboekrekening
];

$relation = $client->createRelation($relationData);
```

### Mutaties aanmaken

```php
$mutationData = [
    'type' => '2',                           // Verplicht: type mutatie (2 = Verkoopfactuur)
    'date' => date('Y-m-d'),                 // Verplicht: datum mutatie
    'ledgerId' => 1300,                      // Verplicht: grootboekrekening
    'invoiceNumber' => 'F2023-001',          // Factuurnummer (verplicht bij factuurmutaties)
    'description' => 'Factuur voor diensten', // Omschrijving
    'inExVat' => 'EX',                       // Verplicht: 'IN' (incl. BTW) of 'EX' (excl. BTW)
    'relationId' => 12345,                   // Relatie ID
    'rows' => [                              // Verplicht: minimaal 1 regel
        [
            'ledgerId' => 8000,              // Verplicht: grootboekrekening voor deze regel
            'vatCode' => 'HOOG_VERK_21',     // Verplicht: BTW-code
            'amount' => 150.00,              // Verplicht: bedrag
            'description' => 'Consultancy werkzaamheden', // Omschrijving
        ],
    ],
];

$mutation = $client->createMutation($mutationData);
```

### Paginering gebruiken

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

### Error response verwerken

Wanneer er een fout optreedt, bevat het `EBoekhoudenException` object nuttige informatie:

```php
try {
    $result = $client->createInvoice($invoiceData);
} catch (EBoekhoudenException $e) {
    // Haal basis foutgegevens op
    $message = $e->getMessage();      // Bijv. "Relatie niet gevonden"
    $code = $e->getCode();            // HTTP status code (bijv. 400, 404)
    $errorCode = $e->getErrorCode();  // API foutcode (bijv. "FACT_002")
    
    // Haal volledige foutrespons op voor meer details
    $errorResponse = $e->getErrorResponse();
    /* 
    Array zoals:
    [
        'errors' => [...],            // Validatiefouten (indien aanwezig)
        'type' => 'validation',       // Type fout
        'propertyName' => 'relationId', // Veld dat de fout veroorzaakte
        'code' => 'FACT_002',         // Foutcode
        'title' => 'Relatie niet gevonden', // Korte foutbeschrijving
        'message' => '...',           // Uitgebreide foutinformatie (niet altijd aanwezig)
        'status' => 400,              // HTTP status
        'traceId' => 'ABCDEF-123456'  // Unieke trace voor foutopsporing
    ]
    */
    
    // Voorbeeld van hoe je specifieke foutafhandeling kunt implementeren
    switch ($errorCode) {
        case 'FACT_002':
            echo "De opgegeven relatie bestaat niet. Controleer het relationId.";
            break;
        case 'FACT_019':
            echo "Er zijn geen factuuritems opgegeven. Voeg minimaal één item toe.";
            break;
        default:
            echo "Er is een fout opgetreden: " . $message;
    }
}
```

### Veelvoorkomende foutcodes

Hier zijn enkele veelvoorkomende foutcodes die je kunt tegenkomen:

#### Algemeen
- `ERR_001` - Er is iets misgegaan.
- `DATA_01` - Geen content body meegestuurd.
- `PAGE_001` - Limit moet tussen 1 en 2000 liggen.
- `PAGE_002` - Offset moet groter of gelijk aan 0 zijn.

#### Beveiliging
- `SECURITY_001` - Geen toegang tot de gevraagde resource.
- `SECURITY_002` - Geen administratie geopend in de huidige sessie.
- `SECURITY_010` - Niet geauthenticeerd. Controleer de Authorization header.

#### Relaties
- `REL_001` - Relatietype is ongeldig (B,P).
- `REL_002` - Naam is verplicht.
- `REL_007` - IBAN is ongeldig.
- `REL_023` - E-mail is ongeldig.
- `REL_049` - Code bestaat al.

#### Facturen
- `FACT_001` - Relatie ID ontbreekt.
- `FACT_002` - Relatie niet gevonden.
- `FACT_007` - Factuurnummer is te lang.
- `FACT_011` - Factuurdatum ontbreekt.
- `FACT_018` - Factuursjabloon niet gevonden.
- `FACT_019` - Factuuritems ontbreken.
- `FACT_101` - Factuurnummer is in gebruik.

#### Mutaties
- `MUT_001` - Type is ongeldig.
- `MUT_007` - Onbekende grootboekrekening voor mutatie.
- `MUT_017` - In/Ex BTW moet 'IN' of 'EX' zijn.
- `MUT_100` - Regels ontbreken.

#### Grootboekrekeningen
- `LEDG_001` - Code ontbreekt.
- `LEDG_003` - Omschrijving ontbreekt.
- `LEDG_005` - Categorie ontbreekt.
- `LEDG_007` - Groep ontbreekt.
- `LEDG_011` - Grootboekrekening niet gevonden.
- `LEDG_013` - Grootboekcode bestaat al.

## Geavanceerd gebruik

### Automatische herverbinding

Als je langlopende processen hebt waarbij de sessie kan verlopen, kun je automatische herverbinding implementeren:

```php
class ExtendedClient extends \Mantix\EBoekhoudenRestApi\Client {
    /**
     * Maximum aantal herverbindingspogingen
     */
    private int $maxRetries = 1;
    
    /**
     * Voer een API-verzoek uit met automatische herverbinding bij sessieverlies
     *
     * @param callable $apiCall Een functie die de API-aanroep uitvoert
     * @return mixed Het resultaat van de API-aanroep
     * @throws \Exception
     */
    public function executeWithRetry(callable $apiCall) {
        $retries = 0;
        
        while (true) {
            try {
                return $apiCall();
            } catch (\Mantix\EBoekhoudenRestApi\EBoekhoudenException $e) {
                // Controleer of de fout een verlopen sessie is
                if ($e->getErrorCode() === 'SECURITY_010' && $retries < $this->maxRetries) {
                    // Sessie is verlopen, maak een nieuwe sessie aan
                    $this->createSession();
                    $retries++;
                    continue;
                }
                
                // Andere fout of maximaal aantal pogingen bereikt
                throw $e;
            }
        }
    }
}

// Gebruik:
$client = new ExtendedClient('JE_API_TOKEN', 'JouwApp');

try {
    $result = $client->executeWithRetry(function() use ($client) {
        return $client->getRelations(['limit' => 100]);
    });
} catch (\Exception $e) {
    echo "Fout: " . $e->getMessage();
}
```

### Helper classes

Je kunt helper classes maken om complexe API-structuren eenvoudiger te maken:

```php
// Voorbeeld van een Invoice Builder class
class InvoiceBuilder {
    private array $data = [];
    private array $items = [];
    
    public function __construct(int $relationId, int $templateId) {
        $this->data['relationId'] = $relationId;
        $this->data['templateId'] = $templateId;
        $this->data['date'] = date('Y-m-d');
        $this->data['termOfPayment'] = 14; // Standaard betalingstermijn
    }
    
    public function setInvoiceNumber(string $number): self {
        $this->data['invoiceNumber'] = $number;
        return $this;
    }
    
    public function setDate(string $date): self {
        $this->data['date'] = $date;
        return $this;
    }
    
    public function addItem(string $description, float $pricePerUnit, int $ledgerId, string $vatCode, float $quantity = 1): self {
        $this->items[] = [
            'description' => $description,
            'pricePerUnit' => $pricePerUnit,
            'quantity' => $quantity,
            'vatCode' => $vatCode,
            'ledgerId' => $ledgerId
        ];
        return $this;
    }
    
    public function build(): array {
        $this->data['items'] = $this->items;
        return $this->data;
    }
}

// Gebruik:
$invoice = (new InvoiceBuilder(12345, 6789))
    ->setInvoiceNumber('F2023-001')
    ->addItem('Consultancy', 95.00, 8000, 'HOOG_VERK_21', 8)
    ->addItem('Training', 595.00, 8000, 'HOOG_VERK_21', 1)
    ->build();

$result = $client->createInvoice($invoice);
```

### Facturen downloaden als PDF

Je kunt de URL van de PDF-factuur ophalen en gebruiken om deze te downloaden:

```php
$invoice = $client->getInvoice(12345);
$pdfUrl = $invoice['urlPdfFile'];

// Download de PDF
if (!empty($pdfUrl)) {
    $pdfContent = file_get_contents($pdfUrl);
    file_put_contents('factuur.pdf', $pdfContent);
    echo "Factuur gedownload als 'factuur.pdf'";
} else {
    echo "Geen PDF-URL beschikbaar voor deze factuur";
}
```

## Tips en best practices

### Caching voor referentiedata

Cache gegevens zoals grootboeknummers en kostenplaatsen om herhaalde API-verzoeken te verminderen:

```php
$cache = new \Symfony\Component\Cache\Adapter\FilesystemAdapter();

// Haal grootboekrekeningen op met caching
$ledgers = $cache->get('ledgers', function() use ($client) {
    return $client->getLedgers(['limit' => 500]);
});
```

### Batch-verwerking

Verwerk grote hoeveelheden gegevens in batches om binnen de API-limieten te blijven:

```php
function processInBatches(array $items, int $batchSize = 25, callable $processor) {
    $batches = array_chunk($items, $batchSize);
    $results = [];
    
    foreach ($batches as $batch) {
        $batchResults = $processor($batch);
        $results = array_merge($results, $batchResults);
        
        // Optioneel: voeg een korte pauze toe om API overbelasting te voorkomen
        usleep(500000); // 500ms
    }
    
    return $results;
}
```

### BTW-codes

Overzicht van beschikbare BTW-codes (Nederland):

| Code | Omschrijving | Tarief | Type |
|---|---|---|---|
| HOOG_VERK_21 | BTW 21% | 21% | Verkoop |
| LAAG_VERK_9 | BTW 9% | 9% | Verkoop |
| VERL_VERK | Verlegd 21% | 21% | Verkoop |
| LAAG_INK_9 | BTW 9% | 9% | Inkoop |
| HOOG_INK_21 | BTW 21% | 21% | Inkoop |
| VERL_INK | Verlegd 21% | 21% | Inkoop |
| GEEN | Geen BTW | 0% | - |

### Mutatie types

Overzicht van beschikbare mutatie types:

| Waarde | Omschrijving |
|---|---|
| 1 | Inkoopfactuur |
| 2 | Verkoopfactuur |
| 3 | Inkoopfactuur betaling |
| 4 | Verkoopfactuur betaling |
| 5 | Geld ontvangen |
| 6 | Geld uitgegeven |
| 7 | Memoriaal boeking |

### Grootboekrekening categorieën

Overzicht van grootboekrekening categorieën:

| Waarde | Omschrijving |
|---|---|
| BAL | Balans |
| VW | Winst en verlies |
| AF6 | Omzetbelasting laag tarief |
| AF19 | Omzetbelasting hoog tarief |
| DEB | Debiteuren |
| CRED | Crediteuren |

## Bijdragen

Bijdragen aan dit package zijn altijd welkom! Of het nu gaat om bug reports, feature requests, of pull requests.

1. Fork de repository
2. Maak een feature branch (`git checkout -b feature/amazing-feature`)
3. Commit je wijzigingen (`git commit -m 'Add some amazing feature'`)
4. Push naar de branch (`git push origin feature/amazing-feature`)
5. Open een Pull Request

## Tests

Dit package gebruikt PHPUnit voor tests. Je kunt de tests als volgt uitvoeren:

```bash
composer install
vendor/bin/phpunit
```

## Licentie

Dit package is beschikbaar onder de MIT-licentie. Zie het [LICENSE](LICENSE) bestand voor meer informatie.