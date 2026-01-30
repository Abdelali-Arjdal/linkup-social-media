@props(['disabled' => false])

<input {{ $attributes->merge(['class' => 'input' . ($errors->has($attributes->get('name')) ? ' input-error' : '')]) }} {{ $disabled ? 'disabled' : '' }} />
