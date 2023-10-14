<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $resultado = Empresa::create([
            'name' => $input['name'],
            'plan' => 'free' ///// <<<<<<<<<<<<  Este default debe cambiar para restringir el acceso a usuarios vip
        ]);

        session()->put('empresa', $resultado);

        return User::create([
            'name' => $input['usuario'],
            'email' => $input['email'],
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'empresa_id' => $resultado->id,
            'user_type' => 'owner'
        ]);
    }
}
