@extends('layouts.app', ['title' => 'Bước 2: Thông tin'])

@section('content')
    @include('public.register.partials.stepper', ['currentStep' => 2])

    <div class="text-center">
        <div class="rsvp-heading">Bước 2: Thông tin người mời khách</div>
        <div class="mt-2 text-sm text-neutral-200/75">Nhập thông tin để nhận email xác nhận.</div>
    </div>

    @php
        $phone = (string) old('phone', $draft['phone'] ?? '');
        $parsedCountry = null;
        $parsedNumber = null;
        if (preg_match('/^(\\+(?:84|1|82|81|65))(\\d+)$/', $phone, $m)) {
            $parsedCountry = $m[1];
            $parsedNumber = $m[2];
        }
        $phoneCountry = (string) old('phone_country', $parsedCountry ?? '+84');
        $phoneNumber = (string) old('phone_number', $parsedNumber ?? preg_replace('/\\D+/', '', $phone));
    @endphp

    <form method="post" action="{{ url('/register/step2') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <div class="rsvp-label">Họ &amp; Tên <span class="text-red-500">*</span></div>
            <input
                id="full_name"
                name="full_name"
                value="{{ old('full_name', $draft['full_name'] ?? '') }}"
                required
                class="rsvp-input"
            />
        </div>

        <div>
            <div class="rsvp-label">Email</div>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $draft['email'] ?? '') }}"
                class="rsvp-input"
            />
        </div>

        <div>
            <div class="rsvp-label">Số liên hệ (Signal/Phone) <span class="text-red-500">*</span></div>
            <div class="mt-2 grid grid-cols-3 gap-3">
                <select name="phone_country" class="rsvp-select col-span-1">
                    <option value="+84" {{ $phoneCountry === '+84' ? 'selected' : '' }}>+84 VN</option>
                    <option value="+1" {{ $phoneCountry === '+1' ? 'selected' : '' }}>+1 US</option>
                    <option value="+82" {{ $phoneCountry === '+82' ? 'selected' : '' }}>+82 KR</option>
                    <option value="+81" {{ $phoneCountry === '+81' ? 'selected' : '' }}>+81 JP</option>
                    <option value="+65" {{ $phoneCountry === '+65' ? 'selected' : '' }}>+65 SG</option>
                </select>
                <input
                    type="tel"
                    name="phone_number"
                    inputmode="numeric"
                    value="{{ $phoneNumber }}"
                    placeholder="Nhập số…"
                    required
                    class="rsvp-input col-span-2 mt-0"
                />
            </div>
            <div class="mt-2 text-xs text-neutral-200/60">Số này có thể dùng để gửi cập nhật (nếu cần).</div>
        </div>

        <label class="mt-2 flex items-center justify-between gap-4 rounded-2xl border border-neutral-500/30 bg-black/25 px-4 py-4">
            <div class="text-sm font-semibold tracking-wide text-neutral-100/85">Tôi sẽ tham dự cùng khách</div>
            <span class="relative inline-flex h-7 w-12 items-center rounded-full border border-neutral-500/40 bg-black/30 px-1">
                <input
                    type="checkbox"
                    name="attend_with_guest"
                    value="1"
                    class="peer sr-only"
                    {{ old('attend_with_guest', $draft['attend_with_guest'] ?? false) ? 'checked' : '' }}
                />
                <span class="h-5 w-5 rounded-full bg-neutral-300 transition peer-checked:translate-x-5 peer-checked:bg-[#d9b76f]"></span>
            </span>
        </label>

        <div class="flex items-center justify-between gap-4 pt-2">
            <a href="{{ url('/register') }}" class="btn-dark px-6 py-3 text-xs">QUAY LẠI</a>
            <button class="btn-gold">TIẾP TỤC</button>
        </div>
    </form>
@endsection

