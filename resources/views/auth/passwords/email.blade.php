<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SMAN 3 SUMENEP - Kirim OTP</title>
  <!-- Custom fonts for this template-->
  <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
  <style>
    .background-image {
      background-image: url('{{ asset('/admin_assets/img/Untitled.svg') }}');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
    }
    .border-opacity-90 {
      border-color: rgba(255, 255, 255, 0.9);
    }
    .bg-sendotp-image {
      background: url("{{ asset('admin_assets/img/img 1.svg') }}");
      background-position: center;
      background-size: cover;
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-900 flex flex-col min-h-screen background-image">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-sendotp-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Masukkan Email atau Nomor Telepon</h1>
                  </div>
                  <form action="{{ route('password.sendOtp') }}" method="POST" class="user">
                    @csrf
                    @if ($errors->any())
                      <div class="alert alert-danger">
                        <ul>
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif
                    <div class="form-group">
                      <input type="text" name="email_or_phone" id="email_or_phone" class="form-control form-control-user" placeholder="Masukkan Email atau Nomor Telepon" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Kirim OTP</button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="{{ route('login') }}">Kembali ke Login</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>
</body>
</html>
