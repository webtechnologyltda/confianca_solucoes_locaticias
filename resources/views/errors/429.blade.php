<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name') }} - Muitas requisições</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <x-error-content title="Muitas Requisições"
        message="Você está fazendo muitas requisições em um curto período de tempo. Por favor, aguarde alguns minutos antes de tentar novamente."
        :showErrorCode="false" errorCode="429" iconPath="{{ asset('img/svg/errors/429.svg') }}" showActions="true"
        :actions="[['label' => 'Voltar para a Home', 'url' => route('home')]]" />
</body>

</html>
