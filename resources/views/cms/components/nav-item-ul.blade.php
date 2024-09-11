<li class="nav-item @if (str_contains(Route::currentRouteName(), $routMain)) menu-open @endif">
    <a href="#" class="nav-link @if (str_contains(Route::currentRouteName(), $routMain)) active @endif">
        <i class="nav-icon {{ $icon }}"></i>
        <p>
            {{ $label }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    @foreach ($lis as $li)
        @can($li['permission'])
            <ul class="nav nav-treeview" style="display: @if (str_contains(Route::currentRouteName(), $routMain)) block @else none @endif">
                <li class="nav-item">
                    <a href="{{ route($li['route']) }}"
                        class="nav-link @if (Route::currentRouteName() == $li['route']) active @elseif(Route::currentRouteName() == $li['routTrash'])) active @elseif(Route::currentRouteName() == $li['routEdit'])) active @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ $li['label'] }}</p>
                    </a>
                </li>
            </ul>
        @endcan
    @endforeach
</li>
