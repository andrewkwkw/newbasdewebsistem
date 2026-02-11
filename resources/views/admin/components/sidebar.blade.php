<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand" style="height: 100%">
            <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('img/logo.webp') }}" height="150"></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('login') }}">BS</a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}"><i
                        class="fas fa-dashboard"></i><span>Dashboard</span></a>
            <li class='nav-item'>
                <a href="{{ route('jenis_sampah') }}"><i class="fas fa-gift"></i><span>Jenis Sampah</span></a>
            </li>
            <li class='nav-item'>
                <a href="{{ route('admin.laporan') }}"><i class="fas fa-file-invoice"></i><span>Laporan</span></a>
            </li>
            </li>
    </aside>
</div>
