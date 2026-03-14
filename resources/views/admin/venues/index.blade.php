@extends('layouts.app', ['title' => 'Admin – Địa điểm'])

@section('content')
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Địa điểm</h1>
            <p class="mt-1 text-sm text-neutral-600">Quản lý danh sách địa điểm.</p>
        </div>

        <a
            href="{{ url('/admin/venues/create') }}"
            class="rounded-xl bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800"
        >
            Thêm địa điểm
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs font-semibold text-neutral-600">
                <tr>
                    <th class="px-4 py-3">Tên</th>
                    <th class="px-4 py-3">Địa chỉ</th>
                    <th class="px-4 py-3">Timezone</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($venues as $v)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $v->name }}</td>
                        <td class="px-4 py-3 text-neutral-700">{{ $v->address ?? '—' }}</td>
                        <td class="px-4 py-3 text-neutral-700">{{ $v->timezone }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a
                                    href="{{ url('/admin/venues/'.$v->id.'/edit') }}"
                                    class="rounded-lg border border-neutral-300 bg-white px-3 py-1.5 text-xs font-medium hover:bg-neutral-50"
                                >
                                    Sửa
                                </a>
                                <form method="post" action="{{ url('/admin/venues/'.$v->id) }}" onsubmit="return confirm('Xoá địa điểm này?')">
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
                        <td class="px-4 py-10 text-center text-neutral-600" colspan="4">Chưa có địa điểm.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $venues->links() }}
    </div>
@endsection

