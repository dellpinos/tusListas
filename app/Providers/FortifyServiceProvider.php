<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Auth\Notifications\ResetPassword;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email'); // Aqui la vista "Revisa tu casilla de email"
        });


        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', ['request' => $request]); //// Aqui ingresa el nuevo password / Espera recibir un email, password, confirmación de password, and a hidden field named token that contains the value of request()->route('token')
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password'); /// Aqui la vita "he perdido mi password" / Espera recibir un email
        });

        Fortify::registerView(function (Request $request) {
            // Busco el token en la base de datos, si hay coincidencia redirijo a "register" modificado
            $invitation = Invitation::where('token', $request->input('inv'))->first();
            $mensaje = '';

            if ($request->input('inv') && !$invitation) {
                $mensaje = "La invitación no existe o ha caducado.";
            }

            return view('empresa.register', [
                'invitation' => $invitation,
                'mensaje' => $mensaje
            ]);
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifica tu dirección de correo electrónico.')
                ->line('Haz clic en el siguiente botón para verificar tu dirección de correo electrónico y poder acceder a la aplicación de TusListas.')
                ->action('Verificar tu email', $url);
        });


        ResetPassword::toMailUsing(function ($notifiable, $url) {

            // El método esta enviando un token como $url en vez de la URL completa, agrego una linea para convertirlo en URL (copiada de ResetPassword::resetUrl)
            $url = url(route('password.reset', ['token' => $url, 'email' => $notifiable->getEmailForPasswordReset()], false));

            return (new MailMessage)
                ->subject('Reinicio de contraseña')
                ->line('Te enviamos este email porque recibimos un pedido para reiniciar la contraseña de tu cuenta.')
                ->action('Reiniciar Password', $url)
                ->line('Este enlace expirará en 60 minutos.') /* El tiempo deberia ser un dato variable */
                ->line('Si no hiciste la solicitud de reinicio de contraseña, solo ignora este mensaje.');
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (
                $user &&
                Hash::check($request->password, $user->password)
            ) {
                return $user;
            }
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
