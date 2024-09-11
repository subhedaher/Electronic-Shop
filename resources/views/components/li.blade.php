<li class="nav-item">
    <a href="{{ route($routeName) }}" class="@if (Route::currentRouteName() == $routeName) active @endIf"
        aria-label="Toggle navigation">{{ $label }}</a>
</li>
