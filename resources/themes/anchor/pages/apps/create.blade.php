<?php 

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use App\Models\App;

middleware('auth');
name('apps.create');

new class extends Component
{
    public $apps;

    public function mount()
    {
        $this->apps = App::all();
    }
};
?>

<x-layouts.app>
    @volt('apps.create')
    <x-app.container>
        <h1 class="text-2xl font-bold mb-4">Available Apps</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            @foreach ($apps as $app)
                <!-- <x-app.dashboard-card
                    href="{{ route('apps.deploy', ['app' => $app->id]) }}"
                    title="{{ $app->name }}"
                    description="{{ $app->description }}"
                    link_text="Deploy"
                    image="/storage/{{ $app->image ?? '/default-app.png' }}"
                /> -->

                <a href="{{ route('apps.deploy', ['app' => $app->id]) }}" @if($target ?? false) target="_blank" @endif class="flex flex-col overflow-hidden relative  w-full bg-custom-light-gray dark:bg-zinc-800 rounded-lg border duration-300 ease-out group border-slate-200 dark:border-zinc-700 hover:scale-[1.01]">
    <!-- Image at the top full-width -->
    <img src="/storage/{{ $app->image }}" class="w-full h-32 object-cover rounded-t-lg bg-white"  alt="{{ $app->name ?? 'App Image' }}">

    <div class="flex flex-col justify-between flex-grow mt-4 p-4">
        <!-- Title and Description -->
        <div class="space-y-2">
            <span class="block text-lg font-bold tracking-tight leading-tight text-slate-700 dark:text-white">{{ $app->name ?? '' }}</span>
            <span class="block text-sm opacity-60 dark:text-zinc-200">{{ $app->description ?? '' }}</span>
        </div>

        <!-- Status and Manage App Button -->
        <div class="flex justify-between items-center mt-4">
            <!-- <span class="text-xs text-slate-600 dark:text-slate-300 font-bold text-green-300"> {{ $app->status ?? 'Unknown' }}</span> -->
            <span class="inline-flex items-center space-x-2">
                <span class="text-xs text-slate-600 dark:text-slate-300">Deploy App</span>
                <svg class="w-4 h-4 stroke-1 stroke-slate-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M14 6l-1.41 1.41L16.17 11H4v2h12.17l-2.58 2.59L14 18l6-6-6-6z"></path>
                </svg>
            </span>
        </div>
    </div>
</a>

            @endforeach
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app>
