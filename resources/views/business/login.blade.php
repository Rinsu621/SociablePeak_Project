

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>SociablePeak </title>

      <link rel="shortcut icon" href="{{ asset('/images/template/favicon.ico')}}" />
      <link rel="stylesheet" href="{{ asset('/css/libs.min.css')}}">
      <link rel="stylesheet" href="{{ asset('/css/socialv.css')}}">
      <link rel="stylesheet" href="{{ asset('/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}">
      <link rel="stylesheet" href="{{ asset('/vendor/remixicon/fonts/remixicon.css')}}">
      <link rel="stylesheet" href="{{ asset('/vendor/vanillajs-datepicker/dist/css/datepicker.min.css')}}">
      <link rel="stylesheet" href="{{ asset('/vendor/font-awesome-line-awesome/css/all.min.css')}}">
      <link rel="stylesheet" href="{{ asset('/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css')}}">
      <style>
        body {
          background: linear-gradient(to right, #3a7bd5, #00d2ff);
        }

        #container-inside {
          position: absolute;
          top: 0;
          left: 0;
          width: 50%;
          height: 100%;
          overflow: hidden;
        }

        #circle-small, #circle-medium, #circle-large, #circle-xlarge, #circle-xxlarge {
          position: absolute;
          border-radius: 50%;
          animation: animateCircle 5s linear infinite;
          background: rgba(255, 255, 255, 0.3);
          border: 2px solid rgba(255, 255, 255, 0.5);
        }

        #circle-small {
          width: 150px;
          height: 150px;
          top: 20%;
          left: 25%;
          animation-delay: 0s;
        }

        #circle-medium {
          width: 250px;
          height: 250px;
          top: 40%;
          left: 35%;
          animation-delay: 2s;
        }

        #circle-large {
          width: 350px;
          height: 350px;
          top: 10%;
          left: 15%;
          animation-delay: 4s;
        }

        #circle-xlarge {
          width: 450px;
          height: 450px;
          top: 50%;
          left: 5%;
          animation-delay: 6s;
        }

        #circle-xxlarge {
          width: 550px;
          height: 550px;
          top: 30%;
          left: 40%;
          animation-delay: 8s;
        }

        @keyframes animateCircle {
          0% { transform: scale(1) translateY(0); }
          50% { transform: scale(1.1) translateY(20px); }
          100% { transform: scale(1) translateY(0); }
        }
      </style>

  </head>
  <body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->

      <div class="wrapper">
    <section class="sign-in-page">
        <div id="container-inside">
            <div id="circle-small"></div>
            <div id="circle-medium"></div>
            <div id="circle-large"></div>
            <div id="circle-xlarge"></div>
            <div id="circle-xxlarge"></div>
        </div>
        <div class="container p-0" >
            <div class="row no-gutters">
                <div class="col-md-6 text-center pt-5">
                    <div class="sign-in-detail text-white">
                        <a class="sign-in-logo mb-5" href="#"><img src="{{ asset('/images/logo-full.png')}}" class="img-fluid" alt="logo"></a>
                        <div class="sign-slider overflow-hidden ">
                            <ul  class="swiper-wrapper list-inline m-0 p-0 ">
                                <li class="swiper-slide">
                                    <img src="{{ asset('/images/template/login/1.png')}}" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Promote Your Business</h4>
                                    <p>Reach your target audience with tailored advertisements. Boost your visibility and grow your brand effectively.</p>
                                </li>
                                <li class="swiper-slide">
                                    <img src="{{ asset('/images/template/login/2.png')}}" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Connect with Customers</h4>
                                    <p>Engage with potential clients and build lasting relationships through interactive business features.</p>
                                </li>
                                <li class="swiper-slide">
                                    <img src="{{ asset('/images/template/login/3.png')}}" class="img-fluid mb-4" alt="logo">
                                    <h4 class="mb-1 text-white">Advertise with Ease</h4>
                                    <p>Create and manage your ads seamlessly. Showcase your products or services to the right people at the right time.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 bg-white pt-5 pt-5 pb-lg-0 pb-5" >
                    <div class="sign-in-from" style="margin-top: 100px;">
                        <h1 class="mb-0">Sign in</h1>
                        <p>Enter your email address and password to login.</p>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('businesslogin') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="exampleInputEmail1">Email address</label>
                                <input name="email" type="email" class="form-control mb-0" id="exampleInputEmail1" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="exampleInputPassword1">Password</label>
                                <a href="{{route('forgetPassword')}}" class="float-end">Forgot password?</a>
                                <input name="password" type="password" class="form-control mb-0" id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="d-inline-block w-100">
                                <div class="form-check d-inline-block mt-2 pt-1">
                                    <input type="checkbox" class="form-check-input" id="customCheck11">
                                    <label class="form-check-label" for="customCheck11">Remember Me</label>
                                </div>
                                <button type="submit" class="btn btn-primary float-end">Sign in</button>
                            </div>
                            <div class="sign-info">
                                <span class="dark-color d-inline-block line-height-2">Don't have an account? <a href="{{route('businessregister')}}">Sign up</a></span>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
      </div>

    <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('/js/libs.min.js')}}"></script>
    <!-- slider JavaScript -->
    <script src="{{ asset('/js/slider.js')}}"></script>
    <!-- masonry JavaScript -->
    <script src="{{ asset('/js/masonry.pkgd.min.js')}}"></script>
    <!-- SweetAlert JavaScript -->
    <script src="{{ asset('/js/enchanter.js')}}"></script>
    <!-- SweetAlert JavaScript -->
    <script src="{{ asset('/js/sweetalert.js')}}"></script>
    <!-- app JavaScript -->
    <script src="{{ asset('/js/charts/weather-chart.js')}}"></script>
    <script src="{{ asset('/js/app.js')}}"></script>
    <script src="../vendor/vanillajs-datepicker/dist/js/datepicker.min.js')}}"></script>
    <script src="{{ asset('/js/lottie.js')}}"></script>

  </body>
</html>
