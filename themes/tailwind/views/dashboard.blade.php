<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg h-[calc(100vh-250px)]">
                <div class="p-6 bg-white border-b border-gray-200">
                        {{-- <chat-message :user="{{ $user }}" :current-user="{{ auth()->user() }}" /> --}}
                        <chat-box :current-user="{{ auth()->user() }}"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
