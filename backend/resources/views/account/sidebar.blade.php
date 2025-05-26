<div class="profile-title-res ns-title cos-title text-capitalize"><span>Welcome back, {{ Auth::user()->name }}.</span></div>
<div class="profile-sidebar-body pr-4">
    <a href="{{ route('account.account') }}" class="btn psb-row-btn ptab-btn {{ Route::is('account.account') ? 'active' : '' }}"><span class="icon-user icon"></span> My Account</a>
    @if(Auth::user()->phoned_Signed == 1)
        <a href="{{ route('account.password') }}" class="btn psb-row-btn ptab-btn {{ Route::is('account.password') ? 'active' : '' }}"><span class="icon-lock icon"></span> Change Password</a>
    @endif
    <a href="{{ route('account.my-favorites') }}" class="btn psb-row-btn ptab-btn {{ Route::is('account.my-favorites') ? 'active' : '' }}"><span class="icon-heart-empty icon"></span> My Favorites</a>
    <a href="{{ route('account.service-task') }}" class="btn psb-row-btn ptab-btn {{ Route::is('account.service-task') ? 'active' : '' }}"><span class="icon-service icon"></span> My Services / Tasks</a>
    <a href="{{ route('account.subscription') }}" class="btn psb-row-btn ptab-btn {{ Route::is('account.subscription') ? 'active' : '' }}"><span class="icon-rocket icon"></span> Subscriptions</a>
    <a href="{{ route('account.reviews') }}" class="btn psb-row-btn ptab-btn {{ Route::is('account.reviews') ? 'active' : '' }}"><span class="icon-star icon"></span> My Reviews</a>
    <a href="javascript:void(0)" class="btn psb-row-btn ptab-btn logout-btn"><span class="icon-logout icon"></span> Logout</a>
</div>
