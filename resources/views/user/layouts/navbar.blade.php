<!-- Header/Navigation -->
<header class="main-header">
    <div class="container">
        <div class="header-grid">
            <a href="/" class="logo">
                <!-- <img src="" alt="logo" width="180" height="40"> -->
                <h2>LOGO</h2>
            </a>

            {{-- <div class="search-container">
                <form class="search-form" method ="post" id="search">
                    <input type="search" placeholder="Search 50,000+ items..." aria-label="Search">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div> --}}

            {{-- **************************search functuionality ******************************** --}}
            <div class="search-container">
                <form action="{{ route('search.items') }}" class="search-form" method="POST">
                    @csrf
                    <input type="search" name="query" placeholder="Search 50,000+ items..." aria-label="Search" value="{{ old('query', $query ?? '') }}">

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

                <!-- @auth
                    <form action="{{ route('user.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm" style="border:none;">Sign out</button>
                        <button type="submit" class="btn btn-primary btn-sm" style="border:none;">Become an
                            Author</button>
                    </form>
                @endauth -->

                @auth
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5 me-1"></i>
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="{{ route('user.OrderHistory') }}"><i class="bi bi-bag-check me-2"></i>Order History</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.ProfileEdit') }}"><i class="bi bi-person-lines-fill me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.wishlistPage') }}"><i class="bi bi-heart-fill me-2"></i>Wishlist</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</button>
                            </form>
                        </li>
                    </ul>
                </div> 
                @endauth


            </div>
        </div>
    </div>
    </div>
</header>

<div class="container"
    style="
    padding: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    z-index: 99999;">
    <nav class="main-nav">
        <ul class="nav-list" style="margin-bottom: 0;">
            {{-- @foreach ($navbarCategories as $category)
                <li class="nav-item">
                    <a href="single/{{$category}}" class="nav-link" style="color:white;">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach --}}
            @foreach ($navbarCategories as $category)
                <li class="nav-item">
                    <a href="{{ url('category/products/' . strtolower($category->name)) }}" class="nav-link"
                        style="color:white;">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach

        </ul>
    </nav>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    //    $(document).ready(function() {
    //         $('#search').on('submit', function(e) {
    //             e.preventDefault();

    //             let query = $('input[name="query"]').val();
    //             let token = $('input[name="_token"]').val();

    //             $.ajax({
    //                 url: "{{ route('search.items') }}",
    //                 method: "POST",
    //                 data: {
    //                     _token: token,
    //                     query: query
    //                 },
    //                 success: function(response) {
    //                     console.log(response);

    //                     let output = '';

    //                     response.forEach(function(item) {
    //                         output += `
    //                             <div class="search-item" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
    //                                 <img src="/storage/${item.thumbnail}" alt="${item.name}" style="width:100px; height:auto;">
    //                                 <h4>${item.name}</h4>
    //                                 <p>${item.description}</p>
    //                                 <p><strong>Regular Price:</strong> $${item.regular_license_price}</p>
    //                                 <p><strong>Extended Price:</strong> $${item.extended_license_price}</p>
    //                             </div>
    //                         `;
    //                     });

    //                     $('#search-results').html(output);
    //                 },
    //                 error: function(xhr) {
    //                     console.error('Error:', xhr.responseText);
    //                     $('#search-results').html('<p style="color:red;">An error occurred while searching.</p>');
    //                 }
    //             });
    //         });
    //     });

    $(document).ready(function() {
        $.ajax({
            url: "{{ route('user.userCartCount') }}",
            method: "GET",
            success: function(response) {
                $('.cart-count').text(response.cartCount);
            },
            error: function(xhr) {
                console.error("Failed to fetch cart count:", xhr);
            }
        });
    });
</script>
