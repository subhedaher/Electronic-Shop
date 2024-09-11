<li class="nav-item">
    <a href="{{ route($routeName) }}" class="nav-link @if (Route::currentRouteName() === $routeName) active @endif">
        <i class="nav-icon {{ $icon }}"></i>
        <p>
            {{ $label }}
        </p>
    </a>
</li>
