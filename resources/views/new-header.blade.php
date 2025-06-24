    <header class="bg-custom-gray md:bg-white lg:bg-white shadow-md w-full z-10 hidden md:flex lg:flex">
    <div class="flex justify-between items-center p-3 px-6 w-full">
        <a href="https://www.zacl.co.zm/" class="hidden sm:hidden md:flex lg:flex"><img src="/front-logo.png" alt="logo" class="h-9 mr-6"/></a>
        <div class="text-lg font-bold flex-1">
            <div class="font-bold text-white  sm:text-white md:text-custom-gray lg:text-custom-gray">Zambia Airports Corporation Limited</div>
            <div class=" text-green-500 text-base font-bold -mt-2">Payment Gateway</div></div>
        <form action="{{ route('signout')  }}" method="GET" style="display: inline-block;">
                            @csrf
                            @method('GET')
        <button type="submit" class=" hidden sm:hidden md:flex lg:flex space-x-2 items-center text-white bg-custom-gray hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">        
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 hr-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                </svg>
            <span>Log out</span>
        </button>
        </form>
    </div>
</header>
<header class="bg-custom-gray shadow-md w-full z-10 flex md:hidden lg:hidden fixed top-0 left-0">
    <div class="flex justify-between items-center p-3 px-6 w-full">
        <a href="https://www.zacl.co.zm/" class="hidden sm:hidden md:flex lg:flex"><img src="/front-logo.png" alt="logo" class="h-9 mr-6"/></a>
        <div class="text-lg font-bold flex-1">
            <div class="font-bold text-white  sm:text-white md:text-custom-gray lg:text-custom-gray">Zambia Airports Corporation Limited</div>
            <div class=" text-green-500 text-base font-bold -mt-2">Payment Gateway</div></div>
        <button class="ml-auto sm:flex md:hidden lg:hidden focus:outline-none" id="menu-toggle">
            <svg xmlns="http://www.w3.org/2000/svg"  id="humburger"  class="h-6 w-6 text-white fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" id="cancel" class="h-6 w-6 hidden" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="#fff" d="M18 6l-12 12"></path>
                <path stroke="#fff" d="M6 6l12 12"></path>
            </svg>
        </button>
        <form action="{{ route('signout')  }}" method="GET" style="display: inline-block;">
                            @csrf
                            @method('GET')
        <button type="submit" class=" hidden sm:hidden md:flex lg:flex space-x-2 items-center text-white bg-custom-gray hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">        
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 hr-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                </svg>
            <span>Log out</span>
        </button>
        </form>
    </div>
    <nav id="mobile-menu" class="mt-16 z-10 absolute pb-4 top-0 left-0 w-full bg-custom-gray text-white hidden md:hidden lg:hidden">
        <ul class="list-none w-full">
            <li class="px-6 py-4 hover:bg-gray-700 w-full">
                <a href="{{ route('payments.index') }}" class="block">Payments</a>
            </li>
            <li class="px-6 py-4 hover:bg-gray-700">
                <a href="{{ route('cards.index') }}" class="block">Cards</a>
            </li>
            <li class="px-6 py-4 hover:bg-gray-700">
                <a  href="{{ route('user.update') }}" class="block">Email Address</a>
            </li>
            <li class="px-6 py-4 hover:bg-gray-700">
                <a href="{{ route('user.password') }}" class="block">Passwords</a>
            </li>
            <li class="px-6 py-4 hover:bg-gray-700">
                <a href="{{ route('signout')}}" class="block">Logout</a>
            </li>
        </ul>
    </nav>
</header>
<script>
    document.getElementById('menu-toggle').addEventListener('click', () => {
        const mobileMenu = document.getElementById('mobile-menu');
        const humburger = document.getElementById("humburger");
        const cancel = document.getElementById("cancel");
        humburger.classList.toggle("hidden");
        cancel.classList.toggle("hidden");
        mobileMenu.classList.toggle("hidden");
    });
</script>