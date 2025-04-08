<!-- resources/views/auth/two-factor.blade.php -->
@extends('layout')

@section('content')
<style>
    body{
        background:linear-gradient(to right, #3a7bd5, #00d2ff);
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
<div id="container-inside">
    <div id="circle-small"></div>
    <div id="circle-medium"></div>
    <div id="circle-large"></div>
    <div id="circle-xlarge"></div>
    <div id="circle-xxlarge"></div>
</div>
    <div class="d-flex justify-content-center align-items-center" style="margin-top:60px">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Two-Factor Authentication</h4>

                <form method="POST" action="{{ route('verifyTwoFactor') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="code">Enter Verification Code:</label>
                        <input type="text" name="code" class="form-control" id="code" required>
                        @if ($errors->has('code'))
                            <div class="text-danger mt-1">{{ $errors->first('code') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Verify Code</button>
                </form>
            </div>
        </div>
    </div>
@endsection
