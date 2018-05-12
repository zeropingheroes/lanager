<ul class="navbar-nav mr-auto">
    @foreach($navigationLinks as $navigationLink)
        @if($navigationLink->children->count())
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $navigationLink->title }}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @foreach($navigationLink->children as $child)
                        <a class="dropdown-item" href="{{ $child->url }}">{{ $child->title }}</a>
                    @endforeach
                </div>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{ $navigationLink->url }}">{{ $navigationLink->title }}</a>
            </li>
        @endif
    @endforeach
</ul>