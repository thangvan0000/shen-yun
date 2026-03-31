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

                <a
                    href="{{ url('/admin/registrations/export.xls') }}?status={{ $statusFilter }}&session_id={{ $sessionIdFilter }}&search={{ $searchFilter }}"
                    class="inline-flex items-center justify-center rounded-3xl bg-[#1a1a1a] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-neutral-800"
                >
                    Xuất Excel
                </a>
            </div>

            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <div class="relative">
                    <button
                        onclick="applyFilters()"
                        class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-500 hover:text-neutral-800 cursor-pointer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </button>
                    <input
                        type="text"
                        id="searchFilter"
                        placeholder="Tìm SĐT hoặc người mời"
                        value="{{ $searchFilter ?? '' }}"
                        class="w-full rounded-xl border border-neutral-200 bg-white py-2 pl-9 pr-4 text-sm text-neutral-700 outline-none hover:bg-neutral-50 focus:border-neutral-300"
                        oninput="applyFilters()"
                    >
                </div>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <select
                        id="sessionFilter"
                        class="w-full appearance-none rounded-xl border border-neutral-200 bg-white py-2 pl-9 pr-8 text-sm text-neutral-700 outline-none hover:bg-neutral-50 focus:border-neutral-300"
                        onchange="applyFilters()"
                    >
                        <option value="">Trình chiếu: Tất cả</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ $sessionIdFilter == $session->id ? 'selected' : '' }}>
                                Trình chiếu: {{ $session->starts_at->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>

                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm0 0H4.5M13.5 12h6.75M13.5 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm0 0H4.5m0 6h15" />
                        </svg>
                    </div>
                    <select
                        id="statusFilter"
                        class="w-full appearance-none rounded-xl border border-neutral-200 bg-white py-2 pl-9 pr-8 text-sm text-neutral-700 outline-none hover:bg-neutral-50 focus:border-neutral-300"
                        onchange="applyFilters()"
                    >
                        <option value="" {{ empty($statusFilter) ? 'selected' : '' }}>Trạng thái: Tất cả</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Trạng thái: Chờ xác nhận</option>
                        <option value="confirmed" {{ $statusFilter === 'confirmed' ? 'selected' : '' }}>Trạng thái: Đã xác nhận</option>
                        <option value="cancelled" {{ $statusFilter === 'cancelled' ? 'selected' : '' }}>Trạng thái: Đã hủy</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-white/35 bg-white/70 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full text-sm">
                <thead class="bg-white/70 text-left text-xs font-semibold text-neutral-700">
                    <tr class="[&>th]:px-5 [&>th]:py-4">
                        <th class="w-20 pl-6">Mã</th>
                        <th class="w-56">Người mời</th>
                        <th class="w-40">SĐT</th>
                        <th class="w-36">Trình chiếu</th>
                        <th class="w-24">Khách</th>
                        <th class="w-24">NTL</th>
                        <th class="w-28">NTL mới</th>
                        <th class="w-24">Trẻ em</th>
                        <th class="w-24">Tổng</th>
                        <th class="w-24">Trạng thái</th>
                        <th class="w-24 pr-6">Thao tác</th>
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
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($r->phone)
                                    <div class="phone-hover-group relative inline-block">
                                        <span class="cursor-default text-neutral-900">{{ $r->phone }}</span>
                                        <div class="phone-actions absolute left-0 top-full z-10 mt-1 hidden flex-col rounded-xl border border-neutral-200 bg-white overflow-hidden shadow-lg w-32">
                                            <a href="tel:{{ $r->phone }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-neutral-700 hover:bg-emerald-50 transition-colors">
                                                <span class="material-symbols-outlined text-[18px] text-emerald-600">call</span>
                                                <span class="font-medium">Gọi điện</span>
                                            </a>
                                            <a href="https://zalo.me/{{ $r->phone }}" target="_blank" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-neutral-700 hover:bg-blue-50 transition-colors">
                                                <img src="https://page.widget.zalo.me/static/images/2.0/Logo.svg" alt="Zalo" class="w-[18px] h-[18px]">
                                                <span class="font-medium">Zalo</span>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-neutral-900">—</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-semibold text-neutral-900">{{ $r->eventSession->starts_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-neutral-900 text-center">{{ $r->adult_count }}</td>
                            <td class="px-5 py-4 font-semibold text-neutral-900 text-center">{{ $r->ntl_count }}</td>
                            <td class="px-5 py-4 font-semibold text-neutral-900 text-center">{{ $r->ntl_new_count }}</td>
                            <td class="px-5 py-4 font-semibold text-neutral-900 text-center">{{ $r->child_count }}</td>
                            <td class="px-5 py-4 font-semibold text-neutral-900 text-center">{{ $r->total_count }}</td>
                            <td class="px-4 py-4">
                                <div class="status-dropdown-wrap relative inline-block">
                                    @php
                                        $statusCfg = match($r->status) {
                                            'confirmed' => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'dot' => 'bg-green-500',  'label' => 'Đã xác nhận'],
                                            'cancelled' => ['bg' => 'bg-red-100',    'text' => 'text-red-600',    'dot' => 'bg-red-500',    'label' => 'Đã hủy'],
                                            default     => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'dot' => 'bg-amber-400',  'label' => 'Chờ xác nhận'],
                                        };
                                    @endphp
                                    <button type="button"
                                        class="status-badge-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-opacity hover:opacity-80 {{ $statusCfg['bg'] }} {{ $statusCfg['text'] }}"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusCfg['dot'] }}"></span>
                                        {{ $statusCfg['label'] }}
                                        <span class="material-symbols-outlined text-[13px] ml-0.5">expand_more</span>
                                    </button>
                                    <div class="status-menu hidden absolute left-0 top-full mt-1 z-20 w-44 rounded-xl border border-neutral-200 bg-white shadow-lg overflow-hidden py-1">
                                        <form action="{{ url('/admin/registrations/'.$r->id.'/status') }}" method="POST">
                                            @csrf
                                            <button name="status" value="pending" type="submit"
                                                class="w-full flex items-center gap-2.5 px-3 py-2 text-sm hover:bg-amber-50 transition-colors {{ $r->status === 'pending' ? 'bg-amber-50' : '' }}">
                                                <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                                                <span class="font-medium text-amber-700">Chờ xác nhận</span>
                                                @if($r->status === 'pending') <span class="material-symbols-outlined text-[14px] ml-auto text-amber-600">check</span> @endif
                                            </button>
                                            <button name="status" value="confirmed" type="submit"
                                                class="w-full flex items-center gap-2.5 px-3 py-2 text-sm hover:bg-green-50 transition-colors {{ $r->status === 'confirmed' ? 'bg-green-50' : '' }}">
                                                <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                                                <span class="font-medium text-green-700">Đã xác nhận</span>
                                                @if($r->status === 'confirmed') <span class="material-symbols-outlined text-[14px] ml-auto text-green-600">check</span> @endif
                                            </button>
                                            <div class="my-1 border-t border-neutral-100"></div>
                                            <button name="status" value="cancelled" type="submit"
                                                class="w-full flex items-center gap-2.5 px-3 py-2 text-sm hover:bg-red-50 transition-colors {{ $r->status === 'cancelled' ? 'bg-red-50' : '' }}">
                                                <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                                                <span class="font-medium text-red-600">Đã hủy</span>
                                                @if($r->status === 'cancelled') <span class="material-symbols-outlined text-[14px] ml-auto text-red-500">check</span> @endif
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td class="pr-6 py-4 text-center">
                                <a
                                    href="{{ url('/admin/registrations/'.$r->id.'/edit') }}"
                                    class="inline-flex p-2 hover:bg-blue-100 rounded-lg transition-colors cursor-pointer hover:scale-110"
                                    title="Sửa"
                                >
                                    <span class="material-symbols-outlined text-lg" style="color: #2563eb;">edit</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-12 text-center text-sm text-neutral-700/80" colspan="11">
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

    <script>
        let autoCloseTimer = null;

        function startAutoCloseTimer(details) {
            clearTimeout(autoCloseTimer);
            autoCloseTimer = setTimeout(() => {
                details.open = false;
            }, 3000);
        }

        function clearAutoCloseTimer() {
            clearTimeout(autoCloseTimer);
        }

        document.querySelectorAll('details.phone-dropdown').forEach(details => {
            details.addEventListener('toggle', function() {
                if (this.open) {
                    document.querySelectorAll('details.phone-dropdown').forEach(other => {
                        if (other !== this) other.open = false;
                    });
                    startAutoCloseTimer(this);
                } else {
                    clearAutoCloseTimer();
                }
            });
        });

        document.addEventListener('click', function(event) {
            const isClickInside = event.target.closest('details.phone-dropdown');
            if (!isClickInside) {
                setTimeout(() => {
                    document.querySelectorAll('details.phone-dropdown[open]').forEach(details => {
                        details.open = false;
                    });
                }, 200);
            } else {
                const openDetails = document.querySelector('details.phone-dropdown[open]');
                if (openDetails) {
                    startAutoCloseTimer(openDetails);
                }
            }
        });

        function applyFilters() {
            const session = document.getElementById('sessionFilter').value;
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('searchFilter').value;
            let url = new URL('{{ url("/admin/registrations") }}');
            if (session) url.searchParams.set('session_id', session);
            if (status) url.searchParams.set('status', status);
            if (search) url.searchParams.set('search', search);
            
            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                const newTable = newDoc.querySelector('table');
                const newPagination = newDoc.querySelector('.mt-4');
                
                if (newTable) {
                    document.querySelector('table').replaceWith(newTable);
                }
                if (newPagination) {
                    document.querySelector('.mt-4').replaceWith(newPagination);
                }
                
                document.querySelectorAll('details.phone-dropdown').forEach(details => {
                    details.addEventListener('toggle', function() {
                        if (this.open) {
                            document.querySelectorAll('details.phone-dropdown').forEach(other => {
                                if (other !== this) other.open = false;
                            });
                            startAutoCloseTimer(this);
                        } else {
                            clearAutoCloseTimer();
                        }
                    });
                });
            });
        }
    </script>
    <style>
        .phone-hover-group:hover .phone-actions,
        .phone-hover-group .phone-actions:hover {
            display: flex !important;
        }
    </style>
    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.status-badge-btn');
            if (btn) {
                e.stopPropagation();
                const menu = btn.closest('.status-dropdown-wrap').querySelector('.status-menu');
                document.querySelectorAll('.status-menu').forEach(m => { if (m !== menu) m.classList.add('hidden'); });
                menu.classList.toggle('hidden');
                return;
            }
            document.querySelectorAll('.status-menu').forEach(m => m.classList.add('hidden'));
        });
    </script>
@endsection

