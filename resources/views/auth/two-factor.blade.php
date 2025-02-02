{{-- <!-- resources/views/auth/two-factor.blade.php -->
<form action="{{ route('verifyTwoFactor') }}" method="POST">
    @csrf
    <div>
        <label for="two_factor_code">Enter the 6-digit code sent to your email:</label>
        <input type="text" id="two_factor_code" name="two_factor_code" required>
    </div>

    <button type="submit">Verify Code</button>

    @error('two_factor_code')
        <div class="error">{{ $message }}</div>
    @enderror
</form> --}}

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Two-Factor Authentication</title>
<style>
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }

  .container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 350px;
    text-align: center;
  }

  h1 {
    color: #004a7c;
    font-size: 22px;
  }

  p {
    font-size: 16px;
    line-height: 1.5;
  }

  input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
  }

  button {
    background-color: #004a7c;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
  }

  button:hover {
    background-color: #007acc;
  }

  .error {
    color: red;
    font-size: 14px;
    margin-top: 10px;
  }
</style>
</head>
<body>

<div class="container">
  <h1>Two-Factor Authentication</h1>
  <p>Enter the 6-digit code sent to your email.</p>

  <form action="{{ route('verifyTwoFactor') }}" method="POST">
      @csrf
      <input type="text" id="two_factor_code" name="two_factor_code" placeholder="Enter code" required>
      <button type="submit">Verify Code</button>

      @error('two_factor_code')
          <div class="error">{{ $message }}</div>
      @enderror
  </form>
</div>

</body>
</html>

