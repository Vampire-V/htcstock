<div class="app-sidebar sidebar-shadow bg-premium-dark sidebar-text-light">
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
                    <a href="{{route('kpi.dashboard')}}" class="{{Helper::isActive('kpi/dashboard*')}}">
                        <i class="metismenu-icon pe-7s-monitor"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.self-evaluation.index')}}" class="{{Helper::isActive('kpi/self-evaluation*')}}">
                        <i class="metismenu-icon"></i>
                        Self Evaluation
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.rule-list.index')}}" class="{{Helper::isActive('kpi/rule-list*')}}">
                        <i class="metismenu-icon"></i>
                        Rule List
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.template.index')}}" class="{{Helper::isActive('kpi/template*')}}">
                        <i class="metismenu-icon"></i>
                        Rule Template
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.staff.index')}}" class="{{Helper::isActive('kpi/evaluation-form/staff*')}}">
                        <i class="metismenu-icon"></i>
                        Evaluation Form
                    </a>
                </li>
                {{-- <li>
                    <a href="{{route('kpi.set-target.index')}}" class="{{Helper::isActive('kpi/set-target*')}}">
                        <i class="metismenu-icon"></i>
                        Set Target
                    </a>
                </li>
                <li>
                    <a href="{{route('kpi.set-actual.index')}}" class="{{Helper::isActive('kpi/set-actual*')}}">
                        <i class="metismenu-icon"></i>
                        Set Actual
                    </a>
                </li> --}}
                <li>
                    <a href="{{route('kpi.evaluation-review.index')}}" class="{{Helper::isActive('kpi/evaluation-review*')}}">
                        <i class="metismenu-icon"></i>
                        Evaluation Review
                    </a>
                </li>
                <li>
                    <a href="#" class="{{Helper::isActive('kpi/report*')}}">
                        <i class="metismenu-icon"></i>
                        Report
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>