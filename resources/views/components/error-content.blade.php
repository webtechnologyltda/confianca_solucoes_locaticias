@props([
    'title' => 'Erro Interno',
    'message' => 'Erro interno do servidor.',
    'errorCode' => 500,
    'iconPath' => 'public/img/svg/errors/500.svg',
    'showErrorCode' => true,
    'showActions' => true,
    'actions' => [['label' => 'Tentar Novamente', 'url' => route('home')]],
])

<section class="bg-white dark:bg-gray-900 flex items-center h-screen">
    <div class="mx-auto my-auto max-w-screen-xl">
        <div class="mx-auto max-w-screen-md lg:max-w-screen-lg text-center justify-items-center">
            <svg class="size-100 lg:size-130 fill-primary-400">
                <use class="h-full w-full" xlink:href="{{ $iconPath }}"></use>
            </svg>
            @if ($showErrorCode)
                <h1 class="mb-4 text-7xl font-extrabold text-primary-800 dark:text-primary-400">{{ $errorCode }}</h1>
            @endif
            <p class="mb-10 text-3xl lg:text-3xl tracking-tight font-bold text-primary-900 dark:text-white">
                {{ $title }}
            </p>
            <p class="mb-4 text-black font-bold text-2xl lg:text-2xl dark:text-gray-100">
                {{ $message }}
            </p>

            @if ($showActions == 'true' || $showActions === true)
                <p class="text-gray-500 dark:text-primary-400 lg:mb-8 text-xl lg:text-lg mt-15 mb-12">
                    Escolha abaixo uma das opções para continuar navegando:
                </p>
                <ul class="flex justify-center items-center space-x-4 text-gray-500 dark:text-gray-400 mb-10">
                    @foreach ($actions as $action)
                        <li>
                            <a href="{{ $action['url'] }}"
                                class="bg-primary-600 hover:bg-primary-400 dark:bg-primary-500 hover:dark:bg-primary-700 text-2xl lg:text-lg
                                    text-white py-3 px-6 mt-4 rounded-lg hover:text-gray-900 dark:hover:text-white transition-colors duration-300">
                                {{ $action['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>
