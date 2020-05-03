<div>
    <!-- Well begun is half done. - Aristotle -->
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-primary">
        <a class="navbar-brand" href="index.html">ระบบเบิกพัสดุคลัง</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button
        ><!-- Navbar Search-->
        <form id="formSearch" class="d-none d-sm-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control " type="text" name="inputSearch" style="font-weight:400;" placeholder="ค้นหา กรอก..." aria-label="Search" aria-describedby="basic-addon2"/>
                <div class="input-group-append">
                    <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <style>
        </style>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-end" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{-- <i class="fas fa-user fa-fw"></i> --}}
                    <img class="rounded-circle" @if(Auth::user()->profile)src="/upload/profile/{{Auth::user()->profile}}" @else src="https://png.pngtree.com/element_our/png/20181206/users-vector-icon-png_260862.jpg" @endif alt="myProfiles" width="35" height="35">
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="/setting">ข้อมูลส่วนตัว</a>
                    <a class="dropdown-item" href="/setting/pass">รหัสผ่าน</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" id="logout" onclick="axios.post('/logout').then(response => location.reload());" class="dropdown-item">ออกจากระบบ</a>
                </div>
            </li>
        </ul>
    </nav>
</div>