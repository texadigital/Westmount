@extends('public.layouts.app')

@section('title', 'Événements de décès')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Avis de décès publiés</h1>

    @if($events->isEmpty())
        <div class="bg-white rounded-lg shadow p-6 text-gray-600">Aucun événement publié pour le moment.</div>
    @else
        <div class="space-y-4">
            @foreach($events as $event)
                <a href="{{ route('public.death-events.show', $event) }}" class="block bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $event->deceased_name }}</h2>
                            <p class="text-gray-600">Décès le {{ optional($event->date_of_death)->format('Y-m-d') }}</p>
                            @if($event->description)
                                <p class="mt-2 text-gray-700 line-clamp-2">{{ $event->description }}</p>
                            @endif
                        </div>
                        <span class="text-sm text-gray-500">Publié le {{ optional($event->published_at)->format('Y-m-d') }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $events->links() }}
        </div>
    @endif
</div>
@endsection
