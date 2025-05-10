<!-- Header -->
		<div class="header">
			<div class="main-header">
				<!-- Logo -->
				<div class="header-left active">
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
				<a id="mobile_btn" class="mobile_btn" href="#sidebar">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</a>

				<!-- Header Menu -->
				<ul class="nav user-menu">

					<!-- Search -->
					<li class="nav-item nav-searchinputs">

					</li>
					<!-- /Search -->

					<!-- Select Store -->
					<li class="nav-item dropdown has-arrow main-drop select-store-dropdown">

					</li>
					<!-- /Select Store -->

					<li class="nav-item dropdown link-nav">

					</li>

					<li class="nav-item pos-nav">

					</li>

					<!-- Flag -->
					<li class="nav-item dropdown has-arrow flag-nav nav-item-box">

					</li>
					<!-- /Flag -->

					<li class="nav-item nav-item-box">
						<a href="javascript:void(0);" id="btnFullscreen">
							<i class="ti ti-maximize"></i>
						</a>
					</li>



					<li class="nav-item nav-item-box">
						<a href="general-settings.html"><i class="ti ti-settings"></i></a>
					</li>
					<li class="nav-item dropdown has-arrow main-drop profile-nav">
						<a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
							<span class="user-info p-0">
								<span class="user-letter">
									<img src="{{ asset('/') }}assets/img/profiles/avator1.jpg" alt="Img" class="img-fluid">
								</span>
							</span>
						</a>
						<div class="dropdown-menu menu-drop-user">
							<div class="profileset d-flex align-items-center">
								<span class="user-img me-2">
									<img src="{{ asset('/') }}assets/img/profiles/avator1.jpg" alt="Img">
								</span>
								<div>
									<h6 class="fw-medium">{{ $name }}</h6>
									<p>{{ Str::title($roles) }}</p>
								</div>
							</div>
							<a class="dropdown-item" href="{{ route('profile') }}"><i class="ti ti-user-circle me-2"></i>MyProfile</a>
							<a class="dropdown-item" href="#"><i class="ti ti-file-text me-2"></i>Reports</a>
							<a class="dropdown-item" href="#"><i class="ti ti-settings-2 me-2"></i>Settings</a>
							<hr class="my-2">
							<a class="dropdown-item logout pb-0" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="ti ti-logout me-2"></i>Logout
                            </a>
						</div>
					</li>
				</ul>
				<!-- /Header Menu -->

				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu">
					<a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
						aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="#">My Profile</a>
						<a class="dropdown-item" href="#">Settings</a>
						<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Logout</a>
					</div>
				</div>
				<!-- /Mobile Menu -->
			</div>
		</div>
		<!-- /Header -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
