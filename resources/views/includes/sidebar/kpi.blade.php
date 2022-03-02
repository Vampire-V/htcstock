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
                <li>
                    <a href="{{route('kpi.dashboard')}}" class="{{Helper::isActive('kpi/dashboard*')}}">
                        <i class="metismenu-icon pe-7s-monitor"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.self-evaluation.index',['user'=>[auth()->id()],'year'=>[date('Y')]])}}"
                        class="{{Helper::isActive('kpi/self-evaluation*')}}">
                        <i class="metismenu-icon pe-7s-file"></i>
                        Evaluation Self
                    </a>
                </li>
                @canany(['admin-kpi'])
                <li>
                    <a href="{{route('kpi.set-actual.index')}}" class="{{Helper::isActive('kpi/set-actual*')}}">
                        <i class="metismenu-icon pe-7s-ticket"></i>
                        Set Actual
                    </a>
                </li>
                @endcanany
                {{-- admin --}}
                @canany(['admin-kpi'])
                <li class="app-sidebar__heading">Operation</li>

                <li>
                    <a href="{{route('kpi.rule-list.index')}}" class="{{Helper::isActive('kpi/rule-list*')}}">
                        <i class="metismenu-icon pe-7s-upload"></i>
                        Rule List
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.template.index')}}" class="{{Helper::isActive('kpi/template*')}}">
                        <i class="metismenu-icon pe-7s-box1"></i>
                        Template
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.staff.index')}}" class="{{Helper::isActive('kpi/evaluation-form/staff*')}}">
                        <i class="metismenu-icon pe-7s-folder"></i>
                        Evaluation Form
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.for-eddy.index')}}" class="{{Helper::isActive('kpi/for-eddy')}}">
                        <i class="metismenu-icon pe-7s-news-paper"></i>
                        Employee report
                    </a>
                </li>
                @endcanany
                {{-- end admin --}}

                {{-- admin manager --}}
                @canany(['admin-kpi','manager-kpi'])
                <li class="app-sidebar__heading">Manager</li>
                <li class="{{Helper::isActive('kpi/evaluation-review*')}}">
                <li>
                    <a href="{{route('kpi.evaluation-review.index')}}"
                        class="{{Helper::isActive('kpi/evaluation-review*')}}">
                        <i class="metismenu-icon pe-7s-display2"></i>
                        Evaluation Review
                    </a>
                </li>
                </li>
                @endcanany
                {{-- end admin manager --}}

                {{-- for eddy --}}
                @can('parent-admin-kpi')
                <li class="app-sidebar__heading">Menu For Admin</li>
                <li class="{{Helper::isActive('kpi/for-eddy*')}}">
                    <a href="#" class="{{Helper::isActive('kpi/for-eddy*')}}">
                        <i class="metismenu-icon pe-7s-diamond"></i>
                        Edit Evaluate
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('kpi.for-eddy.index')}}" class="{{Helper::isActive('kpi/for-eddy')}}">
                                <i class="metismenu-icon"></i>
                                Employee report
                            </a>
                        </li>
                        <li>
                            <a href="{{route('kpi.for-eddy.rulesready')}}" class="{{Helper::isActive('kpi/for-eddy/rules/ready')}}">
                                <i class="metismenu-icon"></i>
                                Rules report
                            </a>
                        </li>
                        <li>
                            <a href="{{route('kpi.for-eddy.deadline')}}" class="{{Helper::isActive('kpi/for-eddy/config/deadline')}}">
                                <i class="metismenu-icon"></i>
                                Config Dead Line
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('kpi.set-period.index')}}" class="{{Helper::isActive('kpi/set-period*')}}">
                        <i class="metismenu-icon pe-7s-date"></i>
                        Set Periods
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.transfer-rules.index')}}" class="{{Helper::isActive('kpi/transfer-rules*')}}">
                        <i class="metismenu-icon pe-7s-repeat"></i>
                        Transfer rules
                    </a>
                </li>
                @endcan
                {{-- end for eddy --}}
            </ul>
        </div>
    </div>
</div>
