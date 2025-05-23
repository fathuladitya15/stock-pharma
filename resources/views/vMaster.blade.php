<!DOCTYPE html>
<html lang="en" data-layout-mode="light_mode">


<!-- Mirrored from dreamspos.dreamstechnologies.com/html/template/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Apr 2025 02:57:12 GMT -->
<head>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Dreams POS is a powerful Bootstrap based Inventory Management Admin Template designed for businesses, offering seamless invoicing, project tracking, and estimates.">
	<meta name="keywords" content="inventory management, admin dashboard, bootstrap template, invoicing, estimates, business management, responsive admin, POS system">
	<meta name="author" content="Dreams Technologies">
	<meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>StockPharma - @yield('title')</title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('image/logo/StockPharma-Logo.png') }}">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/logo/StockPharma-Logo.png') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/feather.css') }}">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

	<!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">

	<!-- animation CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">

	<!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.css') }}">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

	<!-- Color Picker Css -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/%40simonwep/pickr/themes/nano.min.css') }}">

	<!-- Main CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .custom-swal-size {
            font-size: 14px !important;
          }
    </style>

    @stack('css')

</head>

<body>
	<div id="global-loader">
		<div class="whirly-loader"> </div>
	</div>
	<!-- Main Wrapper -->
	<div class="main-wrapper">
        @include('partials.header')

        @include('partials.sidebar')

		<div class="page-wrapper">
			<div class="content">

                @yield('content')

			</div>
			<div class="copyright-footer d-flex align-items-center justify-content-between border-top bg-white gap-3 flex-wrap">
				<p class="fs-13 text-gray-9 mb-0">2025 &copy; StockPharma. All Right Reserved</p>
				{{-- <p>Designed & Developed By <a href="javascript:void(0);" class="link-primary">MFA</a></p> --}}
			</div>
		</div>

	</div>
	<!-- /Main Wrapper -->

	<!-- Add Stock -->
	<div class="modal fade" id="add-stock">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<div class="page-title">
						<h4>Add Stock</h4>
					</div>
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="https://dreamspos.dreamstechnologies.com/html/template/index.html">
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Warehouse <span class="text-danger ms-1">*</span></label>
									<select class="select">
										<option>Select</option>
										<option>Lobar Handy</option>
										<option>Quaint Warehouse</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Store <span class="text-danger ms-1">*</span></label>
									<select class="select">
										<option>Select</option>
										<option>Selosy</option>
										<option>Logerro</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Responsible Person <span class="text-danger ms-1">*</span></label>
									<select class="select">
										<option>Select</option>
										<option>Steven</option>
										<option>Gravely</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="search-form mb-0">
									<label class="form-label">Product <span class="text-danger ms-1">*</span></label>
									<input type="text" class="form-control" placeholder="Select Product">
									<i data-feather="search" class="feather-search"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-md btn-dark me-2" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-md btn-primary">Add Stock</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- /Add Stock -->

	<!-- jQuery -->
    {{-- <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script> --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('assets/js/feather.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <!-- Datatable JS -->
    {{-- <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script> --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <!-- Summernote JS -->
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <!-- Select2 JS -->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <!-- Datetimepicker JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}" type="ebd83bc71cf0988188c248e1-text/javascript"></script>

    <!-- Bootstrap Tagsinput JS -->
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <!-- Color Picker JS -->
    <script src="{{ asset('assets/plugins/%40simonwep/pickr/pickr.es5.min.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/theme-colorpicker.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>
    <script src="{{ asset('assets/js/script.js') }}" type="4978796092b801b075e66d4e-text/javascript"></script>

    <script src="{{ asset('scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}" data-cf-settings="4978796092b801b075e66d4e-|49" defer></script>
    {{-- <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"93734b0249e68b16","version":"2025.4.0-1-g37f21b1","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}' crossorigin="anonymous"></script> --}}
    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    </script>
    <script>
        var defaultDatatableSettings = {
            bFilter: true,
            sDom: 'fBtlpi',
            ordering: true,
            language: {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search",
                sLengthMenu: 'Row Per Page _MENU_ Entries',
                info: "_START_ - _END_ of _TOTAL_ items",
                paginate: {
                    next: ' <i class=" fa fa-angle-right"></i>',
                    previous: '<i class="fa fa-angle-left"></i>'
                },
            },
            initComplete: function(settings, json) {
                $('.dataTables_filter').appendTo('#tableSearch');
                $('.dataTables_filter').appendTo('.search-input');
            },
        };
    </script>
    @stack('js')

    @if (session('swal_status'))
    <script>
        Swal.fire({
            icon: '{{ session('swal_status') }}', // success, error, warning, etc
            title: '{{ session('title') }}',
            text: '{{ session('text') }}',
            timer: timer,
            showConfirmButton: false,
            timerProgressBar: true,
            width: '300px', // default-nya 500px
            customClass: {
              popup: 'custom-swal-size'
            }
        });
    </script>
    @endif

    <script>
        function showAlert(message, title , type = "info", timer = 3000) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,   // 'success', 'error', 'warning', 'info', 'question'
                timer: timer,
                showConfirmButton: false,
                timerProgressBar: true,
                width: '300px', // default-nya 500px
                customClass: {
                  popup: 'custom-swal-size'
                }
            });
        }

        function showLoadingAlert() {
            Swal.fire({
                title: "Please wait ... !",
                html: "Is processing ... ",
                allowOutsideClick: false,
                allowEscapeKey: false,
                width: '300px', // default-nya 500px
                customClass: {
                  popup: 'custom-swal-size'
                },
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        }

    </script>

</body>


<!-- Mirrored from dreamspos.dreamstechnologies.com/html/template/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Apr 2025 02:58:37 GMT -->
</html>
