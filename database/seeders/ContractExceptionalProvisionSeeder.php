<?php

namespace Database\Seeders;

use App\Models\ContractExceptionalProvision;
use Illuminate\Database\Seeder;

class ContractExceptionalProvisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContractExceptionalProvision::query()->create([
            'provision' => 'Huurder dient zelf voor aansluiting/verbinding van internet/tv/kabel te zorgen op eigen naam en kosten. Aansluiting dient te worden gemaakt in de meterkast (zie ook artikel 16 van het huishoudelijk reglement).',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Verhuurder en/of alle door hem aan te wijzen personen is/zijn gerechtigd om het gehuurde per kwartaal te betreden voor een controle op de naleving van het omschreven gebruik als bedoeld in artikel 1 van deze overeenkomst.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Verhuurder en/of alle door hem aan te wijzen personen is/zijn gerechtigd om het gehuurde te betreden om werkzaamheden te verrichten aan de centrale meterkast.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Verhuurder en huurder zijn overeengekomen dat, indien verhuurder de woning per eind van de contractperiode opnieuw (aan derden) kan verhuren, huurder zijn medewerking verleent aan het doen bezichtigen van de woning door de verhuurder. De verhuurder zal huurder tijdig informeren over de dag en het tijdstip van de bezichtiging.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'In afwijking met de algemene bepalingen heeft huurder een opzegtermijn van 2 maanden. De verhuurder heeft een opzegtermijn van 3 maanden.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Het is huurder niet toegestaan zonder toestemming van verhuurder de plafonds, wanden, muren en vloeren van een andere kleur of eventueel gaatjes of iets dergelijks te voorzien.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Huurder is ervan op de hoogte dat de woonruimte is voorzien van kasten, raambekleding, moderne keukenapparatuur (inclusief wasmachine/droger) en de badkamer zeer luxe is uitgevoerd. Indien door het gebruik deze goederen zijn beschadigd, zullen huurder en verhuurder hiertoe in overleg treden, teneinde een passende compensatie jegens verhuurder af te spreken.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Huurder is verplicht een inboedel- en aansprakelijkheidsverzekering (APV) te hebben of af te sluiten.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Huurder verklaart zich te houden aan het huishoudelijk reglement. Deze huisregels zijn omschreven in de bijlage die deel uitmaakt van deze huurovereenkomst en welke door partijen moet worden ondertekend.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Indien en voor zover de waarborgsom niet rechtsgeldig aangesproken is door verhuurder zal verhuurder na beëindiging van de huurovereenkomst en na overhandiging van een bewijs van uitschrijving Basisregistratie Personen (BRP) de waarborgsom terugstorten op een door huurder op te geven rekeningnummer.',
        ]);
        ContractExceptionalProvision::query()->create([
            'provision' => 'Op verzoek van huurders wordt het houden van een hond(je) toegestaan mits er geen overlast optreedt.',
        ]);
    }
}
