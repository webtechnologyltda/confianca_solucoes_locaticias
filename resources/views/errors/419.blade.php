<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Sessão Expirada</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <x-error-content title="Sessão Expirada" message="Sua sessão expirou. Por favor, faça login novamente para continuar."
        :showErrorCode="false" errorCode="419" iconPath="{{ asset('img/svg/errors/419.svg') }}" showActions="true"
        :actions="[['label' => 'Voltar para a Home', 'url' => route('home')]]" />
</body>

</html>
