<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="#" style="color:#fff;">Admin Dashboard</a>
        <a class="sidebar-brand brand-logo-mini" href="#" style="color:#fff;">AD</a>
    </div>
    <ul class="nav">
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle " src="{{asset('backend/assets/images/faces/face15.jpg')}}" alt="">
                        <span class="count bg-success"></span>
                    </div>
                    <div class="profile-name">
                        <h5 class="mb-0 font-weight-normal">{{ Auth::user()->name }}</h5>
                        <span>Gold Member</span>
                    </div>
                </div>            
            </div>
        </li>

        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-category" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
                </span>
                <span class="menu-title">Category</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-category">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.category') }}">Add Category</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.viewcategory') }}">View All</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-products" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
                </span>
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-products">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.product') }}">Add Product</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.viewproduct') }}">View All</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-users" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-icon">
                <i class="mdi mdi-laptop"></i>
                </span>
                <span class="menu-title">Users</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-users">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.user') }}">Add User</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.viewusers') }}">View All</a></li>
                </ul>
            </div>
        </li>
		
		 <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-community" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-icon">
                <i class="mdi mdi-bullhorn"></i>
                </span>
                <span class="menu-title">Community</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-community">
                <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="#">Add Community</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('admin.viewCommunities') }}">View All</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" href="#">
                <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Orders</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" href="#">
                <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Reviews</span>
            </a>
        </li>
        
    </ul>
</nav>