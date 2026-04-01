@extends('layouts.app', ['title' => 'Admin – Sửa mẫu lịch chiếu'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Sửa mẫu lịch chiếu</h1>
                <p class="mt-1 text-sm text-neutral-600">Cập nhật mẫu lịch chiếu cho {{ $template->venue->name }}.</p>
                <p class="mt-0.5 text-xs text-neutral-400">Ngày tạo: {{ $template->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ url('/admin/sessions') }}" class="rounded-xl border border-neutral-300 bg-white px-4 py-2 text-sm font-medium hover:bg-neutral-50">
                Quay lại
            </a>
        </div>

        <div class="mt-6 rounded-xl border border-neutral-200 bg-white p-6 shadow-sm">
            <form method="post" action="{{ url('/admin/templates/' . $template->id) }}" id="templateForm">
                @csrf
                @method('PUT')
                @include('admin.templates.form')

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="rounded-xl bg-neutral-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-neutral-800">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
