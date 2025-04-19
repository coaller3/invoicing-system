<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="{{ asset('assets/img/AdminLTELogo.png') }}">

    <title>Invoicing System</title>

	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{asset('assets/css/adminlte.min.css')}}">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<!-- Sweetalert2 Css -->
	<link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2/sweetalert2.css')}}" />

</head>
<body class="hold-transition login-page">
	<div class="login-box">
		<div class="card card-outline card-primary">

			<div class="card-header text-center">
				<h2>Invoicing System</h2>
			</div>

			<div class="card-body">

				<p class="login-box-msg">Sign in to start your session</p>

				<form id="form1" method="post" action="login">
				    @csrf
					<div class="input-group mb-3">

						<input type="email" class="form-control" name="email" placeholder="Email">

						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
					</div>

					<div class="input-group mb-3">
						<input type="password" class="form-control" name="password" placeholder="Password">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn btn-success btn-block">Sign In</button>
						</div>
					</div>
				</form>

                <div class="row">
                    <div class="col-12 mb-1 mt-1">
                        <a href="forgot-password.html" class="text-center">Reset Password</a>
                    </div>
                    <div class="col-12">
                        <a href="register.html" class="text-center">Registration</a>
                    </div>
                </div>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
	<!-- Bootstrap 4 -->
	<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
	<!-- AdminLTE App -->
	<script src="{{asset('assets/js/adminlte.min.js')}}"></script>
	<!-- SweetAlert2 Plugin Js -->
	<script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

	@if(session('status')=="failed")
        <script>
            Swal.fire(
                'Error!',
                "Invalid email or password!",
                'error'
            )
        </script>
	@endif
</body>
</html>
