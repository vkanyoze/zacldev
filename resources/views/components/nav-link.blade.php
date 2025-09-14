@props(['active' => false, 'mobile' => false])

@php
$classes = $mobile 
    ? 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition duration-150 ease-in-out ' . 
      ($active 
          ? 'border-blue-400 text-blue-700 bg-blue-50 focus:outline-none focus:text-blue-800 focus:bg-blue-100 focus:border-blue-700' 
          : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300')
    : 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out ' .
      ($active 
          ? 'border-blue-500 text-gray-900 focus:outline-none focus:border-blue-700' 
          : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
