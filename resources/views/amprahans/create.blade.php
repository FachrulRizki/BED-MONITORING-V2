@extends('layouts.app')

@section('title', 'Buat Laporan Amprahan')
@section('page-title', 'Buat Laporan Amprahan')
@section('page-description', 'Masukkan data shift, pasien, dan dokumentasi gambar dalam pola form yang seragam.')

@section('content')
<div class="mb-6 flex justify-start">
    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button :href="route('amprahans.index')" variant="secondary">
            <i data-lucide="arrow-left" class="size-4"></i>
            Kembali
        </x-ui.button>
    </div>
</div>

<x-ui.card title="Form Laporan" description="Laporan dibuat menjelang pergantian shift, jadi isi petugas shift saat ini dan petugas shift berikutnya.">
    <form action="{{ route('amprahans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid gap-5 md:grid-cols-2">
            <x-ui.form-field label="Nama Ruangan" for="room_id" :error="$errors->first('room_id')" required>
                <x-ui.select id="room_id" name="room_id" required>
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>{{ $room->name }}</option>
                    @endforeach
                </x-ui.select>
            </x-ui.form-field>

            <x-ui.form-field label="Jam Amprahan" for="report_time" :error="$errors->first('report_time')" required>
                <x-ui.input type="time" id="report_time" name="report_time" :value="old('report_time')" step="60" required />
            </x-ui.form-field>

            <div class="md:col-span-2 rounded-2xl border border-border bg-card-grey/40 p-5">
                <div class="mb-4">
                    <p class="text-sm font-semibold text-foreground">Informasi Shift dan Petugas</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <x-ui.form-field label="Shift Saat Ini" for="shift" :error="$errors->first('shift')" required>
                        <x-ui.select id="shift" name="shift" required>
                            <option value="">-- Pilih Shift Saat Ini --</option>
                            <option value="pagi" @selected(old('shift') === 'pagi')>Pagi</option>
                            <option value="sore" @selected(old('shift') === 'sore')>Sore</option>
                            <option value="malam" @selected(old('shift') === 'malam')>Malam</option>
                        </x-ui.select>
                    </x-ui.form-field>

                    <x-ui.form-field label="Shift Berikutnya" for="next_shift" :error="$errors->first('next_shift')" required>
                        <x-ui.select id="next_shift" name="next_shift" required>
                            <option value="">-- Pilih Shift Berikutnya --</option>
                            <option value="pagi" @selected(old('next_shift') === 'pagi')>Pagi</option>
                            <option value="sore" @selected(old('next_shift') === 'sore')>Sore</option>
                            <option value="malam" @selected(old('next_shift') === 'malam')>Malam</option>
                        </x-ui.select>
                    </x-ui.form-field>

                    <x-ui.form-field label="Petugas Shift Saat Ini" for="officer_name" :error="$errors->first('officer_name')" hint="Isi nama petugas yang sedang bertugas sekarang." required>
                        <x-ui.input id="officer_name" name="officer_name" :value="old('officer_name')" placeholder="Nama petugas shift saat ini" required />
                    </x-ui.form-field>

                    <x-ui.form-field label="Petugas Shift Berikutnya" for="next_officer_name" :error="$errors->first('next_officer_name')" hint="Isi nama petugas yang akan menerima shift berikutnya." required>
                        <x-ui.input id="next_officer_name" name="next_officer_name" :value="old('next_officer_name')" placeholder="Nama petugas shift berikutnya" required />
                    </x-ui.form-field>
                </div>
            </div>

            <div class="md:col-span-2 rounded-2xl border border-border bg-card-grey/40 p-5">
                <div class="mb-4">
                    <p class="text-sm font-semibold text-foreground">Jumlah Pasien</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <x-ui.form-field label="Jumlah Pasien Pria" for="male_patient_count" :error="$errors->first('male_patient_count')" required>
                        <x-ui.input type="number" id="male_patient_count" name="male_patient_count" :value="old('male_patient_count', 0)" min="0" required />
                    </x-ui.form-field>

                    <x-ui.form-field label="Jumlah Pasien Wanita" for="female_patient_count" :error="$errors->first('female_patient_count')" required>
                        <x-ui.input type="number" id="female_patient_count" name="female_patient_count" :value="old('female_patient_count', 0)" min="0" required />
                    </x-ui.form-field>
                </div>
            </div>

            <div class="md:col-span-2">
                <x-ui.form-field label="Rencana Tindakan" for="action_plan" :error="$errors->first('action_plan')" hint="Opsional. Isi rencana tindak lanjut atau catatan serah terima.">
                    <x-ui.textarea id="action_plan" name="action_plan" rows="5" placeholder="Contoh: observasi ulang 30 menit sebelum pergantian shift, koordinasi dengan ruangan penerima, siapkan kebutuhan bed tambahan.">{{ old('action_plan') }}</x-ui.textarea>
                </x-ui.form-field>
            </div>

            <div class="md:col-span-2">
                <x-ui.form-field label="Gambar Dokumentasi" for="image" :error="$errors->first('image')" hint="Format JPG, JPEG, PNG. Maksimal 5 MB." required>
                    <x-ui.input type="file" id="image" name="image" accept="image/jpeg,image/png" required class="file:mr-4 file:rounded-xl file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary" />
                </x-ui.form-field>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <x-ui.button :href="route('amprahans.index')" variant="secondary">Batal</x-ui.button>
            <x-ui.button variant="primary" type="submit">
                <i data-lucide="save" class="size-4"></i>
                Simpan Laporan
            </x-ui.button>
        </div>
    </form>
</x-ui.card>
@endsection
