<div class="app-header header-shadow">
    <div class="app-header__logo">
        <a href="{{ url('/') }}">
            <div class="logo-src"></div>
        </a>
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
    <div class="app-header__content">
        <div class="app-header-left">
            <div class="search-wrapper">
                <div class="input-holder">
                    <input type="text" class="search-input" placeholder="Type to search">
                    <button class="search-icon"><span></span></button>
                </div>
                <button class="close"></button>
            </div>
            <ul class="header-menu nav">
                {{-- <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-link-icon fa fa-database"> </i>
                        Statistics
                    </a>
                </li>
                <li class="btn-group nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-link-icon fa fa-edit"></i>
                        Projects
                    </a>
                </li>
                <li class="dropdown nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="nav-link-icon fa fa-cog"></i>
                        Settings
                    </a>
                </li> --}}

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link" href="javascript:void(0);" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre><i
                            class="nav-link-icon fa fa-globe"></i>
                        {{ __('navigation.language') }}

                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item {{Auth::user()->locale === 'en' ? 'active' : ''}}"
                            href="{{route('switch.language','en')}}">{{ __('navigation.english') }}</a>
                        <a class="dropdown-item {{Auth::user()->locale === 'th' ? 'active' : ''}}"
                            href="{{route('switch.language','th')}}">{{ __('navigation.thailand') }}
                        </a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="app-header-right">
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    @auth
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="usermanu" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <img width="42" class="rounded-circle"
                                        src="{{asset('assets/images/avatars/2.jpg')}}" alt="">
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('me.user.edit',Auth::user()->id) }}" tabindex="0"
                                        class="dropdown-item">โปรไฟล์</a>
                                    @can('for-superadmin-admin')
                                    <a href="{{ route('admin.users.index') }}" tabindex="0"
                                        class="dropdown-item">การจัดการผู้ใช้</a>
                                    <a href="{{ route('it.budgets.index') }}" tabindex="0"
                                        class="dropdown-item">การจัดการงบประมาณ</a>
                                    <a href="{{ route('admin.users.updateusers') }}" tabindex="0"
                                        class="dropdown-item">อัปเดตข้อมูลผู้ใช้</a>
                                    @endcan
                                    {{-- <button type="button" tabindex="0" class="dropdown-item">Settings</button>
                                    <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                    <button type="button" tabindex="0" class="dropdown-item">Actions</button> --}}
                                    <div tabindex="-1" class="dropdown-divider"></div>

                                    <button type="button" href="{{ route('logout') }}" tabindex="0"
                                        class="dropdown-item" onclick="event.preventDefault(); localStorage.clear();
                                            document.getElementById('logout-form').submit();">ออกจากระบบ</button>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading">
                                {{Auth::user()->name}}
                            </div>
                            <div class="widget-subheading">
                                {{Auth::user()->email}}
                            </div>
                        </div>
                        <div class="widget-content-right header-user-info ml-3">
                            {{-- <button type="button" class="btn-shadow p-1 btn btn-primary btn-sm show-toastr-example">
                                <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                            </button> --}}
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>