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
                <x-app.dashboard-card
                    href="{{ route('apps.deploy', ['app' => $app->id]) }}"
                    title="{{ $app->name }}"
                    description="{{ $app->description }}"
                    link_text="Deploy"
                    image="{{ $app->image ?? '/default-app.png' }}"
                />
            @endforeach
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app>
