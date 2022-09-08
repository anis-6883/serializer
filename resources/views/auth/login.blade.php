<!DOCTYPE html>
<html lang="en-US">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ config('app.name', 'Laravel') }} | Login</title>
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('public/backend/css/vertical-layout-light/style.css') }}">
  <!-- favicon -->
  <link rel="shortcut icon" href="{{ asset('public/default/h&s.jpg') }}" />
  <!-- fontawsome -->
  <script src="https://kit.fontawesome.com/fbcd216f09.js" crossorigin="anonymous"></script>
  <!-- bootstrap 5 -->
  <link rel="stylesheet" href="{{ asset('public/backend/css/bootstrap5/bootstrap.min.css') }}">
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo d-flex justify-content-center" data-tilt>
                <img src="{{ asset('public/default/h&s.jpg') }}" alt="logo" style="width: 180px">
              </div>
              <h3 style="font-weight: 700">Login</h3>

              <form action="{{ route('login') }}" method="POST" class="pt-3">
                @csrf
                @method('POST')

                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                     </div>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror rounded-sm" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email...">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->
                
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"> <i class="fa fa-lock"></i></i> </span>
                     </div>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror rounded-sm" name="password" required autocomplete="current-password" placeholder="Password...">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> <!-- form-group// -->
        
                <div class="mt-3">
                  <button type="sumbit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- vanilla-tilt -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.7.2/vanilla-tilt.min.js" integrity="sha512-K9tDZvc8nQXR1DMuT97sct9f40dilGp97vx7EXjswJA+/mKqJZ8vcZLifZDP+9t08osMLuiIjd4jZ0SM011Q+w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>
