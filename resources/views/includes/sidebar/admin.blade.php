<style>
    .back-sidebar:hover {
        background: #e45959 !important;
        color: #fff;
        
    }
    .back-sidebar{
        border: 1px solid red;
    }
</style>
<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu</li>
                <li>
                    <a href="{{route('admin.users.index')}}" class="{{Helper::isActive('admin/users*')}}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Users Management
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.roles.index')}}" class="{{Helper::isActive('admin/roles*')}}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Role
                    </a>
                </li>
                <li>
                    <a href="#" class="back-sidebar"  onclick="window.history.back()">
                        <i class="metismenu-icon pe-7s-back"> </i>
                        Back
                    </a>
                    {{-- <a class="btn btn-sm btn-transition btn btn-outline-danger btn-block" onclick="window.history.back()" ><i class="metismenu-icon pe-7s-back"> </i> Back</a> --}}
                </li>
            </ul>
        </div>
    </div>
</div>