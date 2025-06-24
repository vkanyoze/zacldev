<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/js/app.js')
  <title>{{ $title}}</title>
  <style>
    .custom-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23333' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 8px 10px;
        padding-right: 1.5rem;
    }
    .loader {
                border: 2px solid #f3f3f3; /* Light grey */
                border-top: 2px solid #445797; /* Blue */
                border-radius: 50%;
                width: 24px;
                height: 24px;
                animation: spin 3s linear infinite;
        }
    @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
@yield('content')
</body>
</html>