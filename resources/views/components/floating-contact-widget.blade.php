@php
    $hotline = config('rsvp.hotline_number');
    $zaloUrl = config('rsvp.zalo_url');
@endphp

@if ($hotline || $zaloUrl)
<div class="fixed bottom-6 right-4 z-50 flex flex-col gap-3 sm:bottom-8 sm:right-6">

    @if ($hotline)
    <a href="tel:{{ $hotline }}"
       aria-label="Gọi hotline {{ $hotline }}"
       class="flex items-center justify-center w-12 h-12 rounded-full shadow-lg transition-colors duration-200"
       style="background-color: #2D3A2F;"
       onmouseover="this.style.backgroundColor='#D4B37D'"
       onmouseout="this.style.backgroundColor='#2D3A2F'">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="#D4B37D" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
        </svg>
    </a>
    @endif

    @if ($zaloUrl)
    <a href="https://zalo.me/{{ $zaloUrl }}"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Liên hệ qua Zalo"
       class="flex items-center justify-center w-12 h-12 rounded-full shadow-lg transition-colors duration-200"
       style="background-color: #2D3A2F;"
       onmouseover="this.style.backgroundColor='#D4B37D'"
       onmouseout="this.style.backgroundColor='#2D3A2F'">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 48 48" aria-hidden="true">
            <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle"
                  font-family="Arial, sans-serif" font-weight="bold" font-size="26"
                  fill="#D4B37D">Z</text>
        </svg>
    </a>
    @endif

</div>
@endif
