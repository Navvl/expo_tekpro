<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ optional(App\Models\Setting::first())->site_name ?? 'Default Site Name' }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/logos/' . optional(App\Models\Setting::first())->logo) }}" />

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset ('css/core/libs.min.css') }}" />


    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{ asset ('css/hope-ui.min.css?v=2.0.0') }}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset ('css/custom.min.css?v=2.0.0') }}" />

    <!-- Dark Css -->
    <link rel="stylesheet" href="{{ asset ('css/dark.min.css') }}" />

    <!-- Customizer Css -->
    <link rel="stylesheet" href="{{ asset ('css/customizer.min.css') }}" />

    <!-- RTL Css -->
    <link rel="stylesheet" href="{{ asset ('css/rtl.min.css') }}" />

    <!-- reCAPTCHA script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function validateForm() {
            var backupCaptchaField = document.querySelector('input[name="backup_captcha"]');

            if (navigator.onLine) {
                var response = grecaptcha.getResponse();
                if (response.length === 0) {
                    alert('Please complete the CAPTCHA.');
                    return false;
                }
                backupCaptchaField.removeAttribute('required');
            } else {
                backupCaptchaField.setAttribute('required', 'required');
                var backupCaptcha = backupCaptchaField.value;
                if (backupCaptcha === '') {
                    alert('Please complete the offline CAPTCHA.');
                    return false;
                }
            }

            return true;
        }

        function checkInternet() {
            var backupCaptchaField = document.querySelector('input[name="backup_captcha"]');
            if (!navigator.onLine) {
                document.getElementById('offline-captcha').style.display = 'block';
                document.querySelector('.g-recaptcha').style.display = 'none';
                backupCaptchaField.removeAttribute('disabled'); // Enable the field for offline use
            } else {
                document.getElementById('offline-captcha').style.display = 'none';
                document.querySelector('.g-recaptcha').style.display = 'block';
                backupCaptchaField.setAttribute('disabled', 'disabled'); // Disable the field for online use
            }
        }

        window.onload = checkInternet;
    </script>
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    <!-- loader END -->

    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card">
                                <div class="card-body">
                                    <a href="#" class="navbar-brand d-flex align-items-center mb-3">
                                        <div class="logo-main">
                                            <div class="logo-normal">
                                                <img src="{{ asset('storage/logos/' . optional(App\Models\Setting::first())->logo) }}" alt="Logo" style="max-width: 50px;">
                                            </div>                                            
                                        </div>
                                        <h4 class="logo-title ms-3">Noive</h4>
                                    </a>
                                    <h2 class="mb-2 text-center">Sign In</h2>
                                    <p class="text-center">Login to stay connected.</p>
                                    <form class="pt-3" action="{{ route('aksi_login') }}" method="POST" onsubmit="return validateForm()">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username" aria-describedby="username" placeholder=" ">
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password" name="password" aria-describedby="password" placeholder=" ">
                                                </div>
                                            </div>
                                            <!-- reCAPTCHA widget -->
                                            <div class="g-recaptcha" data-sitekey="6LfO3loqAAAAAA3uUfdmERfJ0o5mtOVF4qV_HwhL"></div>
                                            <!-- Offline CAPTCHA -->
                                            <div id="offline-captcha" style="display:none;">
                                                <p>Please enter the characters shown below:</p>
                                                <img id="captchaimage" src="{{ route('captcha') }}" alt="Captcha">
                                                <input type="text" name="backup_captcha" placeholder="Enter CAPTCHA" required>
                                                <button type="button" onclick="refreshCaptcha()">Refresh CAPTCHA</button>


                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center" style="margin-top:20px;">
                                            <button type="submit" class="btn btn-primary">Sign In</button>
                                        </div>
                                        <p class="mt-3 text-center">
                                            Don’t have an account? <a href="{{route('register')}}" class="text-underline">Click here to sign up.</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="{{ asset ('images/auth/01.png') }}" class="img-fluid gradient-main animated-scaleX" alt="images">
                </div>
            </div>
        </section>
    </div>

    <!-- Library Bundle Script -->
    <script src="{{ asset ('js/core/libs.min.js') }}"></script>

    <!-- External Library Bundle Script -->
    <script src="{{ asset ('js/core/external.min.js') }}"></script>

    <!-- Widgetchart Script -->
    <script src="{{ asset ('js/charts/widgetcharts.js') }}"></script>

    <!-- mapchart Script -->
    <script src="{{ asset ('js/charts/vectore-chart.js') }}"></script>
    <script src="{{ asset ('js/charts/dashboard.js') }}"></script>

    <!-- fslightbox Script -->
    <script src="{{ asset ('js/plugins/fslightbox.js') }}"></script>

    <!-- Settings Script -->
    <script src="{{ asset ('js/plugins/setting.js') }}"></script>

    <!-- Slider-tab Script -->
    <script src="{{ asset ('js/plugins/slider-tabs.js') }}"></script>

    <!-- Form Wizard Script -->
    <script src="{{ asset ('js/plugins/form-wizard.js') }}"></script>

    <!-- AOS Animation Plugin-->

    <!-- App Script -->
    <script src="{{ asset ('js/hope-ui.js') }}" defer></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, "rgba(94, 114, 228, 0.2)");
        gradientStroke1.addColorStop(0.2, "rgba(94, 114, 228, 0.0)");
        gradientStroke1.addColorStop(0, "rgba(94, 114, 228, 0)");
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: [
                    "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#5e72e4",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#fbfbfb",
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#ccc",
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                },
            },
        });

        function refreshCaptcha() {
            document.getElementById('captchaImage').src = '{{ route("captcha") }}?' + Math.random();
        }
    </script>


</body>

</html>