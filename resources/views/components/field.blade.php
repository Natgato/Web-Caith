@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => false, 'autocomplete' => null])
<div class="field">
    <label for="{{ $name }}">{{ $label }}@if($required) <span aria-hidden="true">*</span>@endif</label>
    @if($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" rows="5" @required($required) @if($errors->has($name)) aria-invalid="true" aria-describedby="error-{{ $name }}" @endif>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if($type !== 'file') value="{{ $type === 'password' ? '' : old($name, $value) }}" @endif @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif @required($required) @if($type === 'number') step="any" @endif @if($errors->has($name)) aria-invalid="true" aria-describedby="error-{{ $name }}" @endif>
    @endif
    @error($name)<p class="field-error" id="error-{{ $name }}">{{ $message }}</p>@enderror
</div>
