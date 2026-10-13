<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Names start with a letter, then letters, accents, spaces, hyphens,
     * apostrophes or full stops: "Naledi", "Mary-Jane", "O'Brien", "Zoë", "St. John".
     */
    private const NAME_PATTERN = "/^\p{L}[\p{L}\p{M} '’.-]*$/u";

    /**
     * 9 to 15 digits, with an optional leading + and spaces, hyphens or
     * brackets between them: "0712345678", "071 234 5678", "+27 71 234 5678".
     */
    private const PHONE_PATTERN = '/^\+?(?:[ ()-]*\d){9,15}[ ()-]*$/';

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:100', 'regex:'.self::NAME_PATTERN],
            'surname' => ['required', 'string', 'max:100', 'regex:'.self::NAME_PATTERN],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'phone_number' => ['required', 'string', 'max:20', 'regex:'.self::PHONE_PATTERN],
            'password' => $this->passwordRules(),
        ], [
            'name.regex' => __('Your first name can only use letters, spaces, hyphens and apostrophes.'),
            'surname.regex' => __('Your surname can only use letters, spaces, hyphens and apostrophes.'),
            'phone_number.regex' => __('Enter a phone number like 071 234 5678 or +27 71 234 5678.'),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'surname' => $input['surname'],
            'email' => $input['email'],
            'phone_number' => $input['phone_number'],
            'password' => Hash::make($input['password']),
            'role' => 'customer',
        ]);
    }
}
