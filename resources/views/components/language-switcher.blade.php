<div class="dropdown">
    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
        @if(session('locale') == 'fr')
            🇫🇷 Français
        @elseif(session('locale') == 'ar')
            🇸🇦 العربية
        @else
            🇬🇧 English
        @endif
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2 {{ session('locale') == 'fr' ? 'active' : '' }}" 
               href="{{ url('/lang/fr') }}">
                <span>🇫🇷</span> Français
                @if(session('locale') == 'fr')
                    <i class="fas fa-check ms-auto text-success"></i>
                @endif
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2 {{ session('locale') == 'ar' ? 'active' : '' }}" 
               href="{{ url('/lang/ar') }}">
                <span>🇸🇦</span> العربية
                @if(session('locale') == 'ar')
                    <i class="fas fa-check ms-auto text-success"></i>
                @endif
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2 {{ session('locale') == 'en' ? 'active' : '' }}" 
               href="{{ url('/lang/en') }}">
                <span>🇬🇧</span> English
                @if(session('locale') == 'en')
                    <i class="fas fa-check ms-auto text-success"></i>
                @endif
            </a>
        </li>
    </ul>
</div>