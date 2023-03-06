<?php

use App\Models\User;
use function Pest\Laravel\actingAs;

test('profile page is displayed', function () {
    actingAs(User::factory()->make())
        ->get('profile')
        ->assertOk();
});

test('profile information can be updated', function () {
    actingAs(user: ($user = User::factory()->create()))
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(uri: '/profile');

    expect($user)
        ->refresh()
        ->name->toBe('Test User')
        ->email->toBe('test@example.com')
        ->email_verified_at->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    actingAs(user: ($user = User::factory()->make()))
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    expect($user)
        ->refresh()
        ->email_verified_at->toBeNull();
});

test('user can delete their account', function () {
    actingAs(user: ($user = User::factory()->make()))
        ->delete('/profile', [
            'password' => 'password',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();

    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    actingAs(user: ($user = User::factory()->make()))
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ])
        ->assertSessionHasErrors('password')
        ->assertRedirect('/profile');

    expect($user->refresh())
        ->not()
        ->toBeNull();
});
