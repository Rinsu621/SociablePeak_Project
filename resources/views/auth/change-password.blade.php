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

<div class="d-flex justify-content-center align-items-center " style="margin-top: 50px;">
    <div class="card shadow w-100" style="max-width: 500px;">
        <div class="card-body">
            <h4 class="mb-4 text-center">Change Password</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('changePassword') }}">
                @csrf

                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
