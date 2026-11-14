@props(['field'])
@error($field)
<p {{ $attributes->merge(['class' => 'field-error', 'id' => $field.'-error']) }}>{{ $message }}</p>
@enderror
