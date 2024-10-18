<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SMAN 3 SUMENEP - Register</title>
  <!-- Custom fonts for this template-->
  <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
  <style>
    .background-image {
      background-image: url('{{ asset('admin_assets/img/Untitled.svg') }}');
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
    }
    .border-opacity-90 {
      border-color: rgba(255, 255, 255, 0.9);
    }
    .border-b-2 {
      border-bottom-width: 2px;
    }
    .background-link {
      position: relative;
    }
    .bg-register-image {
      background: url("{{ asset('admin_assets/img/img 1.svg') }}");
      background-position: center;
      background-size: cover;
    }
    #togglePassword{
            background-color: transparent;
            cursor: pointer;
            border: none;
            position: absolute;
            margin-left: 13rem;
            margin-top: -2.5rem;
        }
    #togglePassword1{
            background-color: transparent;
            cursor: pointer;
            border: none;
            position: absolute;
            margin-left: 13rem;
            margin-top: -2.5rem;
        }
  </style>
</head>
<body class="bg-gray-100 text-gray-900 flex flex-col min-h-screen background-image">
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Buat Akun!</h1>
              </div>
              <form action="{{ route('register.save') }}" method="POST" class="user">
                @csrf
                <div class="form-group">
                  <input name="name" type="text" class="form-control form-control-user @error('name')is-invalid @enderror" id="exampleInputName" placeholder="Nama">
                  @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
                <div class="form-group">
                  <input name="email" type="email" class="form-control form-control-user @error('email')is-invalid @enderror" id="exampleInputEmail" placeholder="Alamat Email">
                  @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input name="password" type="password" class="form-control form-control-user @error('password')is-invalid @enderror" id="passreg" placeholder="Password">
                    <button type="button" id="togglePassword">
                        <!-- Eye Icon (Default: Hidden) -->
                        <span id="eyeIcon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                        <!-- Eye Off Icon (Password Visible) -->
                        <span id="eyeOffIcon" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                        </span>
                    </button>
                    @error('password')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                  <div class="col-sm-6">
                    <input name="password_confirmation" type="password" class="form-control form-control-user @error('password_confirmation')is-invalid @enderror" id="repassreg" placeholder="Repeat Password">
                    <button type="button" id="togglePassword1">
                        <!-- Eye Icon (Default: Hidden) -->
                        <span id="eyeIcon1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                        <!-- Eye Off Icon (Password Visible) -->
                        <span id="eyeOffIcon1" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                        </span>
                    </button>
                    @error('password_confirmation')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="{{ route('login') }}">Sudah Punya Akun? Login!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('passreg');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            // Toggle between 'password' and 'text' input types
            if (passwordField.getAttribute('type') === 'password') {
                passwordField.setAttribute('type', 'text');
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'inline'; // Show eye-off (visible)
            } else {
                passwordField.setAttribute('type', 'password');
                eyeIcon.style.display = 'inline'; // Show eye (hidden)
                eyeOffIcon.style.display = 'none';
            }
    });
    document.getElementById('togglePassword1').addEventListener('click', function () {
        const passwordField = document.getElementById('repassreg');
            const eyeIcon = document.getElementById('eyeIcon1');
            const eyeOffIcon = document.getElementById('eyeOffIcon1');

            // Toggle between 'password' and 'text' input types
            if (passwordField.getAttribute('type') === 'password') {
                passwordField.setAttribute('type', 'text');
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'inline'; // Show eye-off (visible)
            } else {
                passwordField.setAttribute('type', 'password');
                eyeIcon.style.display = 'inline'; // Show eye (hidden)
                eyeOffIcon.style.display = 'none';
            }
    });
</script>
</body>
</html>
