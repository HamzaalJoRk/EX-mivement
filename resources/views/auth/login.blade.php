<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <title>معبر نصيب - تسجيل الدخول</title>

    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors-rtl.min.css">

    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/colors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/components.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css-rtl/pages/authentication.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style-rtl.css">

    <style>
        .logo-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo-container img {
            width: 90px;
            height: auto;
        }

        .show-password {
            position: absolute;
            right: 15px;
            top: 35px;
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="blank-page">

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="auth-wrapper auth-basic px-2">
                    <div class="auth-inner my-2">
                        <div class="card mb-0 shadow-lg rounded-4">
                            <div class="card-body">

                                <div class="logo-container">
                                    <img src="/logo.jpg" alt="شعار معبر نصيب">
                                </div>

                                <h3 class="brand-text text-center text-primary mb-1">
                                    نظام تسجيل المسافرين
                                </h3>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-2">
                                        <x-input-label for="email" value="البريد الإلكتروني" />
                                        <x-text-input id="email" class="form-control" type="email" name="email"
                                            :value="old('email')" required autofocus autocomplete="username" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <div class="mb-2 position-relative">
                                        <x-input-label for="password" value="كلمة المرور" />
                                        <x-text-input id="password" class="form-control" type="password" name="password"
                                            required autocomplete="current-password" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <div class="flex items-center justify-center">
                                        <button type="submit" style="width: 80%" class="btn btn-primary rounded-pill">
                                            تسجيل الدخول
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passField = document.getElementById("password");
            passField.type = passField.type === "password" ? "text" : "password";
        }
    </script>

</body>

</html>