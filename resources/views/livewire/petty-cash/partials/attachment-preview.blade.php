@php
    $extension = pathinfo($file, PATHINFO_EXTENSION);
    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
@endphp

@if ($isImage)
    <div class="relative group">
        <img src="{{ asset('storage/' . $file) }}"
            class="w-full h-32 object-cover rounded border cursor-pointer hover:opacity-75 transition"
            onclick="window.open('{{ asset('storage/' . $file) }}', '_blank')">
        <div class="absolute bottom-1 right-1 bg-black bg-opacity-50 text-white text-[10px] px-1 rounded">
            IMG
        </div>
    </div>
@else
    <a href="{{ asset('storage/' . $file) }}" target="_blank"
        class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded hover:bg-gray-50 transition text-gray-500">
        <span class="text-2xl mb-1">📄</span>
        <span class="text-xs font-medium">Buka File ({{ strtoupper($extension) }})</span>
    </a>
@endif
