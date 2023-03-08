<?php

namespace App\Services;

use App\Models\Contract;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ContractRoz2017Service extends DefaultContractService
{
    private Contract $contract;

    private object $building_unit;

    private object $owner;

    private object $tenant;

    private int $option;

    private bool $interestOverDeposit;

    public function __construct(Contract $contract)
    {
        parent::__construct();

        $this->contract = $contract->load(['tenants', 'unit.building.owner', 'unit.contract_provisions']);
        $this->setContractStatus($this->contract->status->value);
        $this->option = 2; //ToDo: import option from DB/contract
        $this->interestOverDeposit = false;

        $this->building_unit = $this->contract->unit;
        $this->owner = $this->contract->unit->building->owner;
        $this->tenant = $this->contract->tenants;
        $this->reference = $this->contract->reference;

        parent::SetSubject(Str::of($this->building_unit->reference)->append(' - huurovereenkomst'));
        parent::SetTitle('Huurovereenkomst woonruimte');
        parent::SetKeywords(
            $this->building_unit->reference.
                ', '.
                $this->building_unit->building->address.
                ', '.
                $this->contract->status->description().
                ', '.
                $this->contract->document,
        );
    }

    protected function writePreface($type = 'HUUROVEREENKOMST WOONRUIMTE', $text = null): void
    {
        $text =
            'Model door de Raad voor Onroerende Zaken (ROZ) op 20 maart 2017 vastgesteld. Verwijzing naar dit model en het gebruik daarvan zijn uitsluitend toegestaan indien de ingevulde, de toegevoegde of de afwijkende tekst duidelijk als zodanig herkenbaar is. Toevoegingen en afwijkingen dienen bij voorkeur te worden opgenomen onder het hoofd \'bijzondere bepalingen\'. Iedere aansprakelijkheid voor nadelige gevolgen van het gebruik van de tekst van het model wordt door de ROZ uitgesloten.';
        parent::writePreface(type: $type, text: $text);
    }

    protected function writeContractors(): void
    {
        $this->writeOwner($this->owner);
        $this->writeTenant($this->tenant);
    }

    protected function writeConsideration(): void
    {
        parent::writeConsideration();
        $contractOption = $this->considerationSelectContractOption(option: $this->option);
        $this->multiCellFromCurrentPosition($contractOption->title);
        $contractOption->items->each(fn ($el) => $this->articleItem('-', $el, ''));
    }

    protected function writeArticle_1(): void
    {
        $this->createSection(text: 'Het gehuurde, bestemming');
        $this->articleIndentNumber(bullet: '1.1');
        $this->articleTextAndCode(
            text: 'Verhuurder verhuurt aan huurder en huurder huurt van verhuurder de',
            code: $this->building_unit->independent_living_space ? 'zelfstandige' : 'onzelfstandige',
        );
        $this->articleText(text: 'woonruimte, hierna \'het');
        $this->newLineWithIndentation();
        $this->articleTextAndCode(
            text: 'gehuurde\', genoemd, plaatselijk bekend',
            code: $this->building_unit->building->address.SPACE.'('.$this->building_unit->building->zipcode.')',
        );
        $this->articleTextAndCodeWithDot(text: 'te', code: $this->building_unit->building->city);
        $this->Ln();
        $this->articleItem(
            bullet: '1.2',
            text: 'Het gehuurde is uitsluitend bestemd om te worden gebruikt als woonruimte.',
        );
        $this->articleItem(
            bullet: '1.3',
            text: 'Het is huurder niet toegestaan zonder voorafgaande schriftelijke toestemming van verhuurder een andere bestemming aan het gehuurde te geven dan omschreven in artikel 1.2.',
        );
        $this->articleIndentNumber(bullet: '1.4');
        $this->articleTextAndCode(
            text: 'Huurder heeft bij het aangaan van de huurovereenkomst',
            code: $this->building_unit->building->energy_label ? 'wel' : 'niet',
        );
        $this->articleText(text: 'een kopie van het energielabel als bedoeld in');
        $this->newLineWithIndentation();
        $this->multiCellFromCurrentPosition(
            text: 'het Besluit energieprestatie gebouwen en/of een kopie van de Energie-Index ten aanzien van het gehuurde ontvangen.',
        );
    }

    protected function writeArticle_2(): void
    {
        $this->createSection(text: 'Voorwaarden');
        $this->articleItem(
            bullet: '2.1',
            text: 'Deze huurovereenkomst verplicht partijen tot naleving van de bepalingen van de wet met betrekking tot verhuur en huur van woonruimte voor zover daarvan in deze huurovereenkomst niet wordt afgeweken. Van deze huurovereenkomst maken deel uit de \'ALGEMENE BEPALINGEN HUUROVEREENKOMST WOONRUIMTE\', vastgesteld op 20 maart 2017 en gedeponeerd op 12 april 2017 bij de griffie van de rechtbank te Den Haag en aldaar ingeschreven onder nummer 2017.21, hierna te noemen \'algemene bepalingen\'. Deze algemene bepalingen zijn partijen bekend. Huurder heeft hiervan een exemplaar ontvangen. De algemene bepalingen zijn van toepassing behoudens voor zover daarvan in deze huurovereenkomst uitdrukkelijk is afgeweken of toepassing ervan ten aanzien van het gehuurde niet mogelijk is.',
        );
    }

    protected function writeArticle_3(): void
    {
        $this->createSection(text: 'Duur, verlenging en opzegging');
        switch ($this->option) {
            case 1:
                $this->article3_option1();
                break;
            case 2:
                $this->article3_option2();
                break;
            case 3:
                $this->article3_option3();
                break;
            case 4:
                $this->article3_option4();
                break;
            default:
                $this->article3_option2();
        }
    }

    protected function writeArticle_4(): void
    {
        $this->createSection(text: 'Betalingsverplichting, betaalperiode');
        $this->articleItem(
            bullet: '4.1',
            text: 'Met ingang van de ingangsdatum van deze huurovereenkomst bestaat de betalingsverplichting van huurder uit:',
        );
        $this->articleIndentMargin();
        $this->articleItem(bullet: '-', text: 'de huurprijs', style: '');
        if ($this->contract->energy_costs_advanced) {
            $this->articleIndentMargin();
            $this->articleItem(
                bullet: '-',
                text: 'de vergoeding in verband met de levering van elektriciteit, gas en water voor het verbruik in het woonruimtegedeelte van het gehuurde op basis van een zich in dat gedeelte bevindende individuele meter (kosten voor nutsvoorzieningen met een individuele meter).',
                style: '',
            );
        }
        if ($this->contract->service_charge_amount) {
            $this->articleIndentMargin();
            $this->articleItem(
                bullet: '-',
                text: 'de vergoeding voor de overige zaken en diensten die geleverd worden in verband met de bewoning van het gehuurde (servicekosten).',
                style: '',
            );
        }
        $this->articleItem(
            bullet: '4.2',
            text: 'De vergoeding in verband met de levering van elektriciteit, gas en water voor het verbruik in het woonruimtegedeelte van het gehuurde op basis van een zich in dat gedeelte bevindende individuele meter bestaat uit de feitelijke kosten op basis van de meterstanden.',
        );
        $this->articleItem(
            bullet: '4.3',
            text: 'De vergoeding voor de overige zaken en diensten die geleverd worden in verband met de bewoning van het gehuurde, zoals aangegeven in artikel 7 wordt vastgesteld door de verhuurder. Op de vergoeding als bedoeld in artikel 4.2 en 4.3 wordt een systeem van voorschotbetalingen met latere verrekening toegepast, zoals aangegeven in de artikelen 17.1 tot en met 17.15 van de algemene bepalingen.',
        );
        $this->articleIndentNumber(bullet: '4.4');
        $this->articleText(
            text: 'De huurprijs en het voorschot als bedoeld in artikel 4.2 en 4.3 zijn bij vooruitbetaling verschuldigd, steeds te',
        );
        $this->newLineWithIndentation();
        $this->articleTextAndCode(
            text: 'voldoen vóór of op de eerste dag van de periode waarop de betaling betrekking heeft',
            code: 'door middel van',
        );
        $this->Ln();
        $this->articleIndentMargin();
        $this->articleTextAndCodeWithDot(
            text: '',
            code: Str::of('overschrijving op rekeningnummer ')
                ->append($this->owner->iban)
                ->append(SPACE)
                ->append('ten name van')
                ->append(SPACE)
                ->append($this->owner->iban_owner),
        );
        $this->Ln();
        $this->articleIndentNumber(bullet: '4.5');
        $this->articleText(text: 'Per betaalperiode van één maand bedraagt');
        $this->newLineWithIndentation();
        $this->articleIndentNumber(bullet: '-', style: '');
        $this->articleTextAndPriceColumn(text: ['huurprijs'], price: $this->contract->price ?? 0);

        $this->articleIndentMargin();
        $this->articleIndentNumber(bullet: '-', style: '');
        $this->articleTextAndPriceColumn(
            text: [
                'het voorschot op de vergoeding in verband met de levering van elektriciteit, gas en water',
                'voor het verbruik in het woonruimtegedeelte van het gehuurde op basis van een zich in',
                'dat gedeelte bevindende individuele meter',
            ],
            price: $this->contract->energy_costs_advanced ?? 0,
            strikethrough: ! $this->contract->energy_costs_included,
        );

        $this->articleIndentMargin();
        $this->articleIndentNumber(bullet: '-', style: '');
        $this->articleTextAndPriceColumn(
            text: [
                'het voorschot op de vergoeding voor de overige zaken en diensten die geleverd',
                'worden in verband met de bewoning van het gehuurde',
            ],
            price: $this->contract->service_charge_amount ?? 0,
            strikethrough: ! $this->contract->service_charge,
        );

        $this->articleIndentMargin();
        $this->articleTextAndPriceColumn(
            text: ['Zodat huurder per maand in totaal heeft te voldoen'],
            price: $this->contract->price +
                $this->contract->energy_costs_advanced +
                $this->contract->service_charge_amount,
            border: 'T',
        );

        $this->articleIndentMargin();
        $this->articleTextAndCodeWithDot(
            text: 'Zegge',
            code: $this->humanReadablePrice(
                cents: $this->contract->price +
                    $this->contract->energy_costs_advanced +
                    $this->contract->service_charge_amount,
            ),
        );
        $this->Ln();

        $this->articleIndentNumber(bullet: '4.6');
        $this->articleText(
            text: 'Met het oog op de datum van ingang van deze huurovereenkomst heeft de eerste betaalperiode betrekking op',
        );
        $this->newLineWithIndentation();
        $this->articleTextAndCode(text: 'de periode van', code: $this->humanReadableDate($this->contract->start));
        $this->articleTextAndCode(
            text: 'tot en met',
            code: $this->humanReadableDate($this->contract->start->endOfMonth()),
        );
        $this->articleText(text: 'en is het over deze eerste periode');
        $this->newLineWithIndentation();
        $this->articleTextAndCodeWithDot(
            text: 'verschuldigde bedrag',
            code: Str::of(
                $this->formatPrice(
                    cents: $this->contract->price +
                        $this->contract->energy_costs_advanced +
                        $this->contract->service_charge_amount,
                ),
            )
                ->prepend(SPACE)
                ->prepend(EURO),
        );
        $this->articleTextAndCodeWithDot(
            text: 'Huurder zal dit bedrag voldoen vóór of op',
            code: $this->humanReadableDate($this->contract->start),
        );
        $this->Ln();
    }

    protected function writeArticle_5(): void
    {
        $this->createSection(text: 'Huurprijswijziging');
        $this->articleIndentNumber(bullet: '5.1');
        $first_index_date = $this->humanReadableDate($this->firstIndexDate($this->contract->start));
        $this->articleText(
            text: 'Indien het gehuurde woonruimte met een niet-geliberaliseerde huurprijs betreft, kan de huurprijs op voorstel',
        );
        $this->newLineWithIndentation();
        $this->articleTextAndCode(text: 'van verhuurder voor het eerst per', code: $first_index_date);
        define(SPACE, chr(32));
        $this->articleText(text: 'en vervolgens jaarlijks worden gewijzigd met een percentage');
        $this->newLineWithIndentation();
        $this->multiCellFromCurrentPosition(
            text: 'dat maximaal gelijk is aan het op de ingangsdatum van die wijziging wettelijk toegestane percentage voor woonruimte met een niet-geliberaliseerde huurprijs, bij gebreke waarvan de huurprijsaanpassing plaatsvindt overeenkomstig het gestelde in artikel 5.2. In aanvulling op het in de vorige zin bedoelde percentage kan de huurprijs op voorstel van verhuurder worden gewijzigd met een percentage dat maximaal gelijk is aan het op de ingangsdatum van die wijziging toegestane percentage voor de inkomensafhankelijke huurverhoging, indien het gehuurde zelfstandige woonruimte met een niet-geliberaliseerde huurprijs betreft. Partijen verklaren het bepaalde in artikel 7:252a BW voor zover vereist van overeenkomstige toepassing en huurder verleent voor zover vereist toestemming voor het opvragen van een in artikel 7:252a lid 3 BW bedoelde verklaring.',
        );
        $this->articleIndentNumber(bullet: '5.2');
        $this->articleText(
            text: 'Indien het gehuurde zelfstandige woonruimte met een geliberaliseerde huurprijs voor woonruimte betreft, is het',
        );
        $this->newLineWithIndentation();
        $this->articleTextAndCode(
            text: 'onder 5.1 gestelde niet van toepassing. In dat geval wordt de huurprijs voor het eerst per',
            code: $first_index_date,
        );
        $this->articleText(text: 'en');
        $this->newLineWithIndentation();
        $this->multiCellFromCurrentPosition(
            text: 'vervolgens jaarlijks aangepast overeenkomstig het gestelde in artikel 16 van de algemene bepalingen. Bovenop en gelijktijdig met de jaarlijkse aanpassing overeenkomstig artikel 16 van de algemene bepalingen, heeft de',
        );
        $this->articleIndentMargin();
        $this->articleTextAndCode(text: 'verhuurder het recht om de huurprijs te verhogen met maximaal', code: '1,0');
        $this->multiCellFromCurrentPosition(text: '%.');
    }

    protected function writeArticle_6(): void
    {
        $this->createSection(text: 'Kosten voor nutsvoorzieningen met een individuele meter');

        $text = collect([
            'Verhuurder zal zorgdragen voor de levering van [elektriciteit, gas en water*] voor het verbruik in het',
            'woonruimtegedeelte van het gehuurde op basis van een zich in dat gedeelte bevindende individuele meter.',
        ]);

        $sentence = Str::of('[elektriciteit, gas en water*]');
        $old = $sentence;

        if ($this->contract->metering_electricity || $this->contract->metering_gas || $this->contract->metering_water) {
            $words = collect([
                (object) ['exist' => $this->contract->metering_electricity, 'text' => 'elektriciteit'],
                (object) ['exist' => $this->contract->metering_gas, 'text' => 'gas'],
                (object) ['exist' => $this->contract->metering_water, 'text' => 'water'],
            ])->filter(fn ($el) => $el->exist);

            $sentence = Str::of($words->first()->text)
                ->when(
                    $words->count() === 2,
                    fn ($string) => $string
                        ->append(SPACE)
                        ->append('en')
                        ->append(SPACE)
                        ->append($words->last()->text),
                )
                ->when(
                    $words->count() === 3,
                    fn ($string) => $string
                        ->append(',')
                        ->append(SPACE)
                        ->append($words[1]->text)
                        ->append(SPACE)
                        ->append('en')
                        ->append(SPACE)
                        ->append($words->last()->text),
                );

            $line = Str::of($text->first())->replace($old->value(), $sentence->value());
            $text[0] = $line->value();
        }

        $line_dimensions = collect([]);

        $text->each(function ($line, $index) use ($sentence, $line_dimensions) {
            if ($index === 0) {
                $this->articleIndentNumber(bullet: '6');
                $this->articleTextAndCode(
                    text: Str::of($line)
                        ->before($sentence)
                        ->trim(),
                    code: $sentence,
                );
                $this->articleText(text: Str::of($line)->after($sentence));
            }

            if ($index !== 0) {
                $this->Ln();
                $this->articleIndentMargin();
                $this->articleText(text: $line);
            }

            $line_dimensions->push(
                (object) [
                    'x' => $this->dimensions->align->left + $this->indent,
                    'y' => $this->GetY() + 0.5 * $this->font->line_height,
                    'length' => $this->GetStringWidth($line) + 1,
                ],
            );
        });
        $this->Ln();

        if ($sentence->value() === $old->value()) {
            $line_dimensions->each(fn ($line) => $this->Line($line->x, $line->y, $line->x + $line->length, $line->y));
        }
    }

    protected function writeArticle_7(): void
    {
        $this->createSection(text: 'Servicekosten');
        $this->articleItem(
            bullet: '7',
            text: 'Verhuurder zal zorgdragen voor de levering van de volgende zaken en diensten in verband met de bewoning van het gehuurde:',
        );

        $services = collect($this->contract->services);
        if ($services->isEmpty()) {
            $this->articleIndentMargin();
            $this->articleIndentNumber(bullet: '-', style: '');
            $this->defaultFont(style: 'B');
            $this->articleText(text: 'geen');
            $this->Ln();
        } else {
            $services->each(function ($service) {
                $this->articleIndentMargin();
                $this->articleIndentNumber(bullet: '-', style: '');
                $this->defaultFont(style: 'B');
                $this->articleText(text: $service);
                $this->Ln();
            });
        }
        $this->defaultFont();
    }

    protected function writeArticle_8(): void
    {
        $this->createSection(text: 'Belastingen en andere heffingen');
        $this->articleItem(
            bullet: '8.1',
            text: 'Tenzij dit op grond van de wet of daaruit voortvloeide regelgeving niet is toegestaan, zijn voor rekening van huurder, ook als verhuurder daarvoor wordt aangeslagen:',
        );
        $this->articleIndentMargin();
        $this->articleItem(
            bullet: 'a.',
            text: 'de onroerende zaakbelasting en de waterschap- of polderlasten;',
            style: '',
        );
        $this->articleIndentMargin();
        $this->articleItem(
            bullet: 'b.',
            text: 'de milieuheffingen, waaronder de verontreinigingsheffing oppervlaktewateren en zuiveringsheffing afvalwater;',
            style: '',
        );
        $this->articleIndentMargin();
        $this->articleItem(
            bullet: 'c.',
            text: 'de baatbelasting of daarmee verwante belastingen of heffingen, geheel of een evenredig gedeelte daarvan, indien en voor zover huurder is gebaat bij datgene op grond waarvan de aanslag of heffing wordt opgelegd;',
            style: '',
        );
        $this->articleIndentMargin();
        $this->articleItem(
            bullet: 'd.',
            text: 'de overige bestaande of toekomstige belastingen, milieubeschermingbijdragen, lasten, heffingen en retributies.',
            style: '',
        );
        $this->articleIndentMargin();
        $this->multiCellFromCurrentPosition(
            text: 'Deze belastingen en andere heffingen worden alleen doorbelast voor zover ze betrekking hebben op het feitelijk gebruik van het gehuurde en het feitelijk medegebruik van dienstruimten, algemene en gemeenschappelijke ruimten.',
        );
        $this->articleItem(
            bullet: '8.2',
            text: 'Indien de voor rekening van huurder komende heffingen, belastingen, retributies of andere lasten bij verhuurder worden geïnd, moeten deze door huurder op eerste verzoek aan verhuurder worden voldaan.',
        );
    }

    protected function writeArticle_9(): void
    {
        $this->createSection(text: 'Beheerder');
        $this->articleIndentNumber(bullet: '9.1');
        $this->articleTextAndCodeWithDot(
            text: 'Totdat verhuurder anders meedeelt, treedt als beheerder op:',
            code: 'Verhuurder',
        );
        $this->Ln();
        $this->articleItem(
            bullet: '9.2',
            text: 'Tenzij schriftelijk anders overeengekomen, dient huurder voor wat betreft de inhoud en alle verdere aangelegenheden betreffende deze huurovereenkomst met de beheerder contact op te nemen.',
        );
    }

    protected function writeArticle_10(): void
    {
        $this->createSection(text: 'Waarborgsom');
        $this->articleIndentNumber(bullet: '10.1');
        $this->articleTextAndCode(
            text: Str::of('Huurder zal voor de ingangsdatum een waarborgsom betalen ter grootte van een bedrag van')
                ->append(SPACE)
                ->append(EURO),
            code: $this->formatPrice(cents: $this->contract->deposit),
        );
        $this->newLineWithIndentation();
        $this->articleTextAndCode(
            text: '(zegge:',
            code: $this->humanReadablePrice(cents: $this->contract->deposit),
            space: false,
        );
        $this->articleText(text: ') op de in artikel 4.4 aangegeven wijze.');
        $this->Ln();
        $this->articleIndentNumber(bullet: '10.2');
        $this->articleTextAndCode(text: 'Over de waarborgsom wordt', code: $this->interestOverDeposit ? 'wel' : 'geen');
        $this->articleText(text: 'rente vergoed.');
        $this->Ln();
    }

    protected function writeArticle_11(): void
    {
        $this->createSection(text: 'Boetebepaling');
        $this->articleItem(
            bullet: '11.1',
            text: 'Huurder en verhuurder komen overeen dat indien huurder tekortschiet in de nakoming van zijn verplichting(en) uit hoofde van de nagenoemde bepaling(en), hij aan verhuurder een direct opeisbare boete verbeurt zoals hieronder vermeld:',
        );

        $article_11_fines = $this->article11_fines();

        $article_11_text = (object) [
            'sub1' => (object) [
                'a' => [
                    'voor iedere kalenderdag dat de overtreding voortduurt, bij overtreding van artikel 1',
                    'gebruik, 9 (tuin), 13.1 en 13.2 (melden schade), 14.1 (algemene ruimten), 14.3 sub a (huisdieren), 14.4',
                    '(overlast), 21.1 en 21.2 (waarborgsom) van de algemene bepalingen, met een maximum van',
                    '',
                    'onverminderd zijn gehoudenheid om alsnog aan deze verplichting te voldoen en onverminderd verhuurders recht op (aanvullende) schadevergoeding;',
                ],
                'b' => [
                    'voor iedere kalenderdag dat de overtreding voortduurt, bij overtreding van artikel',
                    '4.1 en 4.2 (veranderingen en toevoegingen), 8 (antennes), 10 (zonwering), 14.2 en 14.3 sub b (reclame,',
                    'ventilatie- en rookkanalen) van de algemene bepalingen, met een maximum van',
                    'onverminderd',
                    'zijn gehoudenheid om alsnog aan deze verplichting te voldoen en onverminderd verhuurders recht op (aanvullende) schadevergoeding;',
                ],
                'c' => [
                    'voor iedere kalenderdag dat de overtreding voortduurt, bij overtreding van artikel 1.3',
                    '(wijziging bestemming) van deze huurovereenkomst en van artikel 12 (toegang), 15.2 (gevaarlijke stoffen),',
                    '19 (tijdige en correcte wederoplevering) van de algemene bepalingen, met een maximum van',
                    '',
                    'onverminderd zijn gehoudenheid om alsnog aan deze verplichting te voldoen en onverminderd verhuurders recht op (aanvullende) schadevergoeding;',
                ],
                'd' => [
                    'voor',
                    'iedere kalenderdag dat de overtreding voortduurt, bij overtreding van artikel 2 ((tijdelijke) onderhuur) van de',
                    'algemene bepalingen, met een maximum van',
                    'onverminderd (i) zijn gehoudenheid om alsnog',
                    'aan deze verplichting te voldoen en (ii) verhuurders recht op (aanvullende) schadevergoeding, en (iii) de verplichting tot afdracht van de winst die hij (naar schatting) heeft genoten door het handelen in strijd met dit verbod;',
                ],
                'e' => [
                    'voor',
                    'iedere kalenderdag dat de overtreding voortduurt, bij overtreding van artikel 14.3 sub c (hennep en',
                    'dergelijke) van de algemene bepalingen, met een maximum van',
                    'onverminderd (i) zijn',
                    'gehoudenheid om alsnog aan deze verplichting te voldoen en (ii) verhuurders recht op (aanvullende) schadevergoeding, en (iii) de verplichting tot afdracht van de winst die hij (naar schatting) heeft genoten door het handelen in strijd met dit verbod;',
                ],
            ],
        ];

        $this->article11_bullet(bullet: 'a', fines: $article_11_fines->sub1->a, text: $article_11_text->sub1->a);
        $this->article11_bullet(bullet: 'b', fines: $article_11_fines->sub1->b, text: $article_11_text->sub1->b);
        $this->article11_bullet(bullet: 'c', fines: $article_11_fines->sub1->c, text: $article_11_text->sub1->c);
        $this->article11_bullet(bullet: 'd', fines: $article_11_fines->sub1->d, text: $article_11_text->sub1->d);
        $this->article11_bullet(bullet: 'e', fines: $article_11_fines->sub1->e, text: $article_11_text->sub1->e);

        $this->articleItem(
            bullet: '11.2',
            text: 'Voor iedere overtreding van een verplichting uit deze huurovereenkomst en bijbehorende algemene bepalingen, voor zover niet reeds hiervoor in artikel 11.1 genoemd, is huurder aan verhuurder een direct opeisbare boete',
        );
        $this->articleIndentMargin();
        $this->articleTextAndCode(
            text: Str::of('van')
                ->append(' ')
                ->append(EURO),
            code: $this->formatPrice(cents: $article_11_fines->sub2->day),
        );
        $this->articleTextAndCode(
            text: Str::of('per kalenderdag verschuldigd, met een maximum van')
                ->append(SPACE)
                ->append(EURO),
            code: $this->formatPrice(cents: $article_11_fines->sub2->maximum),
        );
        $this->articleText(text: 'onverminderd zijn gehoudenheid');
        $this->Ln();
        $this->articleItem(
            bullet: '',
            text: 'om alsnog aan deze verplichting te voldoen en onverminderd verhuurders recht op (aanvullende) schadevergoeding. Indien verhuurder een professionele partij is, is dit artikel 11.2 niet van toepassing.',
        );
    }

    protected function writeArticle_12(): void
    {
        $this->createSection(text: 'Bijzondere bepalingen');
        $chapter = 12;
        $article_number = 1;

        $this->article12_ROZ2017_Erratum(chapter: $chapter, article_number: $article_number);
        $article_number++;

        $this->article12_ROZ2017_Undermining(chapter: $chapter, article_number: $article_number);
        $article_number++;

        // Bijzondere bepalingen vanuit DB
        $this->building_unit->contract_provisions
            ->filter(fn ($item) => $item->active)
            ->values()
            ->each(
                fn ($item, $index) => $this->articleExceptionalProvision(
                    text: $item->provision,
                    chapter: $chapter,
                    article_number: $article_number + $index,
                ),
            );
    }

    protected function writeClosing(int $copies = 2): void
    {
        $copies = $this->tenant->count() + 1;

        parent::writeClosing(copies: $copies);

        $y = $this->GetY();
        $this->fullSignature(name: $this->owner->nameWithInitials(), x: $this->dimensions->align->center, y: $y);
        $this->SetY($y);

        $this->tenant->each(function ($tenant, $index) {
            $this->fullSignature(
                name: $tenant->nameWithInitials(),
                x: $this->dimensions->align->left,
                y: $index === 0 ? $this->GetY() : $this->GetY() + $this->font->line_height * 3,
            );
        });

        $additionalAttachments = [
            (object) [
                'text' => 'huishoudelijk reglement',
                'active' => true,
            ],
            (object) [
                'text' => 'inboedellijst',
                'active' => true,
            ],
        ];

        $this->conditionallyAddPage(limit_y: 250);
        $this->writeAttachments(default: $this->closingDefaultAttachments(), additional: $additionalAttachments);

        $this->conditionallyAddPage(limit_y: 224);
        $this->writeAcceptanceGeneralProvisions(tenants: $this->tenant);
    }

    private function considerationSelectContractOption(int $option): object
    {
        return match ($option) {
            1 => (object) [
                'title' => 'Onbepaalde tijd',
                'items' => collect([
                    'partijen kiezen nadrukkelijk niet voor de mogelijkheid van een kortdurende huurovereenkomst, maar voor een langdurige(re) en bestendige(re) huurrelatie;',
                    'partijen kiezen nadrukkelijk om geen gebruik te maken van het huurregime van twee (2) jaar (zelfstandige woonruimte) of vijf (5) jaar (onzelfstandige woonruimte) of korter ex artikel 7:271 lid 1 Burgerlijk Wetboek;',
                    'aan huurder komt huurbescherming toe vanaf aanvang huurovereenkomst.',
                ]),
            ],
            2 => (object) [
                'title' => 'Onbepaalde tijd met een minimum duur van twaalf (12) maanden',
                'items' => collect([
                    'partijen kiezen, mede met het oog op de investeringen die zij in het kader van deze huurovereenkomst doen, nadrukkelijk niet voor de mogelijkheid van een kortdurende huurovereenkomst, maar voor een langdurige(re) en bestendige(re) huurrelatie welke minimaal twaalf (12) maanden zal duren;',
                    'partijen kiezen nadrukkelijk om geen gebruik te maken van het huurregime van twee (2) jaar (zelfstandige woonruimte) of vijf (5) jaar (onzelfstandige woonruimte) of korter ex artikel 7:271 lid 1 Burgerlijk Wetboek;',
                    'deze huurovereenkomst kan gedurende de minimumtermijn van twaalf (12) maanden niet tussentijds door partijen worden opgezegd omdat deze huurovereenkomst niet valt onder het huurregime van twee (2) jaar (zelfstandige woonruimte) of vijf (5) jaar (onzelfstandige woonruimte) of korter ex artikel 7:271 lid 1 Burgerlijk Wetboek;',
                    'aan huurder komt huurbescherming toe vanaf aanvang huurovereenkomst.',
                ]),
            ],
            3 => (object) [
                'title' => $this->building_unit->independent_living_space
                    ? 'Bepaalde tijd voor maximaal twee (2) jaar (zelfstandig)'
                    : 'vijf (5) jaar (onzelfstandig) of korter',
                'items' => collect([
                    $this->building_unit->independent_living_space
                        ? 'partijen kiezen voor de mogelijkheid van een kortdurende huurovereenkomst met een looptijd van twee (2) jaar of korter ex artikel 7:271 lid 1 Burgerlijk Wetboek aangezien sprake is van zelfstandige woonruimte;'
                        : 'partijen kiezen voor de mogelijkheid van een kortdurende huurovereenkomst met een looptijd van vijf (5) jaar of korter ex artikel 7:271 lid 1 Burgerlijk Wetboek aangezien sprake is van onzelfstandige woonruimte;',
                    'indien de huurovereenkomst na afloop van de bepaalde tijd wordt voortgezet, komt huurder huurbescherming toe.',
                ]),
            ],
            4 => (object) [
                'title' => $this->building_unit->independent_living_space
                    ? 'Bepaalde tijd langer dan twee (2) jaar (zelfstandig)'
                    : 'Bepaalde tijd langer dan vijf (5) jaar (onzelfstandig)',
                'items' => collect([
                    $this->building_unit->independent_living_space
                        ? 'partijen kiezen nadrukkelijk niet voor de mogelijkheid van een kortdurende huurovereenkomst maar voor een langdurige(re) en bestendige(re) huurrelatie welke langer dan twee (2) jaar (zelfstandige woonruimte) zal duren;'
                        : 'partijen kiezen nadrukkelijk niet voor de mogelijkheid van een kortdurende huurovereenkomst maar voor een langdurige(re) en bestendige(re) huurrelatie welke langer dan vijf (5) jaar (onzelfstandige woonruimte) zal duren;',
                    'deze huurovereenkomst kan gedurende de in artikel 3.1 genoemde termijn niet tussentijds door partijen worden opgezegd;',
                    'aan huurder komt huurbescherming toe vanaf aanvang huurovereenkomst.',
                ]),
            ],
        };
    }

    private function article3_option1(): void
    {
        $this->articleIndentNumber(bullet: '3.1');
        $this->articleTextAndCodeWithDot(
            text: 'Deze huurovereenkomst is aangegaan voor onbepaalde tijd, ingaande op',
            code: $this->humanReadableDate($this->contract->start),
        );
        $this->Ln();

        $articles = collect([
            (object) [
                'bullet' => '3.2',
                'text' => 'Verhuurder zal het gehuurde op de ingangsdatum van de huur aan huurder ter beschikking stellen, mits huurder heeft voldaan aan alle op dat moment bestaande verplichtingen jegens verhuurder. Indien de ingangsdatum niet op een werkdag valt, zal het gehuurde op de eerstvolgende werkdag ter beschikking worden gesteld.',
            ],
            (object) [
                'bullet' => '3.3',
                'text' => 'Beëindiging van de huurovereenkomst door opzegging dient te geschieden overeenkomstig artikel 18.1 van de algemene bepalingen.',
            ],
        ]);

        $articles->each(fn ($article) => $this->articleItem(bullet: $article->bullet, text: $article->text));
    }

    private function article3_option2(): void
    {
        $this->articleIndentNumber(bullet: '3.1');
        $this->articleText(
            text: 'Deze huurovereenkomst is aangegaan voor onbepaalde tijd met een minimale duur van twaalf (12) maanden,',
        );
        $this->newLineWithIndentation();
        $this->articleTextAndCodeWithDot(text: 'ingaande op', code: $this->humanReadableDate($this->contract->start));
        $this->Ln();

        $articles = collect([
            (object) [
                'bullet' => '3.2',
                'text' => 'Verhuurder zal het gehuurde op de ingangsdatum van de huur aan huurder ter beschikking stellen, mits huurder heeft voldaan aan alle op dat moment bestaande verplichtingen jegens verhuurder. Indien de ingangsdatum niet op een werkdag valt, zal het gehuurde op de eerstvolgende werkdag ter beschikking worden gesteld.',
            ],
            (object) [
                'bullet' => '3.3',
                'text' => 'Tijdens de in artikel 3.1 genoemde periode van twaalf (12) maanden kunnen partijen deze huurovereenkomst niet tussentijds door opzegging beëindigen.',
            ],
            (object) [
                'bullet' => '3.4',
                'text' => 'Indien de in artikel 3.1 genoemde twaalf (12) maanden verstrijken, loopt de huurovereenkomst, behoudens opzegging, voor onbepaalde tijd door.',
            ],
            (object) [
                'bullet' => '3.5',
                'text' => 'Beëindiging van de huurovereenkomst door opzegging dient te geschieden overeenkomstig artikel 18.1 van de algemene bepalingen.',
            ],
        ]);

        $articles->each(fn ($article) => $this->articleItem(bullet: $article->bullet, text: $article->text));
    }

    private function article3_option3(): void
    {
        $text3_1 = 'maximaal vijf (5) jaar of korter (onzelfstandige';
        $text3_4_1 = 'vijf (5) jaren in het geval van onzelfstandige';
        $text3_4_2 = 'woonruimte';

        if (! $this->building_unit->independent_living_space) {
            $text3_1 = 'maximaal twee (2) jaar of korter (zelfstandige';
            $text3_4_1 = 'twee (2) jaren in het geval van zelfstandige';
        }

        $this->articleIndentNumber(bullet: '3.1');
        $this->articleText(text: 'Deze huurovereenkomst is aangegaan voor een duur van');
        $this->articleText(text: $text3_1);
        $this->newLineWithIndentation();
        $this->articleTextAndCode(
            text: 'woonruimte) te weten',
            code: true === true ? '</code> maanden' : '</code> jaren',
        );
        $this->articleTextAndCode(text: ', ingaande op', code: $this->humanReadableDate($this->contract->start));
        $this->articleTextAndCodeWithDot(text: ' en lopende tot en met', code: '</code>');
        $this->Ln();
        $this->articleItem(
            bullet: '3.2',
            text: 'Verhuurder zal het gehuurde op de ingangsdatum van de huur aan huurder ter beschikking stellen, mits huurder heeft voldaan aan alle op dat moment bestaande verplichtingen jegens verhuurder. Indien de ingangsdatum niet op een werkdag valt, zal het gehuurde op de eerstvolgende werkdag ter beschikking worden gesteld.',
        );
        $this->articleItem(
            bullet: '3.3',
            text: 'Tijdens de in artikel 3.1 genoemde periode kan verhuurder deze huurovereenkomst niet tussentijds door opzegging beëindigen.',
        );
        $this->articleIndentNumber(bullet: '3.4');
        $this->articleText(
            text: 'De huurovereenkomst eindigt na ommekomst van de in de in artikel 3.1 genoemde periode, indien de in artikel',
        );
        $this->newLineWithIndentation();
        $this->articleTextAndCode(text: '3.1 genoemde bepaalde termijn korter is dan of gelijk aan', code: $text3_4_1);
        $this->newLineWithIndentation();
        $this->articleTextAndCode(text: '', code: $text3_4_2);
        $this->articleText(
            text: 'en de verhuurder de huurder tijdig, overeenkomstig artikel 18.2 van de algemene bepalingen,',
        );
        $this->newLineWithIndentation();
        $this->multiCellFromCurrentPosition(
            text: 'informeert over de dag waarop de huurovereenkomst eindigt. Indien de verhuurder de huurder niet of niet tijdig informeert en de in artikel 3.1 genoemde periode verstrijkt, loopt de huurovereenkomst voor onbepaalde tijd door. Beëindiging van de huurovereenkomst door opzegging dient in dat geval te geschieden overeenkomstig artikel 18.1 van de algemene bepalingen.',
        );
    }

    private function article3_option4(): void
    {
        $this->articleIndentNumber(bullet: '3.1');
        $this->articleTextAndCode(
            text: 'Deze huurovereenkomst is aangegaan voor een duur van langer dan',
            code: $this->building_unit->independent_living_space
                ? 'twee (2) jaar (zelfstandige'
                : 'vijf (5) jaar (onzelfstandige',
        );
        $this->newLineWithIndentation();
        $this->articleTextAndCode(text: '', code: 'woonruimte)', space: false);
        $this->articleText(text: ',');
        $this->articleTextAndCode(
            text: 'te weten',
            code: true === true ? '</code> maanden' : '</code> jaren',
            space: false,
        );
        $this->articleText(text: ',');
        $this->articleTextAndCode(text: 'ingaande op', code: $this->humanReadableDate($this->contract->start));
        $this->articleTextAndCodeWithDot(text: 'en lopende tot en met', code: '</code>');
        $this->Ln();

        $articles = collect([
            (object) [
                'bullet' => '3.2',
                'text' => 'Verhuurder zal het gehuurde op de ingangsdatum van de huur aan huurder ter beschikking stellen, mits huurder heeft voldaan aan alle op dat moment bestaande verplichtingen jegens verhuurder. Indien de ingangsdatum niet op een werkdag valt, zal het gehuurde op de eerstvolgende werkdag ter beschikking worden gesteld.',
            ],
            (object) [
                'bullet' => '3.3',
                'text' => 'Tijdens de in artikel 3.1 genoemde periode kunnen partijen deze huurovereenkomst niet tussentijds door opzegging beëindigen. ',
            ],
            (object) [
                'bullet' => '3.4',
                'text' => 'Indien in artikel 3.1 een bepaalde tijd is opgenomen en deze periode verstrijkt zonder opzegging, loopt de huurovereenkomst voor onbepaalde tijd door.',
            ],
            (object) [
                'bullet' => '3.5',
                'text' => 'Beëindiging van de huurovereenkomst door opzegging dient te geschieden overeenkomstig artikel 18.1 van de algemene bepalingen.',
            ],
        ]);

        $articles->each(fn ($article) => $this->articleItem(bullet: $article->bullet, text: $article->text));
    }

    private function article11_bullet(string $bullet, object $fines, array $text): void
    {
        $this->articleIndentMargin();
        $this->articleIndentNumber(bullet: Str::of($bullet)->finish('.'), style: '');
        $this->articleTextAndCode(
            text: Str::of('een boete van')
                ->append(SPACE)
                ->append(EURO),
            code: $this->formatPrice(cents: $fines->day),
        );
        if ($fines->offence) {
            $this->articleTextAndCode(
                text: Str::of('per overtreding, te vermeerderen met een aanvullende boete van')
                    ->append(SPACE)
                    ->append(EURO),
                code: $this->formatPrice(cents: $fines->offence),
            );
        }
        $this->articleText(text: Str::of($text[0]));
        $this->newLineWithIndentation();
        $this->articleItem(bullet: '', text: Str::of($text[1])->finish(SPACE), style: '');
        $this->articleIndentMargin();
        $this->articleIndentNumber(bullet: '', style: '');
        $this->articleTextAndCode(
            text: Str::of($text[2])
                ->finish(SPACE)
                ->append(EURO),
            code: $this->formatPrice(cents: $fines->maximum),
            space: false,
        );

        $this->articleText(
            text: Str::of($text[3])->start(Str::of(',')->when($text[3] !== '', fn ($string) => $string->append(SPACE))),
        );
        $this->newLineWithIndentation();
        $this->articleItem(bullet: '', text: Str::of($text[4])->trim(), style: '');
    }

    private function article11_fines(): object
    {
        // fines advised by ROZ
        return (object) [
            'sub1' => (object) [
                'a' => (object) ['day' => 2000, 'maximum' => 400000, 'offence' => null],
                'b' => (object) ['day' => 3500, 'maximum' => 700000, 'offence' => null],
                'c' => (object) ['day' => 5000, 'maximum' => 1000000, 'offence' => null],
                'd' => (object) ['day' => 7500, 'maximum' => 1500000, 'offence' => 150000],
                'e' => (object) ['day' => 10000, 'maximum' => 2500000, 'offence' => 500000],
            ],
            'sub2' => (object) ['day' => 1000, 'maximum' => 200000, 'offence' => null],
        ];
    }

    private function article12_ROZ2017_Erratum(int $chapter, int $article_number): void
    {
        // ROZ2017 - Erratum: alg. voorwaarden
        $erratum = (object) [
            'first_section' => 'In artikel 19.9 van de Algemene bepalingen Woonruimte 2017 is, in de tweede zin van het artikel, abusievelijk het woord ‘op’ vergeten en het woord ‘huurder’ in plaats van ‘verhuurder’ gebruikt. De correcte tekst luidt als volgt:',
            'second_section' => '19.9 Huurder is gehouden de door hem op basis van het rapport uit te voeren werkzaamheden binnen de in het rapport vastgelegde – of anders tussen partijen overeengekomen – termijn op een deugdelijke wijze uit te voeren c.q. te doen uitvoeren. Indien huurder geheel of gedeeltelijk nalatig blijft in de nakoming van zijn uit het rapport voortvloeiende verplichtingen, is verhuurder gerechtigd zelf deze werkzaamheden te laten uitvoeren en de daaraan verbonden kosten op huurder te verhalen, zonder dat huurder daarvoor door of namens verhuurder in gebreke behoeft te worden gesteld, en onverminderd de aanspraak van verhuurder op vergoeding van de verdere schade en kosten.',
        ];
        $this->articleExceptionalProvision(
            text: $erratum->first_section,
            chapter: $chapter,
            article_number: $article_number,
        );
        $this->articleIndentMargin();
        $this->articleIndentNumber(bullet: '', style: '');
        $this->defaultFont(style: 'BI');
        $this->multiCellFromCurrentPosition(text: $erratum->second_section);
    }

    private function article12_ROZ2017_Undermining(int $chapter, int $article_number): void
    {
        // ROZ2017 - Ondermijning (toegang en controle)
        $text = [
            'Verhuurder is gerechtigd te controleren of huurder de huurovereenkomst en de algemene bepalingen nakomt. Het gaat daarbij met name, maar niet uitsluitend om de verplichtingen in de artikelen 1.2 en 1.3 van de huurovereenkomst en de artikelen 1, 2, 4.1 en 4.2 en 14.3 van de algemene bepalingen. Verhuurder en alle door hem aan te wijzen personen zijn daartoe gerechtigd het gehuurde periodiek, op een in overleg met huurder te bepalen tijdstip, te betreden en te inspecteren. Huurder is verplicht daaraan zijn medewerking te verlenen door op eerste verzoek van verhuurder aan te geven op welk tijdstip – gelegen binnen redelijke termijn na diens verzoek – verhuurder het gehuurde kan betreden en inspecteren en door verhuurder op gemeld tijdstip toegang te verlenen tot het gehuurde en gelegenheid te geven tot inspectie. Huurder en verhuurder komen overeen dat indien huurder tekortschiet in de nakoming van zijn verplichtingen uit hoofde van dit artikellid, hij aan verhuurder een direct opeisbare boete verbeurt van',
            '€ **',
            'voor iedere kalenderdag dat de overtreding voortduurt, met een maximum van',
            '€ **',
            ', onverminderd zijn gehoudenheid om alsnog aan zijn verplichtingen te voldoen en onverminderd verhuurders recht op (aanvullende) schadevergoeding.',
        ];

        $string = Str::of($text[0])
            ->ascii()
            ->append(SPACE)
            ->append($this->formatPriceWithEuroSign($this->article11_fines()->sub1->e->day))
            ->append(SPACE)
            ->append($text[2])
            ->append(SPACE)
            ->append($this->formatPriceWithEuroSign($this->article11_fines()->sub1->e->maximum))
            ->append($text[4]);

        $this->articleExceptionalProvision(
            text: $string,
            chapter: $chapter,
            article_number: $article_number,
            checkStrAscii: false,
        );
    }

    private function closingDefaultAttachments(): Collection
    {
        return collect([
            (object) [
                'text' => 'plattegrond/tekening van het gehuurde',
                'active' => false,
            ],
            (object) [
                'text' => 'proces-verbaal van oplevering (toe te voegen ten tijde van oplevering)',
                'active' => false,
            ],
            (object) [
                'text' => 'kopie van het energielabel/Energie-Index',
                'active' => $this->building_unit->building->energy_label,
            ],
            (object) [
                'text' => 'algemene bepalingen',
                'active' => true,
            ],
        ]);
    }
}
