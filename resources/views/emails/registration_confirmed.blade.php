<x-mail::message>
# Xác nhận đăng ký tham dự

Chào {{ $registration->full_name }},

Hệ thống đã ghi nhận đăng ký của bạn.

**Mã đăng ký:** #{{ $registration->id }}

**Địa điểm:** {{ $registration->eventSession->venue->name }}@if($registration->eventSession->venue->address) — {{ $registration->eventSession->venue->address }}@endif  
**Thời gian:** {{ $registration->eventSession->starts_at->format('d/m/Y H:i') }}  
**Tổng số khách:** {{ $registration->total_count }}

Nếu có thay đổi, vui lòng liên hệ ban tổ chức.

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
