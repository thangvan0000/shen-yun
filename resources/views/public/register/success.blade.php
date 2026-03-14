@extends('layouts.app', ['title' => 'Đăng ký thành công'])

@section('content')
    <div class="text-center">
        <div class="rsvp-heading">Đăng ký thành công</div>
        <div class="mt-2 text-sm text-neutral-200/75">
            Cảm ơn {{ $registration->full_name }} — mã đăng ký <span class="font-mono font-semibold text-[#f3e2b6]">#{{ $registration->id }}</span>
        </div>
    </div>

    <div class="mt-6 rounded-3xl border border-neutral-500/25 bg-black/30 p-6 shadow-[inset_0_0_0_1px_rgba(217,183,111,0.12)]">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <div class="text-xs tracking-[0.2em] text-neutral-200/60">ĐỊA ĐIỂM</div>
                <div class="mt-1 text-lg font-semibold text-[#d9b76f]">{{ $registration->eventSession->venue->name }}</div>
                @if ($registration->eventSession->venue->address)
                    <div class="mt-1 text-sm text-neutral-200/70">{{ $registration->eventSession->venue->address }}</div>
                @endif
            </div>
            <div>
                <div class="text-xs tracking-[0.2em] text-neutral-200/60">THỜI GIAN</div>
                <div class="mt-1 text-lg font-semibold text-[#d9b76f]">
                    {{ $registration->eventSession->starts_at->format('d/m/Y H:i') }}
                </div>
            </div>
            <div class="sm:col-span-2">
                <div class="text-xs tracking-[0.2em] text-neutral-200/60">TỔNG SỐ KHÁCH</div>
                <div class="mt-1 text-2xl font-semibold text-neutral-100">{{ $registration->total_count }}</div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex items-center justify-between gap-4">
        <a href="{{ url('/') }}" class="btn-dark px-6 py-3 text-xs">TRANG CHỦ</a>
        <a href="{{ url('/login') }}" class="btn-gold">ĐĂNG KÝ MỚI</a>
    </div>
@endsection

