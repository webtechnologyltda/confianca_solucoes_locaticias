<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Servidor em manutenção</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <x-error-content title="Servidor em manutenção"
        message="O servidor está em manutenção planejada, por favor tente novamente mais tarde ou entre em contato com a equipe de suporte para mais detalhes!"
        :showErrorCode="false" errorCode="503" iconPath="{{ asset('img/svg/errors/503.svg') }}" showActions="false"
        :actions="[['label' => 'Voltar para a Home', 'url' => route('home')]]" />
</body>

</html>
