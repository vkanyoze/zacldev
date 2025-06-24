<body>
    <header class="fixed top-0 left-0 w-full bg-white shadow-md z-20">
  <div class="flex items-center justify-start px-6 h-16">
    <a href="https://www.zacl.co.zm/"><img src="/front-logo.png" alt="logo" class="h-9"/></a>
    <div class="ml-6 text-lg font-bold"><div class="font-bold text-custom-gray">Zambia Airports Corporation Limited</div><div class="text-base text-custom-green font-bold -mt-2">Payment Gateway</div></div>
    <div class="flex-grow"></div>
<form action="{{ route('signout')  }}" method="GET" style="display: inline-block;">
                            @csrf
                            @method('GET')
  <button type="submit" class="flex space-x-2 items-center text-white bg-custom-gray hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded text-sm px-5 py-2.5 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">        
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 hr-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
        </svg>
        <span>Log out</span>
    </button>
     </form>
  </div>
</header>