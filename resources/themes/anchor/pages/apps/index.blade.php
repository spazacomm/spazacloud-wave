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
                
            

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            @foreach($apps as $app)
            <x-app.dashboard-card
				href="https://devdojo.com/wave/docs"
				target="_blank"
				title="{{$app->name}}"
				description="{{$app->description}}"
				link_text="View The Docs"
				image="/wave/img/docs.png"
			/>
            @endforeach
			
        </div>

            @endif
        </x-app.container>
    @endvolt
</x-layouts.app>
