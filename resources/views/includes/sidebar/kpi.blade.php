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
    <div class="scrollbar-sidebar" id="side_bar">
        <div class="app-sidebar__inner" >
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu</li>
                {{-- admin manager --}}
                {{-- @can('admin-manager-kpi') --}}
                <li>
                    <a href="{{route('kpi.dashboard')}}" class="{{Helper::isActive('kpi/dashboard*')}}">
                        <i class="metismenu-icon pe-7s-monitor"></i>
                        Dashboard
                    </a>
                </li>
                {{-- @endcan --}}
                {{-- end admin manager --}}
                <li>
                    <a href="{{route('kpi.self-evaluation.index',['user'=>[auth()->id()],'year'=>[date('Y')]])}}"
                        class="{{Helper::isActive('kpi/self-evaluation*')}}">
                        <i class="metismenu-icon pe-7s-file"></i>
                        Self Evaluation
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.set-actual.index')}}" class="{{Helper::isActive('kpi/set-actual*')}}">
                        <i class="metismenu-icon pe-7s-ticket"></i>
                        Set Actual
                    </a>
                </li>
                {{-- admin --}}
                @can('admin-kpi')
                <li class="app-sidebar__heading">Menu For Operation</li>
                <li>
                    <a href="{{route('kpi.set-period.index')}}" class="{{Helper::isActive('kpi/set-period*')}}">
                        <i class="metismenu-icon pe-7s-date"></i>
                        Set Periods
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.rule-list.index')}}" class="{{Helper::isActive('kpi/rule-list*')}}">
                        <i class="metismenu-icon pe-7s-upload"></i>
                        Rule List
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.template.index')}}" class="{{Helper::isActive('kpi/template*')}}">
                        <i class="metismenu-icon pe-7s-box1"></i>
                        Rule Template
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.staff.index')}}" class="{{Helper::isActive('kpi/evaluation-form/staff*')}}">
                        <i class="metismenu-icon pe-7s-folder"></i>
                        Evaluation Form
                    </a>
                </li>
                @endcan
                {{-- end admin --}}
                {{-- admin manager --}}
                @can('admin-manager-kpi')
                <li class="app-sidebar__heading">Menu For Manager</li>
                <li class="{{Helper::isActive('kpi/evaluation-review*')}}">
                <li>
                    <a href="{{route('kpi.evaluation-review.index')}}"
                        class="{{Helper::isActive('kpi/evaluation-review*')}}">
                        <i class="metismenu-icon pe-7s-display2"></i>
                        Evaluation Review
                    </a>
                </li>
                </li>
                @endcan
                {{-- end admin manager --}}

                {{-- for eddy --}}
                @if (auth()->user()->username === '70037455' || auth()->user()->username === '70037539' || auth()->user()->username === '70038032')
                <li class="app-sidebar__heading">Menu For Eddy</li>
                <li class="{{Helper::isActive('kpi/for-eddy*')}}">
                    <a href="#" class="{{Helper::isActive('kpi/for-eddy*')}}">
                        <i class="metismenu-icon pe-7s-diamond"></i>
                        Edit Evaluate
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('kpi.for-eddy.index')}}" class="{{Helper::isActive('kpi/for-eddy*')}}">
                                <i class="metismenu-icon"></i>
                                Edite actual / ach%
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                {{-- end for eddy --}}
            </ul>
        </div>
    </div>
</div>