<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use App\Models\App;
use App\Models\UserApp;

middleware('auth');
name('apps.deploy');

new class extends Component
{
    public $app;

    public $name;

    public $description;
    
    #[Validate('array')]
    public $env_variables = [];

    #[Validate('array')]
    public $resources = [];

    public function mount()
    {
        $appId = request()->query('app');
        $this->app = App::findOrFail($appId);


        // Parse the default_env string into an associative array
        $defaultEnvString = $this->app->default_env ?? '';
        $defaultEnvArray = [];

        foreach (explode("\n", $defaultEnvString) as $line) {
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $defaultEnvArray[trim($key)] = trim($value);
            }
        }

        // Initialize env_variables with the parsed default_env array
        $this->env_variables = $defaultEnvArray;

        // dd($this->app);
    }

    public function save()
    {
        $this->validate();

        UserApp::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'description' => $this->description,
            'app_id' => $this->app->id,
            'env_variables' => $this->env_variables,
            'resources' => $this->resources,
            'status' => 'pending',
        ]);

        session()->flash('message', 'App created successfully!');
        return redirect()->route('apps');
    }
};
?>

<x-layouts.app>
    @volt('apps.deploy')
    <x-app.container>
        <x-elements.back-button
            class="max-w-full mx-auto mb-3"
            text="Back to Apps"
            :href="route('apps.create')"
        />

        <h2 class="text-2xl font-bold mb-4">Deploy {{ $app->name }}</h2>

        <form wire:submit.prevent="save">

            <div class="mb-4">
                <label class="block font-medium">App Name</label>
                <input type="text" wire:model.defer="name" class="w-full p-3 border rounded">
            </div>

            <div class="mb-4">
                <label class="block font-medium">Description</label>
                <textarea wire:model.defer="description" class="w-full p-3 border rounded"></textarea>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-4">Environment Variables</label>
                @foreach($env_variables as $key => $value)
                    <div class=" items-center mb-4">
                        <label class="block w-1/3 font-medium">{{ $key }}</label>
                        <input type="text" wire:model.defer="env_variables.{{ $key }}" class="w-2/3 p-1 border rounded" value="{{ $value }}">
                    </div>
                @endforeach
            </div>

            <!-- <div class="mb-4">
                <label class="block font-medium">Resources</label>
                <input type="text" wire:model.defer="resources" class="w-full p-3 border rounded">
            </div> -->

            <div class="flex justify-end space-x-3">
                <a href="{{ route('apps.create') }}" class="px-4 py-2 bg-gray-400 text-white rounded">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                    Deploy App
                </button>
            </div>
        </form>
    </x-app.container>
    @endvolt
</x-layouts.app>
