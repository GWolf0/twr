@props([
    'images' => [],
])

@php
    $images = is_array($images) ? $images : array_filter(explode(',', $images ?? ''));
    if (empty($images)) {
        $images = ['https://placehold.co/600x400?text=No+Image'];
    }
    $id = 'carousel-' . Str::random(8);
@endphp

<div id="{{ $id }}" class="space-y-4 carousel-container">
    <div class="relative aspect-video rounded-xl overflow-hidden bg-muted group shadow-inner">
        <img class="main-image w-full h-full object-cover transition-opacity duration-300" src="{{ $images[0] }}"
            alt="Vehicle Image">

        @if (count($images) > 1)
            <button type="button"
                class="prev-btn absolute left-2 top-1/2 -translate-y-1/2 bg-background/80 hover:bg-background text-foreground p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </button>
            <button type="button"
                class="next-btn absolute right-2 top-1/2 -translate-y-1/2 bg-background/80 hover:bg-background text-foreground p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </button>
        @endif
    </div>

    @if (count($images) > 1)
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            @foreach ($images as $index => $image)
                <button type="button" data-index="{{ $index }}" data-src="{{ $image }}"
                    class="thumb-btn relative flex-shrink-0 w-24 aspect-video rounded-md overflow-hidden border-2 transition-all {{ $index === 0 ? 'border-primary' : 'border-transparent opacity-70 hover:opacity-100' }}">
                    <img src="{{ $image }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover">
                </button>
            @endforeach
        </div>
    @endif

    <script>
        (function() {
            const container = document.getElementById('{{ $id }}');
            if (!container) return;

            const images = @json($images);
            const mainImg = container.querySelector('.main-image');
            const thumbs = container.querySelectorAll('.thumb-btn');
            const prevBtn = container.querySelector('.prev-btn');
            const nextBtn = container.querySelector('.next-btn');

            let currentIndex = 0;

            function updateCarousel(index) {
                currentIndex = index;
                mainImg.style.opacity = '0.5';

                setTimeout(() => {
                    mainImg.src = images[currentIndex];
                    mainImg.style.opacity = '1';
                }, 150);

                thumbs.forEach((thumb, i) => {
                    if (i === currentIndex) {
                        thumb.classList.add('border-primary');
                        thumb.classList.remove('border-transparent', 'opacity-70');
                    } else {
                        thumb.classList.remove('border-primary');
                        thumb.classList.add('border-transparent', 'opacity-70');
                    }
                });
            }

            thumbs.forEach((thumb, index) => {
                thumb.addEventListener('click', () => updateCarousel(index));
            });

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    const next = (currentIndex - 1 + images.length) % images.length;
                    updateCarousel(next);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    const next = (currentIndex + 1) % images.length;
                    updateCarousel(next);
                });
            }
        })();
    </script>
</div>
