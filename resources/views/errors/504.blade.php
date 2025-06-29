<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Tempo de inatividade do servidor</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <x-error-content title="Tempo de inatividade do servidor"
        message="O servidor não está respondendo. Por favor, tente novamente mais tarde ou entre em contato com a equipe de suporte."
        :showErrorCode="false" errorCode="504" iconPath="{{ asset('img/svg/errors/504.svg') }}" showActions="true"
        :actions="[['label' => 'Voltar para a Home', 'url' => route('home')]]" />
</body>

</html>
