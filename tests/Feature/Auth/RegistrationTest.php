<?php

use App\Models\User;

function signUpDetails(array $overrides = []): array
{
    return array_merge([
        'name' => 'Naledi',
        'surname' => 'Khumalo',
        'email' => 'naledi@example.com',
        'phone_number' => '0712345678',
        'password' => 'Correct-Horse-Battery-9',
        'password_confirmation' => 'Correct-Horse-Battery-9',
    ], $overrides);
}

test('a new customer can sign up and is asked to verify their email', function () {
    $this->post(route('register.store'), signUpDetails())
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('verification.notice'));

    $user = User::query()->where('email', 'naledi@example.com')->sole();

    expect($user->role)->toBe('customer')
        ->and($user->hasVerifiedEmail())->toBeFalse();
});

test('names with accents, hyphens, apostrophes and spaces are accepted', function (string $name, string $surname) {
    $this->post(route('register.store'), signUpDetails(['name' => $name, 'surname' => $surname]))
        ->assertSessionHasNoErrors();
})->with([
    ['Mary-Jane', "O'Brien"],
    ['Zoë', 'van der Merwe'],
    ['José', 'O’Neil'],
    ['Thabo', 'St. John'],
]);

test('names with numbers or symbols are rejected', function (string $field, string $value) {
    $this->post(route('register.store'), signUpDetails([$field => $value]))
        ->assertSessionHasErrors($field);

    expect(User::query()->count())->toBe(0);
})->with([
    ['name', 'J4ne'],
    ['name', '<script>alert(1)</script>'],
    ['name', '=HYPERLINK("https://example.com")'],
    ['surname', '12345'],
    ['surname', '-Doe'],
]);

test('common phone number formats are accepted', function (string $phone) {
    $this->post(route('register.store'), signUpDetails(['phone_number' => $phone]))
        ->assertSessionHasNoErrors();
})->with([
    '0712345678',
    '071 234 5678',
    '+27 71 234 5678',
    '+27712345678',
    '(011) 555-1234',
]);

test('anything that is not a phone number is rejected', function (string $phone) {
    $this->post(route('register.store'), signUpDetails(['phone_number' => $phone]))
        ->assertSessionHasErrors('phone_number');

    expect(User::query()->count())->toBe(0);
})->with([
    'hello',
    '12345',
    '=1+1',
    '071 234 5678 ext 2',
    '+27 71 234 5678 999 999',
]);

test('people signing up cannot make themselves an admin', function () {
    $this->post(route('register.store'), signUpDetails(['role' => 'admin']))
        ->assertSessionHasNoErrors();

    expect(User::query()->where('email', 'naledi@example.com')->sole()->role)->toBe('customer');
});
