@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-lintasarta-blue focus:ring-lintasarta-blue rounded-md shadow-sm']) !!}>
