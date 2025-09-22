<aside class="w-80 bg-custom-gray shadow-md hidden md:block lg:block text-white">
    <nav class="p-0">
        <ul class="mt-2 relative">
            <li class="mt-4 relative cursor-pointer">
                <a href="{{ route('dashboards') }}" class="flex items-center text-sm py-3 px-6 h-12 overflow-hidden {{ $activeNavItem === 'dashboard' ? 'bg-custom-green text-white hover:bg-custom-green font-bold' : 'text-white hover:text-gray-900 hover:bg-gray-100' }} text-ellipsis whitespace-nowrap transition duration-300 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 hr-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 8h2v-2H7v2zm0-4h2v-2H7v2zm0-4h2V7H7v2zm4 8h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V7h-2v2zm4 8h2v-2h-2v2zm0-4h2v-2h-2v2zm0-4h2V7h-2v2z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="mt-4 relative cursor-pointer">
                <a href="{{ route('payments.index') }}" class="flex items-center text-sm py-3 px-6 h-12 overflow-hidden {{ $activeNavItem === 'payments' ? 'bg-custom-green text-white  hover:bg-custom-green  font-bold' : 'text-white hover:text-gray-900 hover:bg-gray-100' }}  text-ellipsis whitespace-nowrap transition duration-300 ease-in-out"  data-mdb-ripple="true" data-mdb-ripple-color="dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 hr-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    <span>Payments</span>
                </a>
            </li>
            <li class="mt-2 relative cursor-pointer">
                <a  href="{{ route('user.update') }}" class="flex items-center text-sm py-3 px-6 h-12 overflow-hidden {{ $activeNavItem === 'email' ? 'bg-custom-green text-white  hover:bg-custom-green  font-bold' : 'text-white hover:text-gray-900 hover:bg-gray-100' }} text-ellipsis whitespace-nowrap transition duration-300 ease-in-out"  data-mdb-ripple="true" data-mdb-ripple-color="dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 hr-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                    <span>Email Address</span>
                </a>
            </li>
            <li class="mt-2 relative cursor-pointer">
                <a href="{{ route('user.password') }}" class="flex items-center text-sm py-3 px-6 h-12 overflow-hidden  text-white {{ $activeNavItem === 'password' ? 'bg-custom-green text-white  hover:bg-custom-green  font-bold' : 'text-white hover:text-gray-900 hover:bg-gray-100' }}  text-ellipsis whitespace-nowrap transition duration-300 ease-in-out"  data-mdb-ripple="true" data-mdb-ripple-color="dark">
                    <svg xmlns="http://www.w3.org/2000/svg" focusable="false" class="w-4 hr-4 mr-2" role="img" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Passwords</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>