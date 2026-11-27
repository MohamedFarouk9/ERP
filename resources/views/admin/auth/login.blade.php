<!doctype html>
@php
    $currentLocale = app()->getLocale();
    $isRtl = $currentLocale === 'ar';
@endphp
<html lang="{{ $currentLocale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('admin/assets/images/favicon-32x32.png') }}" type="image/png" />
    @if($isRtl)
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif
    <link href="{{ asset('admin/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('admin/assets/js/pace.min.js') }}"></script>
    @if($isRtl)
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    @endif
    <link href="{{ asset('admin/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/icons.css') }}" rel="stylesheet">
    @if($isRtl)
        <link href="{{ asset('admin/assets/css/rtl.css') }}" rel="stylesheet">
    @endif
    <title>{{ __('Enterprise ERP') }} - {{ __('Sign In') }}</title>
</head>

<body class="bg-login">
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="d-flex justify-content-end mb-2">
                                        <a href="{{ route('locale.switch', $isRtl ? 'en' : 'ar') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bx bx-globe me-1 align-middle"></i>{{ $isRtl ? 'EN' : 'العربية' }}
                                        </a>
                                    </div>
                                    <div class="text-center mb-3">
                                        <h3 class="mb-1">{{ __('Enterprise ERP') }}</h3>
                                        <p class="text-muted mb-0">{{ __('Sign in to your organization workspace') }}</p>
                                    </div>
                                    <hr />
                                    <div class="form-body">
                                        <form class="row g-3" method="POST" action="{{ route('admin.login.submit') }}">
                                            @csrf
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">{{ __('Email Address') }}</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="inputEmailAddress" name="email" placeholder="admin@acme.test"
                                                    required value="{{ old('email') }}">
                                                @error('email')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">{{ __('Password') }}</label>
                                                <div class="input-group" id="show_hide_password">
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        id="inputChoosePassword" name="password"
                                                        placeholder="{{ __('Enter Password') }}" required>
                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                </div>
                                                @error('password')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" name="remember" value="1">
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">{{ __('Remember Me') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i> {{ __('Sign In') }}</button>
                                                </div>
                                            </div>
                                            <div class="col-12 text-center">
                                                <small class="text-muted">{{ __('Demo') }}: <code>admin@acme.test</code> / <code>Password123!</code></small>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide").removeClass("bx-show");
                } else {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide").addClass("bx-show");
                }
            });
        });
    </script>
</body>

</html>
