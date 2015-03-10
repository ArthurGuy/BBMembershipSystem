<nav class="mainSidenav" role="navigation">

    <header>
        <span class="sidenav-brand">
            <a href="{{ route('home') }}"><img class="" src="/img/logo.png" height="50" /></a>
            @if (!Auth::guest() && (Auth::user()->status != 'active') )
                {{ HTML::statusLabel(Auth::user()->status) }}
            @endif
        </span>
        @if (!Auth::guest())
        <ul class="nav memberAccountLinks">
            <li class="withAction">
                <a href="{{ route('account.show', [Auth::id()]) }}">
                    Your Membership
                    @if (count(Auth::user()->getAlerts()) > 0)
                    <span class="badge">{{ count(Auth::user()->getAlerts()) }}</span>
                    @endif
                </a>
                <a class="toggleSettings" href=""><span class="glyphicon glyphicon-cog"></span></a>
            </li>
            <ul class="nav nested-nav accountSettings">
                {{ HTML::sideNavLink('Edit Your Account', 'account.edit', [Auth::id()]) }}
                {{ HTML::sideNavLink('Edit Your Profile', 'account.profile.edit', [Auth::id()]) }}
            </ul>

            {{ HTML::sideNavLink('Manage Your Balance', 'account.balance.index', [Auth::id()]) }}

        </ul>
        @endif
    </header>


    <ul class="nav">
        {{ HTML::sideNavLink('Members', 'members.index') }}
        {{ HTML::sideNavLink('Member Storage', 'storage_boxes.index') }}
        {{ HTML::sideNavLink('Tools and Equipment', 'equipment.index') }}
        {{ HTML::sideNavLink('Activity', 'activity.index') }}
        {{ HTML::sideNavLink('Stats', 'stats.index') }}
        {{ HTML::sideNavLink('Proposals', 'proposals.index') }}
        {{ HTML::sideNavLink('Resources', 'resources.index') }}
        @if (!Auth::guest() && Auth::user()->isAdmin())
        {{ HTML::sideNavLink('Members (Admin)', 'account.index') }}
        {{ HTML::sideNavLink('Roles (Admin)', 'roles.index') }}
        {{ HTML::sideNavLink('Payments (Admin)', 'payments.index') }}
        @endif
    </ul>

    <ul class="nav secondaryNav">
        @if (Auth::guest())
            {{ HTML::sideNavLink('Login', 'login') }}
            {{ HTML::sideNavLink('Become a Member', 'register') }}
        @else

            {{ HTML::sideNavLink('Logout', 'logout') }}
        @endif
    </ul>
</nav>