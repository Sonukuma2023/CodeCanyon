<!-- Header/Navigation -->
<header class="main-header">
    <div class="container">
        <div class="header-grid">
            <a href="/" class="logo">
                <!-- <img src="" alt="logo" width="180" height="40"> -->
                <h2>LOGO</h2>     
            </a>
            
            <div class="search-container">
                <form class="search-form">
                    <input type="search" placeholder="Search 50,000+ items..." aria-label="Search">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <div class="user-actions">
                <a href="{{ route('cart.index') }}" class="cart-icon" aria-label="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count">0</span>
                </a>
                <div class="auth-buttons">
                    @guest
                        {{-- <a href="{{ route('guest.login') }}" class="btn btn-primary">Sign In</a>
                        <a href="{{ route('guest.register') }}" class="btn btn-primary">Join Free</a> --}}
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Join Free</a>
                    @endguest

                    @auth
                        <form action="{{ route('user.logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm" style="border:none;">Sign out</button>
                            <button type="submit" class="btn btn-primary btn-sm" style="border:none;">Become an Author</button>
                        </form>
                    @endauth

                </div>
            </div>
        </div>
    </div>
</header>

<div class="container" style="
    padding: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    z-index: 99999;">
    <nav class="main-nav">
        <ul class="nav-list" style="margin-bottom: 0;">
            @foreach ($navbarCategories  as $category)
                <li class="nav-item">
                    <a href="#" class="nav-link" style="color:white;">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
</div>
