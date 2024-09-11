<li class="nav-item">
    <a data-bs-toggle="collapse" data-bs-target="#submenu-1-4" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation" class="dd-menu collapsed @if (str_contains(Route::currentRouteName(), 'edit')) active @endif"
        href="javascript:void(0)">{{ $label }}</a>
    <ul class="sub-menu collapse" id="submenu-1-4">
        @foreach ($lis as $li)
            <li class="nav-item @if (Route::currentRouteName() === $li['routeName']) active @endif"><a
                    href="{{ route($li['routeName']) }}">{{ $li['label'] }}</a>
            </li>
        @endforeach
    </ul>
</li>
