<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30 in">
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.home')}}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>
                <li class="sidebar-item selected"> <a class="sidebar-link has-arrow waves-effect waves-dark active" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-folder-plus"></i><span class="hide-menu">GBG</span></a>
                    <ul aria-expanded="false" class="collapse first-level in">
                        <li class="sidebar-item"><a href="{{route('admin.gbg.cms.list')}}" class="sidebar-link"><i class="mdi mdi-note-outline"></i><span class="hide-menu">CMS Management</span></a></li>  
                        <li class="sidebar-item"><a href="{{route('admin.gbg.category.list')}}" class="sidebar-link"><i class="mdi mdi-note-outline"></i><span class="hide-menu">Category Management</span></a></li>
                        <li class="sidebar-item"><a href="{{route('admin.gbg.falseurl.list')}}" class="sidebar-link"><i class="mdi mdi-note-outline"></i><span class="hide-menu">FalseUrl Management</span></a></li>
                        <li class="sidebar-item"><a href="{{route('admin.gbg.product.list')}}" class="sidebar-link"><i class="mdi mdi-note-outline"></i><span class="hide-menu">Product Management</span></a></li>
                        <li class="sidebar-item"><a href="{{route('admin.gbg.coupon.list')}}" class="sidebar-link"><i class="mdi mdi-note-outline"></i><span class="hide-menu">Coupon Management</span></a></li>
                    </ul>
                </li>

                
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>