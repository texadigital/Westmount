@extends('public.layouts.app')

@section('title', 'Avis de décès - ' . $event->deceased_name)

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <a href="{{ route('public.death-events.index') }}" class="text-accent hover:underline">← Retour aux avis</a>

    <div class="bg-white rounded-lg shadow p-6 mt-4">
        <h1 class="text-3xl font-bold text-gray-900">{{ $event->deceased_name }}</h1>
        <p class="text-gray-600 mt-1">Décès le {{ optional($event->date_of_death)->format('Y-m-d') }}</p>
        @if($event->description)
            <div class="prose max-w-none mt-4">{!! nl2br(e($event->description)) !!}</div>
        @endif

        <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
            <div>Publié le {{ optional($event->published_at)->format('Y-m-d') }}</div>
            <div>{{ $event->contributions_count ?? 0 }} contributions à collecter</div>
        </div>
    </div>
</div>
@endsection
