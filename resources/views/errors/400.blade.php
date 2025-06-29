<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Solicitação Incorreta</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <x-error-content title="Solicitação Incorreta"
        message="A solicitação que você fez não pôde ser processada pelo servidor. Por favor, verifique os dados enviados e tente novamente."
        :showErrorCode="false" errorCode="400" iconPath="{{ asset('img/svg/errors/400.svg') }}" showActions="true"
        :actions="[['label' => 'Voltar para a Home', 'url' => route('home')]]" />
</body>

</html>
