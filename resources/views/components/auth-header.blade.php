@props([
    'title',
    'description',
])

<div class="mb-4 text-center">
    <h4 class="mb-2 fw-bold" style="background: linear-gradient(135deg, #191970, #BA55D3); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        {{ $title }}
    </h4>
    <p class="mb-6 text-muted fs-6">{{ $description }}</p>
</div>