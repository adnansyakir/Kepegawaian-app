<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
      body { font-family: Arial, Helvetica, sans-serif; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; background:#f7f7f7 }
      .card { background:#fff; border:1px solid #e1e1e1; padding:24px; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.05); width:360px; }
      h1 { margin-top:0; font-size:20px }
      label { display:block; margin-top:12px; font-size:13px }
      input[type="email"], input[type="password"] { width:100%; padding:8px 10px; margin-top:6px; box-sizing:border-box }
      .error { color:#b00020; font-size:13px }
      button { margin-top:16px; padding:10px 14px; background:#2563eb; color:#fff; border:none; border-radius:4px }
    </style>
  </head>
  <body>
    <div class="card">
      <h1>Login</h1>

      @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.perform') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <div style="margin-top:10px;">
          <label><input type="checkbox" name="remember"> Remember me</label>
        </div>

        <button type="submit">Login</button>
      </form>

    </div>
  </body>
</html>
