@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar',  ['activeNavItem' => 'email'])
    <main class="w-screen text-gray-700">
        <div class="w-full sm:w-full lg:w-8/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12">
             <div class="block h-16 w-full bg-white md:hidden lg:hidden"></div>
            <div class="flex justify-between items-center max-w-2xl">
                <div class="text-2xl font-bold text-custom-gray">Update email address</div>
            </div>
            <div class="text-lg mt-2 flex max-w-2xl text-custom-gray">Keep your information up-to-date and ensure secure communication.</div>
            <div>
                <form action="{{ route('user.email') }}" method="POST">
                    @csrf
                    <div>
                    <label class="block text-custom-gray font-bold mt-4 max-w-2xl" for="full_name">New email Address<label>
                    <input type="text" placeholder="Email" name="email" class="mt-2 w-full p-3 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
                                            @if ($errors->has('email'))
                                            <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('email') }}</span>
                                            @endif
                    </div>
                    <div>
                    <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="password">Password<label>
                    <div class="relative w-full mt-1">
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 py-2">
                                        <input class="hidden js-password-toggle" id="toggle" type="checkbox" />
                                        <label class="mt-1.5 bg-gray-300 hover:bg-gray-400 rounded px-2 py-1 text-sm text-gray-600 cursor-pointer js-password-label " for="toggle">show</label>
                                    </div> <input id="password" name="password" type="password" autocomplete="off" placeholder="password" class="js-password mt-2 w-full p-3 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
                                </div>
                                @if ($errors->has('password'))
                                <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('password') }}</span>
                                @endif
                    </div>
                    <div class="flex mt-8">
                        <button class="px-6 py-4 bg-green-500 text-white rounded font-bold mt-3 w-full sm:w-full lg:w-auto md:w-auto">Change Email</button>
                    </div>
                </form>
        </div>
    <script>
        const passwordToggle = document.querySelector('.js-password-toggle')
        passwordToggle.addEventListener('change', function() {
            const password = document.querySelector('.js-password'),
                passwordLabel = document.querySelector('.js-password-label')

            if (password.type === 'password') {
                password.type = 'text'
                passwordLabel.innerHTML = 'hide'
            } else {
                password.type = 'password'
                passwordLabel.innerHTML = 'show'
            }

            password.focus()
        })
    </script>
    @if(session('success'))
        <div id="alert" class="text-white fixed drop-shadow-md top-4 right-4 w-96 z-20 flex p-4 mb-4 border-l-4 bg-custom-green" role="alert">
            <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <div class="ml-3 font-medium">
                {{ session('success') }} 
            </div>
            <button type="button" onclick="closeAlert()" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-3" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
            <script>
                const alertBox = document.getElementById('alert');
                close.addEvent
                if (alertBox) {
                    setTimeout(() => {
                        alertBox.remove();
                    }, 5000);
                }
                function closeAlert() {
                    var alertBox = document.getElementById("alert");
                    alertBox.style.display = "none";
                }
            </script>
    @endif
    @if(session('error'))
        <div id="alert" class="text-white fixed drop-shadow-md top-4 right-4 w-96 z-20 flex p-4 mb-4 border-l-4 bg-red-600" role="alert">
            <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            <div class="ml-3 font-medium">
                {{ session('error') }} 
            </div>
            <button type="button" onclick="closeAlert()" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-3" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
            <script>
                const secondAlertBox = document.getElementById('alert');
                close.addEvent
                if (secondAlertBox) {
                    setTimeout(() => {
                        secondAlertBox.remove();
                    }, 5000);
                }
                function closeAlert() {
                    var alertBox = document.getElementById("alert");
                    alertBox.style.display = "none";
                }
            </script>
    @endif
    </main>
</div>
@endsection