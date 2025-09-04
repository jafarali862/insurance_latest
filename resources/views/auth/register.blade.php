<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Insurance - Register</title>
    <link rel="shortcut icon" href="{{ asset('dashboard/img/icon.png') }}" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-dyZ88n1T6h+gBvnxE1VlzDq+W8sELpAo0P5PLf4KJIp4jOSAmhpN6wX+Z1GDZCBo2uiG9q4CPrlGHBXHi3pP2g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- icheck bootstrap -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <link rel="shortcut icon" href="{{ asset('dashboard/img/icon.jpg') }}" type="image/x-icon">
</head>

<body class="hold-transition register-page" style="background-color:#c9c9c9;">

    <div class="register-box">
       <div class="register-logo">
    <a href="#">
        <span style="color: #007bff; font-weight: bold;">Insurance</span>
        <span style="color: #28a745;font-weight:bold;">Register</span>
    </a>
</div>


        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Register a new membership</p>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Name"
                               value="{{ old('name') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif

                    <div class="input-group mb-3">
                        <input type="number" name="phone" class="form-control" placeholder="Phone Number"
                               value="{{ old('phone') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-phone"></span></div>
                        </div>
                    </div>
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                               value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif

                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Repeat Password">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif

                    <div class="row">
                        <div class="col-8">
                            <!-- <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">I agree to the <a href="#">terms</a></label>
                            </div> -->
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>

                <a href="{{ route('login.form') }}" class="text-center">I already have a membership</a>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
        integrity="sha512-894YeM4G+MbJQp+swHbZ4CkP2J1F1a7nJpO3S2wzVnz8zD59+M3eY5M7oj8zW3Ql7F6dqK4Z1hJ1GfCJo0xRkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc5lFfKxT8P7k5lrW7R1EjrJENz3U2hyXKXh5j9gD"
        crossorigin="anonymous"></script>

    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
