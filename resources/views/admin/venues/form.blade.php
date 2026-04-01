@php
    $venue = $venue ?? null;
@endphp

<div class="space-y-4">
    <div>
        <label class="text-sm font-medium" for="name">Tên <span class="text-red-500">*</span></label>
        <input
            id="name"
            name="name"
            value="{{ old('name', $venue?->name ?? '') }}"
            required
            class="mt-2 w-full rounded-xl border bg-white px-3 py-2 text-sm outline-none focus:border-neutral-900 {{ $errors->has('name') ? 'admin-field-invalid' : 'border-neutral-300' }}"
        />
    </div>

    <div>
        <label class="text-sm font-medium" for="address">Địa chỉ</label>
        <input
            id="address"
            name="address"
            value="{{ old('address', $venue?->address ?? '') }}"
            class="mt-2 w-full rounded-xl border bg-white px-3 py-2 text-sm outline-none focus:border-neutral-900 {{ $errors->has('address') ? 'admin-field-invalid' : 'border-neutral-300' }}"
        />
    </div>

    <div>
        <label class="text-sm font-medium" for="timezone">Timezone <span class="text-red-500">*</span></label>
        <input
            id="timezone"
            name="timezone"
            value="{{ old('timezone', $venue?->timezone ?? 'Asia/Ho_Chi_Minh') }}"
            required
            class="mt-2 w-full rounded-xl border bg-white px-3 py-2 text-sm outline-none focus:border-neutral-900 {{ $errors->has('timezone') ? 'admin-field-invalid' : 'border-neutral-300' }}"
        />
        <div class="mt-1 text-xs text-neutral-500">Ví dụ: <span class="font-mono">Asia/Ho_Chi_Minh</span></div>
    </div>
</div>

