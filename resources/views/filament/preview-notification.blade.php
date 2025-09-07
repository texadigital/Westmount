<div class="space-y-4">
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sujet</h3>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $template->subject }}</p>
    </div>
    
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Corps du Message</h3>
        <div class="mt-1 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <pre class="whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-300">{{ $template->body }}</pre>
        </div>
    </div>
    
    @if($template->variables && count($template->variables) > 0)
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Variables Disponibles</h3>
        <div class="mt-1 flex flex-wrap gap-2">
            @foreach($template->variables as $variable)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ $variable }}
                </span>
            @endforeach
        </div>
    </div>
    @endif
</div>
