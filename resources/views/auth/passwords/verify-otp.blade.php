<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SMAN 3 SUMENEP - Verifikasi OTP</title>
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
    .bg-verifyotp-image {
      background: url("{{ asset('admin_assets/img/img 1.svg') }}");
      background-position: center;
      background-size: cover;
    }
    .toggle-password-btn {
      background: none;
      border: none;
      position: absolute;
      right: 10px;
      top: 70%;
      transform: translateY(-50%);
      cursor: pointer;
    }
    .form-group {
      position: relative;
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
              <div class="col-lg-6 d-none d-lg-block bg-verifyotp-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Verifikasi OTP</h1>
                  </div>
                  <form action="{{ route('password.verify') }}" method="POST" class="user">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
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
                      <label for="code">Masukkan OTP</label>
                      <input type="text" name="code" id="code" class="form-control form-control-user" placeholder="Masukkan OTP" required>
                    </div>
                    <div class="form-group">
                      <label for="password">Password Baru</label>
                      <input type="password" name="password" id="password" class="form-control form-control-user" placeholder="Password Baru" required>
                      <button type="button" id="togglePassword" class="toggle-password-btn">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                        <i class="fas fa-eye-slash" id="eyeOffIcon" style="display: none;"></i>
                      </button>
                    </div>
                    <div class="form-group">
                      <label for="password_confirmation">Konfirmasi Password</label>
                      <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-user" placeholder="Konfirmasi Password" required>
                      <button type="button" id="togglePasswordConfirmation" class="toggle-password-btn">
                        <i class="fas fa-eye" id="eyeIcon1"></i>
                        <i class="fas fa-eye-slash" id="eyeOffIcon1" style="display: none;"></i>
                      </button>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Submit</button>
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
  <script>
    // Toggle visibility for password field
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordField = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');
      const eyeOffIcon = document.getElementById('eyeOffIcon');
      
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.style.display = 'none';
        eyeOffIcon.style.display = 'inline';
      } else {
        passwordField.type = 'password';
        eyeIcon.style.display = 'inline';
        eyeOffIcon.style.display = 'none';
      }
    });

    // Toggle visibility for password confirmation field
    document.getElementById('togglePasswordConfirmation').addEventListener('click', function () {
      const passwordField = document.getElementById('password_confirmation');
      const eyeIcon = document.getElementById('eyeIcon1');
      const eyeOffIcon = document.getElementById('eyeOffIcon1');
      
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.style.display = 'none';
        eyeOffIcon.style.display = 'inline';
      } else {
        passwordField.type = 'password';
        eyeIcon.style.display = 'inline';
        eyeOffIcon.style.display = 'none';
      }
    });
  </script>
</body>
</html>
