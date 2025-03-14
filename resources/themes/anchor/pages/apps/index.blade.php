<?php
    use function Laravel\Folio\{middleware, name};
    use App\Models\UserApp;
    use Livewire\Volt\Component;
    middleware('auth');
    name('apps');

    new class extends Component{
        public $apps;

        public function mount()
        {
            $this->apps = UserApp::where('user_id', auth()->user()->id)->get();
          
        }

        public function delete(UserApp $app)
        {
            $app->delete();
            $this->apps = auth()->user()->apps()->latest()->get();
        }
    }
?>

<x-layouts.app>
    @volt('apps')
        <x-app.container>

            <div class="flex items-center justify-between mb-5">
                <x-app.heading
                        title="Apps"
                        description="Check out your apps below"
                        :border="false"
                    />
                <x-button tag="a" href="/apps/create">New App</x-button>
            </div>

            @if($apps->isEmpty())
                <div class="w-full p-20 text-center bg-gray-100 rounded-xl">
                    <p class="text-gray-500">You don't have any apps yet.</p>
                </div>
            @else
                
            

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($apps as $app)
<a href="{{ $href ?? '' }}" @if($target ?? false) target="_blank" @endif class="flex flex-col overflow-hidden relative  w-full bg-custom-light-gray dark:bg-zinc-800 rounded-lg border duration-300 ease-out group border-slate-200 dark:border-zinc-700 hover:scale-[1.01]">
    <!-- Image at the top full-width -->
    <img src="/storage/{{ $app->app->image }}" class="w-full h-32 object-cover rounded-t-lg bg-white" alt="{{ $app->name ?? 'App Image' }}">

    <div class="flex flex-col justify-between flex-grow mt-4 p-4">
        <!-- Title and Description -->
        <div class="space-y-2">
            <span class="block text-lg font-bold tracking-tight leading-tight text-slate-700 dark:text-white">{{ $app->name ?? '' }}</span>
            <span class="block text-sm opacity-60 dark:text-zinc-200">{{ $app->description ?? '' }}</span>
        </div>

        <!-- Status and Manage App Button -->
        <div class="flex justify-between items-center mt-4">
            <span class="text-xs text-slate-600 dark:text-slate-300 font-bold text-green-300"> {{ $app->status ?? 'Unknown' }}</span>
            <span class="inline-flex items-center space-x-2">
                <span class="text-xs text-slate-600 dark:text-slate-300">Manage App</span>
                <svg class="w-4 h-4 stroke-1 stroke-slate-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M14 6l-1.41 1.41L16.17 11H4v2h12.17l-2.58 2.59L14 18l6-6-6-6z"></path>
                </svg>
            </span>
        </div>
    </div>
</a>
@endforeach

			
        </div>

            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
