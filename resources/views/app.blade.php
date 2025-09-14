<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/js/app.js')
  <title>{{ $title}}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @stack('styles')
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
    
    /* Auth pages background */
    .auth-background {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
        min-height: 100vh !important;
    }
    
    /* Force auth form styling */
    .auth-background .fas.fa-envelope,
    .auth-background .fas.fa-lock {
        color: #4f46e5 !important;
    }
    
    .auth-background input[type="email"],
    .auth-background input[type="password"] {
        border-color: #a5b4fc !important;
    }
    
    .auth-background input[type="email"]:focus,
    .auth-background input[type="password"]:focus {
        border-color: #4f46e5 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1) !important;
    }
    
    .auth-background button[type="submit"] {
        background: linear-gradient(to right, #4f46e5, #3730a3) !important;
        border: none !important;
    }
    
    .auth-background button[type="submit"]:hover {
        background: linear-gradient(to right, #3730a3, #312e81) !important;
        transform: scale(1.02) !important;
    }
    
    .auth-background a {
        color: #4f46e5 !important;
    }
    
    .auth-background a:hover {
        color: #3730a3 !important;
    }
  </style>
</head>
<body class="bg-gray-100">
@yield('content')
@stack('scripts')
</body>
</html>