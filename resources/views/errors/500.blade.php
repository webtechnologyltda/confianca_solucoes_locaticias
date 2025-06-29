<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Erro Interno do Servidor</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <x-error-content title="Erro Interno do Servidor"
        message="Ocorreu um erro inesperado no servidor. Por favor, tente novamente mais tarde ou entre em contato com a equipe de suporte."
        :showErrorCode="false" errorCode="500" iconPath="{{ asset('img/svg/errors/500.svg') }}" showActions="true"
        :actions="[['label' => 'Voltar para a Home', 'url' => route('home')]]" />
</body>

</html>
