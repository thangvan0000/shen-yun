@extends('layouts.app', ['title' => 'Admin – Đăng ký'])

@section('content')
    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-3xl border border-white/25 bg-white/65 shadow-[0_16px_60px_rgba(0,0,0,0.18)] backdrop-blur-md">
        <div class="px-5 sm:px-6 pt-5 sm:pt-6 pb-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">Danh sách khách đã đăng ký</h1>
                    <p class="mt-1 text-sm text-neutral-700/80">Xem và xuất file Excel (CSV).</p>
                </div>

                <div class="flex items-center gap-2">
                    <select
                        onchange="window.location.href = '{{ url('/admin/registrations') }}?' + (this.value ? 'status=' + this.value : '')"
                        class="rounded-xl border border-neutral-300 bg-white px-3 py-2 text-sm outline-none focus:border-neutral-900"
                    >
                        <option value="" {{ empty($statusFilter) ? 'selected' : '' }}>Tất cả</option>
                        <option value="confirmed" {{ $statusFilter === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="cancelled" {{ $statusFilter === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <a
                    href="{{ url('/admin/registrations/export.xls') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-neutral-900 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-neutral-800"
                >
                    Xuất Excel
                </a>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-white/35 bg-white/70 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full text-sm">
                <thead class="bg-white/70 text-left text-xs font-semibold text-neutral-700">
                    <tr class="[&>th]:px-5 [&>th]:py-4">
                        <th class="w-20 pl-6">Mã</th>
                        <th class="w-56">Người mời</th>
                        <th class="w-72">Gmail</th>
                        <th class="w-40">SĐT</th>
                        <th class="w-36">Suất diễn</th>
                        <th class="w-24">Khách</th>
                        <th class="w-24">NTL</th>
                        <th class="w-28">NTL mới</th>
                        <th class="w-24">Trẻ em</th>
                        <th class="w-24">Tổng</th>
                        <th class="w-24">Trạng thái</th>
                        <th class="w-36">Tạo lúc</th>
                        <th class="w-20 pr-6"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200/70">
                    @forelse ($registrations as $r)
                        <tr class="hover:bg-black/3">
                            <td class="pl-6 py-4 font-mono text-xs text-neutral-700">#{{ $r->id }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ url('/admin/registrations/'.$r->id.'/edit') }}" class="block min-h-[44px] min-w-[44px] flex items-center font-semibold text-neutral-900 hover:underline">
                                    {{ $r->full_name }}
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-neutral-900">{{ $r->email }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-neutral-900">{{ $r->phone ?: '—' }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-semibold text-neutral-900">{{ $r->eventSession->starts_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-neutral-900">{{ $r->adult_count }}</td>
                            <td class="px-5 py-4 font-semibold text-neutral-900">{{ $r->ntl_count }}</td>
                            <td class="px-5 py-4 font-semibold text-neutral-900">{{ $r->ntl_new_count }}</td>
                            <td class="px-5 py-4 font-semibold text-neutral-900">{{ $r->child_count }}</td>
                            <td class="px-5 py-4 font-semibold text-neutral-900">{{ $r->total_count }}</td>
                            <td class="px-5 py-4">
                                @if($r->status === 'confirmed')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">Đã xác nhận</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">Đã hủy</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-neutral-800 whitespace-nowrap">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                            <td class="pr-6 py-4">
                                <a href="{{ url('/admin/registrations/'.$r->id.'/edit') }}" class="text-sm text-neutral-700 hover:underline">Sửa</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-12 text-center text-sm text-neutral-700/80" colspan="12">
                                Chưa có đăng ký.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $registrations->links() }}
    </div>
@endsection

