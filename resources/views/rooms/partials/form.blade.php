@php
    $room = $room ?? null;
@endphp

<x-ui.card :title="$room ? 'Perbarui data ruangan' : 'Tambahkan ruangan baru'" description="Gunakan struktur form yang sama agar data kapasitas dan okupansi tetap konsisten.">
    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @if (($method ?? 'POST') !== 'POST')
            @method($method)
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <x-ui.form-field label="Nama Ruangan" for="name" :error="$errors->first('name')" required>
                    <x-ui.input id="name" name="name" :value="old('name', $room?->name)" placeholder="Contoh: Ruang Mawar" required />
                </x-ui.form-field>
            </div>

            <x-ui.form-field label="Kapasitas Bed Laki-laki" for="male_capacity" :error="$errors->first('male_capacity')" required>
                <x-ui.input type="number" id="male_capacity" name="male_capacity" :value="old('male_capacity', $room?->male_capacity ?? 0)" min="0" required />
            </x-ui.form-field>

            <x-ui.form-field label="Kapasitas Bed Perempuan" for="female_capacity" :error="$errors->first('female_capacity')" required>
                <x-ui.input type="number" id="female_capacity" name="female_capacity" :value="old('female_capacity', $room?->female_capacity ?? 0)" min="0" required />
            </x-ui.form-field>

            @if ($room)
                <x-ui.form-field label="Bed Laki-laki Terisi" for="male_occupied" :error="$errors->first('male_occupied')">
                    <x-ui.input type="number" id="male_occupied" name="male_occupied" :value="old('male_occupied', $room->male_occupied)" min="0" />
                </x-ui.form-field>

                <x-ui.form-field label="Bed Perempuan Terisi" for="female_occupied" :error="$errors->first('female_occupied')">
                    <x-ui.input type="number" id="female_occupied" name="female_occupied" :value="old('female_occupied', $room->female_occupied)" min="0" />
                </x-ui.form-field>
            @endif
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <x-ui.button :href="route('rooms.index')" variant="secondary">Batal</x-ui.button>
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="{{ $room ? 'save' : 'plus' }}" class="size-4"></i>
                {{ $submitLabel }}
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
