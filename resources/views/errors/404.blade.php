<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Página não encontrada</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <x-error-content title="Página não encontrada" message="A página que você está procurando não existe ou foi movida."
        :showErrorCode="false" errorCode="404" iconPath="{{ asset('img/svg/errors/404.svg') }}" showActions="true"
        :actions="[['label' => 'Voltar para a Home', 'url' => route('home')]]" />
</body>

</html>
