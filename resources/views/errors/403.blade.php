<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Permissão Negada</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <x-error-content title="Permissão Negada"
        message="Você não tem permissão para acessar este recurso. Por favor, verifique suas credenciais ou entre em contato com o administrador do sistema."
        :showErrorCode="false" errorCode="403" iconPath="{{ asset('img/svg/errors/403.svg') }}" showActions="true"
        :actions="[['label' => 'Voltar para a Home', 'url' => route('home')]]" />
</body>

</html>
