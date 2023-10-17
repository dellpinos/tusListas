<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Invitation;
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

        if(!isset($input['token'])) {
            // Usuario owner
            Validator::make($input, [
                'name' => 'required|max:30|min:3|unique:empresas|string',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:60',
                    Rule::unique(User::class),
                ],
                'usuario' => 'required|max:30|min:3|string',
                'username' => ["required", "unique:users", "min:3", "max:20", "not_in:logout,register"],
                'password' => $this->passwordRules(),
            ])->validate();
    
            $resultado = Empresa::create([
                'name' => $input['name'],
                'plan' => 'free' ///// <<<<<<<<<<<<  Este default debe cambiar para restringir el acceso a usuarios vip
            ]);
    
            return User::create([
                'name' => $input['usuario'],
                'email' => $input['email'],
                'username' => $input['username'],
                'password' => Hash::make($input['password']),
                'empresa_id' => $resultado->id,
                'user_type' => 'owner'
            ]);
        } else {
            // Usuario común

            Validator::make($input, [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:60',
                    Rule::unique(User::class),
                ],
                'usuario' => 'required|max:30|min:3|string',
                'username' => ["required", "unique:users", "min:3", "max:20", "not_in:logout,register"],
                'password' => $this->passwordRules(),
            ])->validate();

            $invitation = Invitation::where('token', $input['token'])->first();

            $empresa_id = $invitation->empresa_id;
            $invitation->delete();

            // Eliminar invitacion <<<<

            return User::create([
                'name' => $input['usuario'],
                'email' => $input['email'],
                'username' => $input['username'],
                'password' => Hash::make($input['password']),
                'empresa_id' => $empresa_id,
                'user_type' => 'user'
            ]);

        }
    }
}
