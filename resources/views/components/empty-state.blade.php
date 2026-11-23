@props(['message'])

<div class="empty">
    <svg class="empty-icon" viewBox="0 0 48 48" aria-hidden="true">
        <path fill="currentColor" d="M24 5.5c-7.4 0-13 5.5-13 13.1 0 9.2 13 24 13 24s13-14.8 13-24C37 11 31.4 5.5 24 5.5zm0 17.2a4.4 4.4 0 1 1 0-8.8 4.4 4.4 0 0 1 0 8.8z"/>
    </svg>
    <p>{{ $message }}</p>
</div>
