<div>
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">ระบบ</div>
                    <a class="nav-link {{Request::is('/') ? 'active' : ''}}" href="/">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>หน้าหลัก
                    </a>
                    @if(Auth::user()->role)
                    <a class="nav-link  {{ Request::is('product') || Request::is('type') || Request::is('unit') ? 'active' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-people-carry"></i></div>
                        ระบบพัสดุ
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ Request::is('product') || Request::is('type') || Request::is('unit') ? 'show' : '' }}" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('product') ? 'active' : '' }}" href="/product">ข้อมูลพัสดุ</a>
                            <a class="nav-link {{ Request::is('type') ? 'active' : '' }}" href="/type">หมวดหมู่พัสดุ</a>
                            <a class="nav-link {{ Request::is('unit') ? 'active' : '' }}" href="/unit">หน่วยนับพัสดุ</a>
                        </nav>
                    </div>
                    <a class="nav-link {{ Request::is('stock') || Request::is('sale') || Request::is('import') || Request::is('import/data') || Request::is('product/return') ? 'active' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-box-open"></i></div>
                        ระบบคลังพัสดุ
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ Request::is('stock') || Request::is('sale') || Request::is('import') || Request::is('import/data') || Request::is('product/return') ? 'show' : '' }}" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">                 
                            <a class="nav-link {{ Request::is('stock') ? 'active' : '' }}" href="{{route('stock.index')}}">
                                คลังพัสดุ
                            </a>                      
                            <a class="nav-link {{ Request::is('sale') || Request::is('product/return') ? 'active' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                เบิก-คืน พัสดุ
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse {{ Request::is('sale')  || Request::is('product/return') ? 'show' : '' }}" id="pagesCollapseAuth" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link {{ Request::is('sale') ? 'active' : '' }}" href="{{route('sale.index')}}">เบิกพัสดุ</a>
                                    <a class="nav-link {{ Request::is('product/return') ? 'active' : '' }}" href="/product/return">คืนพัสดุ</a>
                                </nav>
                            </div>                        
                            <a class="nav-link {{ Request::is('import') || Request::is('import/data') ? 'active' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#ImportCollapse" aria-expanded="false" aria-controls="ImportCollapse">
                                นำเข้าพัสดุ
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse {{ Request::is('import') || Request::is('import/data') ? 'show' : '' }}" id="ImportCollapse" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link {{ Request::is('import') ? 'active' : '' }}" href="{{route('import.index')}}">นำเข้าพัสดุ</a>
                                    <a class="nav-link {{ Request::is('import/data') ? 'active' : '' }} " href="{{route('import.history')}}">ข้อมูลการนำเข้า</a>
                                </nav>
                            </div>                     
                        </nav>
                    </div>
                    <div class="sb-sidenav-menu-heading">จัดการข้อมูล</div>
                    <a class="nav-link {{ Request::is('report/list') || Request::is('report/return') ? 'active' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#reportCollapse" aria-expanded="false" aria-controls="reportCollapse">
                        <div class="sb-nav-link-icon"><i class="fas fa-print"></i></div>
                        รายงานพัสดุ
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ Request::is('report/list') || Request::is('report/return') ? 'show' : '' }}" id="reportCollapse" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('report/list') ? 'active' : '' }}" href="/report/list">ใบเบิกพัสดุ</a>
                            <a class="nav-link {{ Request::is('report/return') ? 'active' : '' }}" href="/report/return">คืนพัสดุ</a>
                        </nav>
                    </div>                
                    <a class="nav-link {{ Request::is('department') || Request::is('user') ? 'active' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#mydataCollapse" aria-expanded="false" aria-controls="mydataCollapse">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        ข้อมูลพนักงาน
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ Request::is('department') || Request::is('user') ? 'show':''}}" id="mydataCollapse" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('user') ? 'active' : '' }}" href="/user">ข้อมูลพนักงาน</a>
                            <a class="nav-link {{ Request::is('department') ? 'active' : '' }}" href="/department">ข้อมูลหน่วยงาน</a>
                        </nav>
                    </div>
                    <a class="nav-link {{ Request::is('setting') || Request::is('setting/pass') ? 'active' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#resumeCollapse" aria-expanded="false" aria-controls="resumeCollapse">
                        <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                        ข้อมูลส่วนตัว
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ Request::is('setting') || Request::is('setting/pass') ? 'show' : '' }}" id="resumeCollapse" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link {{ Request::is('setting') ? 'active' : '' }}" href="/setting">ข้อมูลส่วนตัว</a>
                            <a class="nav-link {{ Request::is('setting/pass') ? 'active' : '' }}" href="/setting/pass">เปลี่ยนรหัสผ่าน</a>
                        </nav>
                    </div>
                    @else
                    <a href="/sale" class="nav-link {{ Request::is('sale') ? 'active' : '' }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-balance-scale"></i></div>เบิกพัสดุ
                    </a>
                    <a href="/report/list" class="nav-link {{ Request::is('report/list') ? 'active' : '' }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-print"></i></div>รายงานการเบิก
                    </a>
                    <div class="sb-sidenav-menu-heading">จัดการข้อมูล</div>
                    <a href="/setting" class="nav-link {{ Request::is('setting') ? 'active' : '' }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>ข้อมูลส่วนตัว
                    </a>
                    <a href="/setting/pass" class="nav-link {{ Request::is('setting/pass') ? 'active' : '' }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-key"></i></div>รหัสผ่าน
                    </a>
                    @endif

                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">ลงชื่อใช้งาน โดย:</div>
                {{Auth::user()->name}}
            </div>
        </nav>
    </div>
</div>