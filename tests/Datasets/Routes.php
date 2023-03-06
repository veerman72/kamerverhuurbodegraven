<?php

$authenticated = [
    'buildings',
    'buildings/{building}',
    'buildings/{building}/units',
    'buildings/{building}/units/{unit}',
    'contracts',
    'contracts/{contract}',
    //    'contract-exeptional-provisions',
    //    'kitchens',
    //    'laundryrooms',
    'owners',
    'owners/{owner}/buildings',
    'owners/{owner}/buildings/{building}',
    'owners/{owner}/buildings/{building}/units',
    'owners/{owner}/buildings/{building}/units/{unit}',
    'profile',
    //    'sanitations',
    'tenants',
    'tenants/{tenant}',
    'tenants/{tenant}/contracts',
    'tenants/{tenant}/contracts/{contract}',
];

$guest = ['/'];

dataset('routes.guest', function () use ($guest) {
    return $guest;
});

dataset('routes.auth', function () use ($authenticated) {
    return $authenticated;
});

dataset('routes.all', function () use ($guest, $authenticated) {
    return [...$guest, ...$authenticated];
});
