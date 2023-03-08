<?php

use Symfony\Component\HttpFoundation\Response;

test('route is not protected by middleware', function ($uri) {
    $this->get(uri: $uri)->assertStatus(status: Response::HTTP_OK);
})->with(data: 'routes.guest');

test('route is protected by middleware', function ($uri) {
    $this->withMiddleware()
        ->get(uri: $uri)
        ->assertSessionHasNoErrors()
        ->assertStatus(status: Response::HTTP_FOUND);
})->with(data: 'routes.auth');
