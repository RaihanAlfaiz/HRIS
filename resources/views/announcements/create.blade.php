<x-layouts.app :title="'Buat Pengumuman'">
    <div class="mx-auto max-w-2xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ isset($announcement) ? 'Edit Pengumuman' : 'Buat Pengumuman' }}</h1>
        </div>

        <form method="POST" action="{{ isset($announcement) ? route('announcements.update', $announcement) : route('announcements.store') }}"
              class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
            @csrf
            @if(isset($announcement)) @method('PUT') @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required
                       class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none" placeholder="Judul pengumuman...">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                <textarea name="content" rows="6" required class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none"
                          placeholder="Isi pengumuman...">{{ old('content', $announcement->content ?? '') }}</textarea>
                @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas</label>
                    <select name="priority" class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                        @foreach(['low' => 'Rendah', 'normal' => 'Normal', 'high' => 'Penting', 'urgent' => 'Urgent'] as $val => $label)
                            <option value="{{ $val }}" {{ old('priority', $announcement->priority ?? 'normal') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publish</label>
                    <input type="date" name="publish_date" value="{{ old('publish_date', isset($announcement) && $announcement->publish_date ? $announcement->publish_date->format('Y-m-d') : '') }}"
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berakhir</label>
                    <input type="date" name="expire_date" value="{{ old('expire_date', isset($announcement) && $announcement->expire_date ? $announcement->expire_date->format('Y-m-d') : '') }}"
                           class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-gray-50/50 px-4 py-2.5 text-sm transition focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:outline-none">
                </div>
            </div>

            @if(isset($announcement))
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                <label for="is_active" class="text-sm text-gray-700">Aktif</label>
            </div>
            @endif

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-primary-700 transition">
                    {{ isset($announcement) ? 'Update' : 'Publish' }}
                </button>
                <a href="{{ route('announcements.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>
