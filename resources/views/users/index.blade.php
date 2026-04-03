@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-description', 'Kelola akun admin dan petugas yang dapat mengakses sistem.')

@section('content')
<div class="mb-6 flex justify-start">
    <div class="flex flex-wrap items-center gap-3">
        <x-ui.button :href="route('users.create')" variant="primary">
            <i data-lucide="user-plus" class="size-4"></i>
            Tambah Pengguna
        </x-ui.button>
    </div>
</div>

@if ($users->isEmpty())
    <x-ui.empty-state icon="users-round" title="Belum ada pengguna" description="Tambahkan akun admin atau petugas agar mereka bisa mengakses sistem.">
        <x-ui.button :href="route('users.create')" variant="primary">
            <i data-lucide="user-plus" class="size-4"></i>
            Tambah Pengguna
        </x-ui.button>
    </x-ui.empty-state>
@else
    <x-ui.card body-class="p-0">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-border bg-muted/50">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-secondary">Nama</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Username</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Email</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Role</th>
                        <th class="p-4 text-sm font-semibold text-secondary">Dibuat</th>
                        <th class="p-4 text-right text-sm font-semibold text-secondary">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $managedUser)
                        @php
                            $deleteFormId = 'delete-user-'.$managedUser->id;
                            $modalId = 'confirm-delete-user-'.$managedUser->id;
                        @endphp
                        <tr class="border-b border-border last:border-b-0 hover:bg-card-grey/60">
                            <td class="p-4">
                                <div class="font-semibold text-foreground">{{ $managedUser->name }}</div>
                                @if($managedUser->is(auth()->user()))
                                    <div class="mt-1 text-xs text-secondary/80">Akun Anda</div>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-secondary">{{ $managedUser->username }}</td>
                            <td class="p-4 text-sm text-secondary">{{ $managedUser->email }}</td>
                            <td class="p-4">
                                <x-ui.badge :variant="$managedUser->role === 'admin' ? 'primary' : 'neutral'">
                                    {{ ucfirst($managedUser->role) }}
                                </x-ui.badge>
                            </td>
                            <td class="p-4 text-sm text-secondary">{{ $managedUser->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4">
                                <div class="flex items-center justify-end gap-2">
                                    <x-ui.button :href="route('users.edit', $managedUser)" variant="ghost" size="icon" aria-label="Edit {{ $managedUser->name }}">
                                        <i data-lucide="edit-3" class="size-4"></i>
                                    </x-ui.button>

                                    @if(! $managedUser->is(auth()->user()))
                                        <x-ui.button variant="ghost" size="icon" onclick="openModal('{{ $modalId }}')" aria-label="Hapus {{ $managedUser->name }}">
                                            <i data-lucide="trash-2" class="size-4 text-error"></i>
                                        </x-ui.button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @if(! $managedUser->is(auth()->user()))
                            @push('modals')
                                <form id="{{ $deleteFormId }}" action="{{ route('users.destroy', $managedUser) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <x-ui.confirm-modal :name="$modalId" title="Hapus pengguna?" :message="'Akun '.$managedUser->name.' akan dihapus permanen dari sistem.'" :form-id="$deleteFormId" confirm-label="Ya, Hapus" />
                            @endpush
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endif
@endsection
