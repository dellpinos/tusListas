{{-- Ejemplo de email, actualmente estoy utilizando "invitacion.blade.php" que esta basado en las platillas de Laravel (para que el diseño de todos los emails del sitio sea igual) --}}
<!DOCTYPE html>
<html>
<head>
    <title>Invitación de TusListas</title>
</head>
<body style="background-color: #f5f5f5; font-family: Arial, sans-serif; margin: 0; padding: 0; margin-top: 30px;">
    <table role="presentation" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <tr>
            <td style="padding: 20px;">
                <h1 style="color: #333; text-align: center; line-height: 1.5; padding: 5px;">¡Hola!</h1>
                <p style="color: #666; text-align: center; line-height: 1.5;">El usuario <strong>{{$remitente}}</strong> utiliza la aplicación TusListas para administrar su empresa/negocio. Esta es una invitación para formar parte de su empresa <strong>{{$empresa}}</strong>.</p>
                <div style="text-align: center; margin: 20px;">
                    <a href="{{ env('APP_URL'). "/register" . "?inv=" . $token }}" style="background-color: #3498db; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px;">Aceptar Invitación</a>
                </div>
                <p style="color: #666; text-align: center; line-height: 1.5; padding: 5px;">También puedes crear tu propia empresa <a href="{{ env('APP_URL')}}" style="color: #3498db; text-decoration: none;">aquí</a>.</p>
            </td>
        </tr>
    </table>
</body>
</html>