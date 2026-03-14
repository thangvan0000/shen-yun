@extends('layouts.app', ['title' => 'Admin – Suất diễn'])

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Suất diễn</h1>
            <p class="mt-1 text-sm text-neutral-600">Quản lý danh sách suất diễn.</p>
        </div>

        <a
            href="{{ url('/admin/sessions/create') }}"
            class="rounded-xl bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800"
        >
            Thêm suất diễn
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs font-semibold text-neutral-600">
                <tr>
                    <th class="px-4 py-3">Suất diễn</th>
                    <th class="px-4 py-3">Capacity</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($sessions as $s)
                    @php
                        $remaining = max(0, $s->capacity_total - $s->capacity_reserved);
                    @endphp
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $s->venue->name }}</div>
                            <div class="text-xs text-neutral-600">{{ $s->starts_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-4 py-3 text-neutral-700">
                            {{ $s->capacity_reserved }} / {{ $s->capacity_total }}
                            <div class="text-xs text-neutral-500">Còn {{ $remaining }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-neutral-100 px-2 py-0.5 text-xs font-medium text-neutral-800">
                                {{ $s->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a
                                    href="{{ url('/admin/sessions/'.$s->id.'/edit') }}"
                                    class="rounded-lg border border-neutral-300 bg-white px-3 py-1.5 text-xs font-medium hover:bg-neutral-50"
                                >
                                    Sửa
                                </a>
                                <form method="post" action="{{ url('/admin/sessions/'.$s->id) }}" onsubmit="return confirm('Xoá suất diễn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-rose-300 bg-white px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-50">
                                        Xoá
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-10 text-center text-neutral-600" colspan="4">Chưa có suất diễn.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sessions->links() }}
    </div>
@endsection

