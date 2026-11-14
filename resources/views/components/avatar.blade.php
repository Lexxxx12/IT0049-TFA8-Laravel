@props(['src' => null, 'name' => 'Profile'])
<span {{ $attributes->merge(['class' => 'avatar']) }}>
@if($src)
<img src="{{ $src }}" alt="{{ $name }} profile picture" loading="lazy">
@else
<svg viewBox="0 0 100 100" role="img" aria-label="{{ $name }}: no profile picture" focusable="false"><circle cx="50" cy="50" r="50" fill="#e4e6eb"/><circle cx="50" cy="37" r="19" fill="#a8adb5"/><path d="M12 100v-8c0-19 17-31 38-31s38 12 38 31v8Z" fill="#a8adb5"/></svg>
@endif
</span>
