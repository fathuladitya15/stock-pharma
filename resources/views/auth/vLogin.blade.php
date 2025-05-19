<!DOCTYPE html>
<html lang="en">

<head>

		<!-- Meta Tags -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Dreams POS is a powerful Bootstrap based Inventory Management Admin Template designed for businesses, offering seamless invoicing, project tracking, and estimates.">
		<meta name="keywords" content="inventory management, admin dashboard, bootstrap template, invoicing, estimates, business management, responsive admin, POS system">
		<meta name="author" content="Dreams Technologies">
		<meta name="robots" content="index, follow">
		<title>Stock Pharma - Login</title>

		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('image/logo/StockPharma-Logo.png') }}">

		<!-- Apple Touch Icon -->
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/logo/StockPharma-Logo.png') }}">

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('/') }}assets/css/bootstrap.min.css">

        <!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{ asset('/') }}assets/plugins/fontawesome/css/fontawesome.min.css">
		<link rel="stylesheet" href="{{ asset('/') }}assets/plugins/fontawesome/css/all.min.css">

         <!-- Tabler Icon CSS -->
	    <link rel="stylesheet" href="{{ asset('/') }}assets/plugins/tabler-icons/tabler-icons.css">

	    <!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('/') }}assets/css/style.css">

    </head>
    <body class="account-page bg-white">

        <div id="global-loader" >
			<div class="whirly-loader"> </div>
		</div>

		<!-- Main Wrapper -->
        <div class="main-wrapper">
			<div class="account-content">
				<div class="login-wrapper login-new">
                    <div class="row w-100">
                        <div class="col-lg-5 mx-auto">
                            <div class="login-content user-login">
                                <div class="login-logo">
                                    <img src="{{ asset('image/logo/StockPharma-Logo.png') }}" alt="img">
                                    <a href="#" class="login-logo logo-white">
                                        <img src="{{ asset('/') }}assets/img/logo-white.svg"  alt="Img">
                                    </a>
                                </div>
                                <form action="{{ route('login') }}" id="formLogin">
                                    @csrf
                                    <div class="card">
                                        <div class="card-body p-5">
                                            <div class="login-userheading">
                                                <h3>Log In</h3>
                                                <h4>Access the StockPharma panel using your email and passcode.</h4>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email <span class="text-danger"> *</span></label>
                                                <div class="input-group">
                                                    <input type="text" value="" name="email" class="form-control border-end-0" required>
                                                    <span class="input-group-text border-start-0">
                                                        <i class="ti ti-mail"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Password <span class="text-danger"> *</span></label>
                                                <div class="pass-group">
                                                    <input type="password" name="password" class="pass-input form-control" required>
                                                    <span class="ti toggle-password ti-eye-off text-gray-9"></span>
                                                </div>
                                            </div>
                                            <div class="form-login">
                                                <button type="submit" class="btn btn-primary w-100">Sign In</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                                <p>Copyright &copy; 2025 StockPharma</p>

                            </div>
                        </div>
                    </div>
                </div>
			</div>
        </div>
		<!-- /Main Wrapper -->

		<!-- jQuery -->
        <script src="{{ asset('/') }}assets/js/jquery-3.7.1.min.js" type="a41d4c6a3a32d91c351186a2-text/javascript"></script>

         <!-- Feather Icon JS -->
		<script src="{{ asset('/') }}assets/js/feather.min.js" type="a41d4c6a3a32d91c351186a2-text/javascript"></script>

		<!-- Bootstrap Core JS -->
        <script src="{{ asset('/') }}assets/js/bootstrap.bundle.min.js" type="a41d4c6a3a32d91c351186a2-text/javascript"></script>

		<!-- Custom JS -->
        <script src="{{ asset('/') }}assets/js/script.js" type="a41d4c6a3a32d91c351186a2-text/javascript"></script>

    <script src="{{ asset('/') }}scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="a41d4c6a3a32d91c351186a2-|49" defer></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"93734b6da9218b16","version":"2025.4.0-1-g37f21b1","serverTiming":{"name":{"cfExtPri":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}' crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- SWEET ALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var home = "{{ route('home') }}";
        $(document).ready(function () {
            // Submit Login
            $("#formLogin").submit(function(e) {
                e.preventDefault();
                var url     = $(this).attr('action');
                var data    = $(this).serialize();
                $.ajax({
                    url     : url,
                    data    : data,
                    type    : "POST",
                    beforeSend: function() {
                        Swal.fire({
                            title: "Please Wait ... !",
                            html: "trying to login<b></b> ",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });
                    },
                    success : function(response) {
                        let timerInterval;
                        Swal.fire({
                            title: "Logged in",
                            html: "You will be redirected to the home menu ",
                            icon: "success",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                            }).then((result) => {
                            /* Read more about handling dismissals below */
                            if (result.dismiss === Swal.DismissReason.timer) {
                                // console.log("I was closed by the timer");
                                window.location.href = home;
                            }
                        });
                    },
                    error : function(xhr) {
                        var message = xhr.responseJSON.message;
                        Swal.fire({
                            title: "Opps ...",
                            text: message,
                            icon: 'error',   // 'success', 'error', 'warning', 'info', 'question'
                            timer: 3000,
                            showConfirmButton: false,
                            timerProgressBar: true
                        });
                    }
                });

            });
        });
    </script>
</body>

</html>
