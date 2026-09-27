@props([
    'disabled' => false,
    'label' => null,
    'name' => '',
    'type' => 'text',
    'error' => null,
    'required' => false,
    'placeholder' => '',
    'hint' => null,
])

<div class="space-y-1.5 text-start">
    @if($label)
        <label for="{{ $name }}" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">
            {{ $label }}
            @if($required)
                <span class="text-[#D4AF37]">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full rounded-xl border px-4 py-2.5 text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 ' . 
                    ($errors->has($name) || $error
                        ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200 text-rose-900 bg-rose-50/30'
                        : 'border-slate-300 dark:border-white/20 focus:border-[#D4AF37] focus:ring-[#D4AF37]/20 text-[#071A36] dark:text-white bg-white dark:bg-[#071A36]/80 placeholder:text-slate-400 dark:placeholder:text-slate-500')
            ]) }}
        >
    </div>

    @if($hint && !$errors->has($name) && !$error)
        <p class="text-xs text-slate-500">{{ $hint }}</p>
    @endif

    @if($errors->has($name) || $error)
        <p class="text-xs text-rose-600 font-medium">
            {{ $errors->first($name) ?: $error }}
        </p>
    @endif
</div>

