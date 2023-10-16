<!DOCTYPE html>
<html>
<head>
    <title>Invitación de prueba</title>
</head>
<body>
    <h1>¡Hola, Persona!</h1>
    <p>Esto es un correo de prueba para invitarte.</p>

    <p>El usuario {{$remitente}} te invita a formar parte de su empresa: {{$empresa}}.</p>

    <a href="{{ env('APP_URL'). "/register" . "?inv=" . $token }}">Aceptar Invitación</a>
</body>
</html>

