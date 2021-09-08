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
                    <a href="{{route('legal.dashboard')}}" class="{{Helper::isActive('legal/dashboard*')}}">
                        <i class="metismenu-icon pe-7s-monitor"></i>
                        Dashboard
                    </a>
                </li>
                <li class="app-sidebar__heading">Contract</li>
                <li class="{{Helper::isActive('legal/contract-request*')}}">
                    <a href="#" class="{{Helper::isActive('legal/contract-request*')}}">
                        <i class="metismenu-icon pe-7s-hammer"></i>
                        E-filing
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('legal.contract-request.index')}}"
                                class="{{Helper::isActive('legal/contract-request')}}">
                                <i class="metismenu-icon"></i>
                                Contract Request
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li>
                    <a href="{{route('legal.contract-request.index')}}"
                class="{{Helper::isActive('legal/contract-request*')}}">
                <i class="metismenu-icon pe-7s-rocket"></i>
                Contract Request Form
                </a>
                </li> --}}
                {{-- <li>
                    <a href="{{route('admin.roles.index')}}" class="{{Helper::isActive('admin/roles*')}}">
                <i class="metismenu-icon pe-7s-rocket"></i>
                Role
                </a>
                </li> --}}
                {{-- <li>
                    <a href="{{route('admin.permissions.index')}}" class="{{Helper::isActive('admin/permissions*')}}">
                <i class="metismenu-icon pe-7s-rocket"></i>
                Permissions
                </a>
                </li> --}}
                @can('for-superadmin')
                <li class="app-sidebar__heading">AdminManagement</li>
                <li class="{{Helper::isActive('legal/adminmanagement/*')}}">
                    <a href="#" class="{{Helper::isActive('legal/adminmanagement/*')}}">
                        <i class="metismenu-icon pe-7s-diamond"></i>
                        Approvals
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('legal.adminmanagement.approval.index')}}"
                                class="{{Helper::isActive('legal/adminmanagement/approval')}}">
                                <i class="metismenu-icon"></i>
                                ApprovalManagement
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
            </ul>
        </div>
    </div>
</div>