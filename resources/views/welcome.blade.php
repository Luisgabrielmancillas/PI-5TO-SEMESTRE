<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased bg-white text-gray-900">
  <main class="min-h-screen grid place-items-center p-8">
    <div class="text-center space-y-4">
      <h1 class="text-3xl font-semibold">Laravel</h1>
      <p>Proyecto en marcha.</p>
      <div class="space-x-3">
        <a href="{{ route('login') }}" class="underline">Login</a>
        <a href="{{ route('register') }}" class="underline">Register</a>
      </div>
    </div>
  </main>
</body>
</html>
