<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo active">
        <a href="index.html" class="logo logo-normal">
            <img src="{{ asset('image/logo/StockPharma-Logo-Horizontal.png') }}" alt="Img">
        </a>
        <a href="index.html" class="logo logo-white">
            <img src="{{ asset('/') }}assets/img/logo-white.svg" alt="Img">
        </a>
        <a href="index.html" class="logo-small">
            <img src="{{ asset('/') }}assets/img/logo-small.png" alt="Img">
        </a>
    </div>
    <!-- /Logo -->

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="{{ menuActive(['home','profile']) }}">
                            <a href="{{ route('home') }}" class="{{ menuActive(['home','profile']) }}">
                                <i class="ti ti-layout-grid fs-16 me-2"></i><span>Beranda</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @if(in_array(auth()->user()->role, ['admin', 'staff_gudang']))
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Master</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ menuSubDrop(['category.product','product']) }}"><i data-feather="box"></i><span>Produk</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li class="{{ menuActive(['category.product']) }}">
                                    <a href="{{ route('category.product') }}" class="{{ menuActive(['category.product']) }}">
                                        <span>Kategori Produk</span>
                                    </a>
                                </li>
                                <li class="{{ menuActive(['product']) }}">
                                    <a href="{{ route('product') }}" class="{{ menuActive(['product']) }}"><span>Data Produk</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ menuActive(['suppliers']) }}">
                            <a href="{{ route('suppliers') }}" class="{{ menuActive(['suppliers']) }}"><i class="ti ti-archive fs-16 me-2"></i><span>Suplliers</span></a>
                        </li>
                        @if(in_array(auth()->user()->role, ['admin']))

                         <li class="{{ menuActive(['users']) }}">
                            <a href="{{ route('users') }}" class="{{ menuActive(['users']) }}"><i class="ti ti-user-edit fs-16 me-2"></i><span>Pengguna</span></a>
                        </li>
                        @endif
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Penjualan dan Pembelian</h6>
                    <ul>
                        @if(in_array(auth()->user()->role, ['admin']))
                        <li class="{{ menuActive(['sales']) }}">
                            <a href="{{ route('sales') }}"><i class="ti ti-shopping-bag fs-16 me-2"></i><span>Penjualan</span></a>
                        </li>
                        @endif
                         <li class="{{ menuActive(['purchase.order']) }}">
                            <a href="{{ route('purchase.order') }}" class="{{ menuActive(['purchase.order']) }}">
                                <i class="ti ti-shopping-bag fs-16 me-2"></i><span>Pembelian</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->role == 'manager')
                 <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        <li class="{{ menuActive(['sales.report']) }}">
                            <a href="{{ route('sales.report') }}" class="{{ menuActive(['sales.report']) }}">
                                <i class="ti ti-chart-bar fs-16 me-2"></i>
                                <span>Laporan Penjualan</span>
                            </a>
                        </li>
                        <li class="{{ menuActive('purchase.order.report') }}">
                            <a href="{{ route('purchase.order.report') }}" class="{{ menuActive('purchase.order.report') }}">
                                <i class="ti ti-chart-pie-2 fs-16 me-2"></i>
                                <span>Laporan Pembelian</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @endif
                @if(in_array(auth()->user()->role, ['admin']))
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Pemantauan </h6>
                    <ul>
                        <li class="{{ menuActive(['poq.index']) }}">
                            <a href="{{ route('poq.index') }}" class="{{ menuActive(['poq.index']) }}">
                                <i class="ti ti-stairs-up fs-16 me-2"></i><span>POQ</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Contoh Menu Khusus Role Suppliers -->
                @if(in_array(auth()->user()->role,['supplier']))
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Are Supplier</h6>
                    <ul>
                        <li class="{{ menuActive(['purchase.order']) }}">
                            <a href="{{ route('purchase.order') }}" class="{{ menuActive(['purchase.order']) }}">
                                <i class="ti ti-package fs-16 me-2"></i><span>Pesanan Saya</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
