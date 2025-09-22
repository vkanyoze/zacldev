<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/js/app.js')
    <title>Two-Factor Authentication - ZACL</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-600 to-indigo-800 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 sm:p-8">
        <div class="flex flex-col items-center">
            <a href="https://www.zacl.co.zm/" class="flex justify-center mb-6">
                <img src="/front-logo.png" alt="Zambia AIRPORTS Corporation Limited" class="h-16 w-auto" />
            </a>
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-indigo-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-center text-black mb-2">Two-Factor Authentication</h2>
                <p class="text-center text-gray-600 text-sm">Enter the 6-digit code sent to your email</p>
            </div>
        </div>

        <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-6" id="2faForm">
            @csrf
            <div>
                <label class="block text-black font-medium mb-2" for="code">Verification Code</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-key text-indigo-600"></i>
                    </div>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           maxlength="6" 
                           pattern="[0-9]{6}"
                           placeholder="000000" 
                           autocomplete="off"
                           class="w-full p-3 pl-10 text-center text-2xl font-mono tracking-widest rounded-lg border-2 {{ $errors->has('code') ? 'border-red-500' : 'border-indigo-300' }} focus:outline-none focus:border-indigo-600 text-gray-900 placeholder-gray-400"
                           required>
                </div>
                @if ($errors->has('code'))
                    <span class="text-sm text-red-600 mt-1">{{ $errors->first('code') }}</span>
                @endif
            </div>

            <button type="submit" 
                    class="w-full py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-medium text-lg hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-check mr-2"></i>
                Verify Code
            </button>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" 
                        id="resendBtn" 
                        class="flex-1 py-2 px-4 border border-indigo-300 text-indigo-600 rounded-lg hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Resend Code
                </button>
                
                @if(app()->environment('local'))
                <button type="button" 
                        onclick="window.location.href='{{ route('2fa.skip') }}'"
                        class="flex-1 py-2 px-4 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-600 transition-all duration-300">
                    <i class="fas fa-forward mr-2"></i>
                    Skip (Dev)
                </button>
                @endif
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-500">
                    Didn't receive the code? Check your spam folder or 
                    <a href="{{ route('2fa.resend') }}" class="text-indigo-600 hover:underline">resend</a>
                </p>
            </div>
        </form>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div id="successAlert" class="fixed top-4 right-4 z-50 flex items-center p-4 mb-4 text-white bg-green-500 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out" role="alert">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <span class="font-medium">Success!</span> {{ session('success') }}
        </div>
        <button type="button" onclick="document.getElementById('successAlert').remove()" class="ml-auto -mx-1.5 -my-1.5 text-white hover:text-gray-200 rounded-lg p-1.5 inline-flex h-8 w-8">
            <span class="sr-only">Close</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    <script>
        // Auto-format code input (numbers only)
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 6) {
                document.getElementById('2faForm').submit();
            }
        });

        // Resend code functionality
        document.getElementById('resendBtn').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            
            fetch('{{ route("2fa.resend") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('New verification code sent to your email!', 'success');
                } else {
                    showAlert(data.message || 'Failed to send code. Please try again.', 'error');
                }
            })
            .catch(error => {
                showAlert('Failed to send code. Please try again.', 'error');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 z-50 flex items-center p-4 mb-4 text-white rounded-lg shadow-lg transform transition-all duration-300 ease-in-out ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            alertDiv.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <span class="font-medium">${type === 'success' ? 'Success!' : 'Error!'}</span> ${message}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="ml-auto -mx-1.5 -my-1.5 text-white hover:text-gray-200 rounded-lg p-1.5 inline-flex h-8 w-8">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
        }

        // Auto-hide success message after 5 seconds
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 300);
            }, 5000);
        }
    </script>
</body>
</html>
