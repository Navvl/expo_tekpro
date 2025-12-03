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

    <!-- reCAPTCHA script - DIPERBAIKI dengan callback -->
    <script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
    
    <!-- TAMBAHAN: Style untuk offline captcha -->
    <style>
        #offline-captcha {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        #offline-captcha p {
            margin-bottom: 10px;
            font-weight: 500;
        }
        #captchaimage {
            display: block;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 4px;
            max-width: 100%;
        }
        #offline-captcha input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #offline-captcha button {
            padding: 6px 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #offline-captcha button:hover {
            background: #0056b3;
        }
    </style>
    
    <script>
        // TAMBAHAN: Variable untuk tracking reCAPTCHA
        var recaptchaReady = false;
        var recaptchaWidgetId = null;
        
        // TAMBAHAN: Callback ketika reCAPTCHA loaded
        function onRecaptchaLoad() {
            try {
                var container = document.querySelector('.g-recaptcha');
                if (container) {
                    recaptchaWidgetId = grecaptcha.render(container, {
                        'sitekey': '6LfO3loqAAAAAA3uUfdmERfJ0o5mtOVF4qV_HwhL'
                    });
                    recaptchaReady = true;
                    console.log('reCAPTCHA loaded successfully');
                }
            } catch(e) {
                console.error('reCAPTCHA load error:', e);
                recaptchaReady = false;
            }
            checkInternet();
        }
        
        function validateForm() {
            var backupCaptchaField = document.querySelector('input[name="backup_captcha"]');

            // DIPERBAIKI: Cek recaptchaReady juga
            if (navigator.onLine && recaptchaReady) {
                try {
                    var response = grecaptcha.getResponse(recaptchaWidgetId);
                    if (response.length === 0) {
                        alert('Please complete the CAPTCHA.');
                        return false;
                    }
                    backupCaptchaField.removeAttribute('required');
                    backupCaptchaField.value = ''; // TAMBAHAN: Clear offline captcha
                } catch(e) {
                    console.error('reCAPTCHA validation error:', e);
                    return false;
                }
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
            var offlineDiv = document.getElementById('offline-captcha');
            var recaptchaDiv = document.querySelector('.g-recaptcha');
            
            // TAMBAHAN: Null check
            if (!backupCaptchaField || !offlineDiv || !recaptchaDiv) {
                console.log('Elements not ready, retrying...');
                setTimeout(checkInternet, 500);
                return;
            }
            
            // DIPERBAIKI: Cek recaptchaReady juga
            if (!navigator.onLine || !recaptchaReady) {
                offlineDiv.style.display = 'block';
                recaptchaDiv.style.display = 'none';
                backupCaptchaField.removeAttribute('disabled');
                backupCaptchaField.setAttribute('required', 'required');
                console.log('Using offline CAPTCHA');
                // TAMBAHAN: Load captcha image
                refreshCaptcha();
            } else {
                offlineDiv.style.display = 'none';
                recaptchaDiv.style.display = 'block';
                backupCaptchaField.setAttribute('disabled', 'disabled');
                backupCaptchaField.removeAttribute('required');
                console.log('Using online reCAPTCHA');
            }
        }

        window.onload = checkInternet;
        
        // TAMBAHAN: Monitor koneksi
        window.addEventListener('online', checkInternet);
        window.addEventListener('offline', checkInternet);
    </script>
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">

    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>

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
                                    

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            @foreach ($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                    

                                    @if (session('message'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('message') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                    
                                    <form class="pt-3" action="{{ route('aksi_login') }}" method="POST" onsubmit="return validateForm()">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="username" class="form-label">Username</label>

                                                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" aria-describedby="username" placeholder=" " required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    
                                                    <input type="password" class="form-control" id="password" name="password" aria-describedby="password" placeholder=" " required>
                                                </div>
                                            </div>
                                            
                                            <div class="g-recaptcha" data-sitekey="6LfO3loqAAAAAA3uUfdmERfJ0o5mtOVF4qV_HwhL"></div>
                                            
                                            <div id="offline-captcha" style="display:none;">
                                                <p>Please enter the characters shown below:</p>
                                                <img id="captchaimage" src="{{ route('captcha') }}" alt="Captcha">
                                                
                                                <input type="text" name="backup_captcha" placeholder="Enter CAPTCHA" autocomplete="off">
                                                <button type="button" onclick="refreshCaptcha()">Refresh CAPTCHA</button>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center" style="margin-top:20px;">
                                            <button type="submit" class="btn btn-primary">Sign In</button>
                                        </div>
                                        <p class="mt-3 text-center">
                                            Don't have an account? <a href="{{route('register')}}" class="text-underline">Click here to sign up.</a>
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

        try {
            var chartElement = document.getElementById("chart-line");
            if (chartElement) {
                var ctx1 = chartElement.getContext("2d");

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
            }
        } catch(e) {
            console.log('Chart not initialized - element not found');
        }

        
        function refreshCaptcha() {
            var captchaImg = document.getElementById('captchaimage'); 
            if (captchaImg) {
                var timestamp = new Date().getTime();
                captchaImg.src = '{{ route("captcha") }}?t=' + timestamp;
                console.log('CAPTCHA refreshed');
                
                var input = document.querySelector('input[name="backup_captcha"]');
                if (input) {
                    input.value = '';
                    input.focus();
                }
            } else {
                console.error('Captcha image element not found');
            }
        }
    </script>

</body>

</html>