<?php

namespace App\Services;

use App\Enums\ContractStatus;
use App\Models\Owner;
use App\Models\Tenant;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Str;

class DefaultContractService extends Fpdf
{
    public object $dimensions;

    public object $font;

    public string $title;

    public string $reference;

    public int $indent;

    public int $set_margin_left;

    public int $set_margin_right;

    public int $set_margin_top;

    public int $set_margin_bottom;

    private object $grid;

    private string $logo;

    private string $locale;

    private bool $isDraft;

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $title = null)
    {
        if (! defined('EURO')) {
            define('EURO', chr(128));
            define('SPACE', chr(32));
        }
        parent::__construct($orientation, $unit, $size);
        parent::SetAuthor('kamerverhuurbodegraven.nl');
        parent::SetCreator('eServices kamerverhuurbodegraven.nl');

        $this->locale = config('app.locale');
        $this->title = $title ?? config('app.name');
        //        $this->logo = 'storage/header_url.png';
        $this->logo = storage_path('app/public/header_url.png');
        $this->font = $this->fontSettings();
        $this->SetAutoPageBreak(true, 25);
        $this->AliasNbPages();
        $this->indent = 10;
        $this->applyMargins(20);
        $this->grid = $this->gridColumnWidth(40, 2);
        $this->angle = 0;
    }

    public function Header()
    {
        $this->Image($this->logo, 130, 10, 80);
        $this->SetFont($this->font->family, 'B', $this->font->size->text_base);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(24);
        if ($this->isDraft) {
            $this->Watermark();
        }
    }

    public function Footer()
    {
        $this->SetFont($this->font->family, 'I', $this->font->size->text_sm);
        $this->SetTextColor(0, 0, 0);

        $y = -20;
        $w = 20;
        $col_1 = 11;

        $this->SetXY($this->dimensions->align->left, $y);
        $this->Cell($col_1, $this->font->line_height, 'Contract:');
        $this->Cell($w, $this->font->line_height, $this->reference);

        $this->SetXY($this->dimensions->align->center - 0.5 * $w, $y);
        $this->Cell($w, $this->font->line_height, 'Paraaf verhuurder', null, 0, 'C');

        $this->SetXY($this->dimensions->align->right - $this->dimensions->align->left, $y);
        $this->Cell($w, $this->font->line_height, 'Paraaf huurder(s)', null, 0, 'R');

        $this->SetXY($this->dimensions->align->left, $y + 3.5);
        $this->Cell($col_1, $this->font->line_height, 'Pagina:');
        $this->Cell(
            $w,
            $this->font->line_height,
            Str::of($this->PageNo())
                ->append(SPACE)
                ->append('/')
                ->append(SPACE)
                ->append('{nb}'),
        );

        $this->SetTextColor(0, 0, 0);
    }

    protected function writePreface($type, $text): void
    {
        $this->sectionInitialisation(margin_top: -10);
        $this->SetFont($this->font->family, 'b', $this->font->size->text_lg);
        $width = $this->dimensions->align->right - $this->dimensions->align->left;
        $this->drawLine();
        $this->MultiCell($width, 13, $type);
        $this->drawLine();
        $this->defaultFont();
        $this->SetFontSize($this->font->size->text_sm);
        $this->SetXY($this->dimensions->align->left, $this->GetY() + 1);
        $this->MultiCell($width, $this->font->line_height, $text);
        $this->SetXY($this->dimensions->align->left, $this->GetY() + 1);
        $this->drawLine();
        $this->defaultFont();
    }

    protected function writeOwner(Owner $owner): void
    {
        $this->createSection(text: 'ONDERGETEKENDEN:');
        $this->Ln();
        $this->defaultFont(style: 'B');
        $this->gridOwner(col1: 'A.', col2: 'Verhuurder');
        $this->defaultFont();
        $this->gridOwner(col1: 'Naam:', col2: $this->fullLastName($owner->last_name, $owner->prefix));
        $this->gridOwner(col1: 'Voornamen:', col2: $owner->first_name);
        $this->gridOwner(col1: 'Geboorteplaats:', col2: $owner->place_of_birth);
        $this->gridOwner(col1: 'Geboortedatum:', col2: $this->humanReadableDate($owner->date_of_birth));
        $this->gridOwner(col1: 'Straat:', col2: $owner->address);
        $this->gridOwner(col1: 'Postcode:', col2: $owner->zipcode);
        $this->gridOwner(col1: 'Woonplaats:', col2: $owner->city);
        $this->gridOwner(col1: 'E-mailadres:', col2: $owner->email);
        $this->gridOwner(col1: 'Mobiel:', col2: $owner->phone);
        $this->Ln();
        $this->Write($this->font->line_height, 'Hierna te noemen \'verhuurder\',');
    }

    protected function writeTenant(Collection $tenant): void
    {
        $this->sectionInitialisation();
        $this->Cell(0, $this->font->line_height, 'EN', null, true);
        $this->Ln();
        $this->defaultFont(style: 'B');

        $text_header = ['Huurder'];
        $text_closing = 'Hierna te noemen \'huurder\'.';

        if ($tenant->count() === 2) {
            $text_header = ['Huurder B1', 'Huurder B2'];
            $text_closing = 'Zowel ieder afzonderlijk als gezamenlijk, hierna te noemen \'huurder\'.';
        }

        $this->gridTenants(col1: 'B.', collection: collect($text_header));
        $this->defaultFont();
        $this->gridTenants(
            col1: 'Naam:',
            collection: $tenant->map(fn ($tenant) => $this->fullLastName($tenant->last_name, $tenant->prefix)),
        );
        $this->gridTenants(col1: 'Voornamen:', collection: $tenant->pluck('first_name'));
        $this->gridTenants(col1: 'Geboorteplaats:', collection: $tenant->pluck('place_of_birth'));
        $this->gridTenants(
            col1: 'Geboortedatum:',
            collection: $tenant->pluck('date_of_birth')->transform(fn ($date) => $this->humanReadableDate($date)),
        );
        $this->gridTenants(col1: 'Straat:', collection: $tenant->pluck('address'));
        $this->gridTenants(col1: 'Postcode:', collection: $tenant->pluck('zipcode'));
        $this->gridTenants(col1: 'Woonplaats:', collection: $tenant->pluck('city'));
        $this->gridTenants(col1: 'E-mailadres:', collection: $tenant->pluck('email'));
        $this->gridTenants(col1: 'Mobiel:', collection: $tenant->pluck('phone'));
        $this->gridTenants(col1: 'Legitimatie:', collection: $tenant->pluck('id_document_number'));
        $this->gridTenants(col1: 'Burgerservicenummer:', collection: $tenant->pluck('social_number'));
        $this->gridTenants(col1: 'Werkgever:', collection: $tenant->pluck('employer'));
        $this->Ln();
        $this->Write($this->font->line_height, $text_closing);
    }

    protected function writeConsideration(): void
    {
        $this->createSection(text: 'NEMEN HET VOLGENDE IN AANMERKING:', margin_top: 15);
        $this->Ln();
    }

    protected function writeAgreed(): void
    {
        $this->createSection(text: 'ZIJN OVEREENGEKOMEN:');
    }

    protected function writeClosing(int $copies = 2): void
    {
        $this->conditionallyAddPage(limit_y: 220);

        $this->createSection(text: '', margin_top: $this->GetY() < 50 ? 0 : null);

        $this->articleTextAndCode(
            text: 'Aldus opgemaakt en ondertekend in',
            code: $this->humanReadableAmount($copies),
            space: false,
        );
        $this->articleText(text: 'voud.');
        $this->Ln();
        $this->Ln();
    }

    protected function createSection(string $text, int|null $margin_top = null)
    {
        $this->sectionInitialisation(margin_top: $margin_top);
        $this->sectionTitle(text: $text);
    }

    public function applyMargins(
        int $left = 10,
        int|null $top = null,
        int|null $right = null,
        int|null $bottom = null,
    ): object {
        $this->set_margin_left = $left;
        $this->set_margin_right = $right ?: $left;
        $this->set_margin_top = $top ?: $left;
        $this->set_margin_bottom = $bottom ?: $this->set_margin_top;
        $this->SetMargins($this->set_margin_left, $this->set_margin_top, $this->set_margin_right);

        return $this->dimensions = $this->pageDimensions();
    }

    private function fontSettings(): object
    {
        return (object) [
            'family' => 'Arial',
            'line_height' => 5,
            'size' => (object) [
                'text_xl' => 16,
                'text_lg' => 12,
                'text_base' => 9,
                'text_sm' => 7,
                'text_xs' => 5,
            ],
        ];
    }

    private function margins(): object
    {
        return (object) [
            'left' => $this->set_margin_left ?? 10,
            'right' => $this->set_margin_right ?? 10,
            'top' => $this->set_margin_top ?? 10,
            'bottom' => $this->set_margin_bottom ?? 10,
        ];
    }

    private function pageDimensions(): object
    {
        return (object) [
            'align' => (object) [
                'left' => (int) $this->margins()->left,
                'right' => (int) $this->GetPageWidth() - $this->margins()->right,
                'center' => (int) $this->GetPageWidth() / 2,
            ],
            'width' => (int) $this->GetPageWidth(),
            'height' => (int) $this->GetPageHeight(),
        ];
    }

    private function drawLine(): void
    {
        $this->Line($this->dimensions->align->left, $this->GetY(), $this->dimensions->align->right, $this->GetY());
    }

    protected function defaultFont(string $style = null): void
    {
        $this->SetFont($this->font->family, $style, $this->font->size->text_base);
    }

    private function sectionInitialisation(int|null $margin_top = 10): void
    {
        $this->defaultFont();
        $this->Ln($margin_top);
    }

    private function sectionTitle(string $text): void
    {
        $this->defaultFont(style: 'B');
        $this->MultiCell(100, $this->font->line_height, $text);
        $this->defaultFont();
    }

    private function gridColumnWidth(int $widthFirstColumn, int $numberOfAdditionalColumns): object
    {
        return (object) [
            'first' => $widthFirstColumn,
            'additional' => (int) ($this->dimensions->width - $widthFirstColumn - $this->margins()->left * 2) /
                $numberOfAdditionalColumns,
        ];
    }

    private function gridOwner(string $col1, string $col2): void
    {
        $this->Cell($this->grid->first, $this->font->line_height, $col1);
        $this->Cell($this->grid->additional, $this->font->line_height, Str::of($col2)->ascii(), null, true);
    }

    private function gridTenants(string $col1, SupportCollection $collection): void
    {
        $this->Cell($this->grid->first, $this->font->line_height, $col1);
        $collection->each(
            fn ($item, $index) => $this->Cell(
                $this->grid->additional,
                $this->font->line_height,
                Str::of($item)->ascii(),
                null,
                $index === $collection->count() - 1,
            ),
        );
    }

    protected function articleItem(string $bullet, string $text, string $style = 'B'): void
    {
        $this->articleIndentNumber(bullet: $bullet, style: $style);
        $this->multiCellFromCurrentPosition(text: $text);
    }

    protected function articleExceptionalProvision(
        string $text,
        int $chapter,
        int $article_number,
        bool $checkStrAscii = true,
    ): void {
        $this->articleIndentNumber(
            bullet: Str::of($chapter)
                ->finish('.')
                ->append($article_number),
        );
        $this->defaultFont(style: 'B');
        $this->multiCellFromCurrentPosition(text: $text, checkStrAscii: $checkStrAscii);
        $this->defaultFont();
    }

    protected function multiCellFromCurrentPosition(
        string $text,
        string|null $border = null,
        string $align = 'L',
        bool $checkStrAscii = true,
    ): void {
        $this->MultiCell(
            $this->dimensions->align->right - $this->GetX(),
            $this->font->line_height,
            Str::of($text)->when($checkStrAscii, fn ($string) => $string->ascii()),
            $border,
            $align,
        );
    }

    protected function articleIndentNumber(string $bullet, string $style = 'B'): void
    {
        $this->defaultFont(style: $style);
        $correction = $style === '' ? 5 : 0;
        $this->Cell($this->indent - $correction, $this->font->line_height, $bullet);
        $this->defaultFont();
    }

    protected function articleIndentMargin(): void
    {
        $this->setX($this->dimensions->align->left + $this->indent);
    }

    protected function newLineWithIndentation(): void
    {
        $this->Ln();
        $this->articleIndentMargin();
    }

    protected function articleText(string $text): void
    {
        $this->Write(
            $this->font->line_height,
            Str::of($text)
                ->whenContains(EURO, fn ($item) => $item->replace(EURO, 'chr(128)'))
                ->ascii()
                ->whenContains('chr(128)', fn ($item) => $item->replace('chr(128)', EURO))
                ->append(SPACE),
        );
    }

    protected function articleTextAndCode(string $text, string $code, bool $space = true): void
    {
        $this->articleText(text: $text);
        $this->defaultFont(style: 'B');
        $this->Write(
            $this->font->line_height,
            Str::of($code)
                ->whenContains(EURO, fn ($item) => $item->replace(EURO, 'chr(128)'))
                ->ascii()
                ->whenContains('chr(128)', fn ($item) => $item->replace('chr(128)', EURO))
                ->when($space, fn ($string) => $string->append(SPACE)),
        );
        $this->defaultFont();
    }

    protected function articleTextAndCodeWithDot(string $text, string $code): void
    {
        $this->articleTextAndCode(text: $text, code: $code, space: false);
        $this->articleEndDot();
    }

    protected function articleEndDot(): void
    {
        $this->Write($this->font->line_height, Str::of('.')->append(SPACE));
    }

    protected function articleTextAndPriceColumn(
        array $text,
        int $price,
        bool $strikethrough = false,
        string $border = '',
    ) {
        $x = $this->GetX();
        $y = $this->GetY();

        $a = 3;
        $b = 17;
        $c = $a + $b + 10;

        $this->defaultFont('B');
        $this->SetX($this->dimensions->align->right - ($a + $b));
        $this->Cell($a, $this->font->line_height, EURO, $border, false, 'R');
        $this->MultiCell($b, $this->font->line_height, $this->formatPrice(cents: $price), $border, 'R');
        $this->defaultFont();

        $this->SetXY($x, $y);

        $line_dimensions = collect([]);

        collect($text)->each(function ($line) use ($x, $line_dimensions) {
            $this->SetX($x);
            $this->articleText($line);
            $line_dimensions->push(
                (object) [
                    'x' => $x,
                    'y' => $this->GetY() + 0.5 * $this->font->line_height,
                    'length' => $this->GetStringWidth($line) + 1,
                ],
            );
            $this->Ln();
        });

        if ($strikethrough) {
            $line_dimensions->each(fn ($line) => $this->Line($line->x, $line->y, $line->x + $line->length, $line->y));
        }
    }

    protected function writeAttachments(SupportCollection $default, array|SupportCollection $additional): void
    {
        if ($this->GetY() > 45) {
            $this->sectionInitialisation(margin_top: 15);
        }
        $this->articleText(text: 'Bijlagen:');
        $this->Ln();
        $this->Ln();

        collect($default->merge($additional)->all())->each(function ($attachment) {
            $x = $this->GetX();
            $x1 = $x + 1;

            $this->Cell(3, $this->font->line_height, '[');
            $this->Cell(3, $this->font->line_height, ']', null, 0, 'R');
            $this->SetX($x + 10);
            $this->Write($this->font->line_height, $attachment->text);

            if ($attachment->active) {
                $this->SetX($x1 - 0.05);
                $this->Cell(0, $this->font->line_height + 0.5, 'X');
            } else {
                $y = $this->GetY() + 0.5 * $this->font->line_height;
                $x2 = $this->GetStringWidth($attachment->text) + 31;
                $this->Line($x1, $y, $x2, $y);
            }

            $this->Ln();
        });
    }

    protected function writeAcceptanceGeneralProvisions(Collection|Tenant $tenants, string $text = null): void
    {
        if ($text === null) {
            $text =
                'Afzonderlijke handtekening(en) van huurder(s) voor de ontvangst van een eigen exemplaar van de ALGEMENE BEPALINGEN HUUROVEREENKOMST WOONRUIMTE als genoemd in artikel 2.';
        }

        $this->Ln($this->font->line_height * 2);
        $this->SetFontSize($this->font->size->text_sm);
        $this->multiCellFromCurrentPosition(text: $text);
        $this->Ln($this->font->line_height);
        $this->articleText(text: 'handtekening huurder(s):');

        $y = $this->GetY();

        $tenants->each(function ($tenant, $index) use ($y) {
            $this->SetY($y);
            $this->signature(
                name: $tenant->full_name,
                x: $index === 0 ? $this->dimensions->align->left : $this->dimensions->align->center,
            );
        });
    }

    protected function signature(string $name, int $x, int $space_for_signature = 25): void
    {
        $this->Ln($space_for_signature);
        $this->SetX($x);
        $this->defaultFont(style: 'B');
        $this->Write($this->font->line_height, Str::of($name)->ascii());
        $this->defaultFont();
        $this->Ln();
    }

    protected function fullSignature(
        string $name,
        int $x,
        int $y,
        string $city = 'Bodegraven',
        int $space_for_signature = 25,
    ): void {
        if ($y > 232) {
            $this->conditionallyAddPage(limit_y: 232);
            $y = $this->GetY();
        }
        $this->SetXY($x, $y);
        $this->defaultFont(style: 'B');
        $this->Cell(40, $this->font->line_height, $city);
        $this->defaultFont();
        $this->articleText(text: 'datum');
        $this->signature(name: $name, x: $x, space_for_signature: $space_for_signature);
    }

    protected function conditionallyAddPage(int $limit_y): void
    {
        $this->GetY() < $limit_y ?: $this->AddPage();
    }

    protected function firstIndexDate(Carbon|CarbonImmutable $startContract): CarbonImmutable
    {
        return $startContract->month >= 9
            ? CarbonImmutable::create($startContract->startOfYear()->addYears(2))
            : CarbonImmutable::create($startContract->startOfYear()->addYear());
    }

    protected function formatPrice(int $cents): string
    {
        return number_format($cents / 100, 2, ',', '.');
    }

    protected function formatPriceWithEuroSign(int $cents): string
    {
        return Str::of($this->formatPrice($cents))->prepend(EURO.SPACE);
    }

    protected function humanReadablePrice(int $cents): string
    {
        $eurocents = $cents % 100;
        $euro = ($cents - $eurocents) / 100;

        return Str::of($this->humanReadableAmount(amount: $euro))
            ->append(' euro')
            ->when(
                $eurocents > 0,
                fn ($string) => $string
                    ->append(' en ')
                    ->append($this->humanReadableAmount(amount: $eurocents))
                    ->append(' eurocent'),
            )
            ->ascii();
    }

    protected function humanReadableDate(Carbon|CarbonImmutable $date): string
    {
        return $date->isoFormat('LL');
    }

    private function fullLastName(string $last_name, string $prefix = null)
    {
        return Str::of($last_name)
            ->headline()
            ->when(
                $prefix,
                fn ($string) => $string->prepend(
                    Str::of($prefix)
                        ->ucfirst()
                        ->append(SPACE),
                ),
            );
    }

    private function humanReadableAmount(int $amount): string
    {
        return (new \NumberFormatter($this->locale, \NumberFormatter::SPELLOUT))->format($amount);
    }

    private function Watermark(string $text = 'concept', int $angle = 45): void
    {
        $txt = $this->transformWatermarkText(text: $text);
        $this->SetTextColor(156, 163, 175); // tailwindcss text-gray-400
        $this->SetFontSize(84);
        $this->Rotate(angle: $angle, x: $this->GetStringWidth($txt), y: $this->GetStringWidth($txt));
        $this->Text(
            x: $this->GetPageWidth() / 2 - $this->GetStringWidth($txt) / 8,
            y: $this->GetPageHeight() / 2 - $this->GetStringWidth($txt) / 4.5,
            txt: $txt,
        );
        $this->Rotate(0);
        $this->SetTextColor(0, 0, 0);
        $this->defaultFont();
    }

    private function transformWatermarkText(string $text): string
    {
        // add spaces and to uppercase
        return chunk_split(strtoupper($text), 1, ' ');
    }

    public function Rotate($angle, $x = -1, $y = -1): void
    {
        if ($x == -1) {
            $x = $this->x;
        }
        if ($y == -1) {
            $y = $this->y;
        }
        if ($this->angle != 0) {
            $this->_out('Q');
        }
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(
                sprintf(
                    'q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',
                    $c,
                    $s,
                    -$s,
                    $c,
                    $cx,
                    $cy,
                    -$cx,
                    -$cy,
                ),
            );
        }
    }

    public function _endpage(): void
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    protected function setContractStatus(int $status): void
    {
        $this->isDraft = $status <= ContractStatus::APPROVAL->value;
    }
}
