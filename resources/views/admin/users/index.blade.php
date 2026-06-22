@extends('layouts-admin.admin')

@section('title', 'Manajemen Akun - Lawgika Admin')

@section('content')
<!--start breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Dashboard</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Data Akun Client
                </li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="card radius-10 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-0">Manajemen Akun</h6>
            @if(auth()->user()->isSPV())
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm ms-auto d-flex align-items-center gap-1">
                <ion-icon name="person-add-outline"></ion-icon> Tambah Akun
            </a>
            @endif
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive mt-2">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Kontak</th>
                        <th>Tanggal Daftar</th>
                        <th>Paket Jasa yang Dibeli</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-box border bg-light rounded d-flex justify-content-center align-items-center" style="width: 45px; height: 45px">
                                    <ion-icon name="person-outline" class="fs-4 text-primary"></ion-icon>
                                </div>
                                <div>
                                    <h6 class="mb-0 product-title">{{ $user->name }}</h6>
                                    <p class="mb-0 product-category">{{ $user->company_name ?? 'Personal' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="d-block text-dark fw-bold">{{ $user->email }}</small>
                            <small class="text-muted">{{ $user->phone ?? '-' }}</small>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        {{-- Badge Role --}}
                        <td>
                            @php
                                $roleLabel = $user->roleLabel();
                                $badgeClass = match($user->role) {
                                    'admin', 'spv' => 'bg-gradient-purple',
                                    'admin1'       => 'bg-gradient-info',
                                    'admin2'       => 'bg-gradient-success',
                                    default        => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} text-white">{{ $roleLabel }}</span>
                        </td>
                        <td>
                            @php
                                $purchasedItems = collect();
                                
                                foreach($user->orders as $o) {
                                    $name = $o->service_name ?: ($o->service->name ?? 'Jasa Unknown');
                                    $isApproved = ($o->payment_status === 'verified' || in_array($o->status, ['approved', 'processing', 'completed', 'verified']));
                                    $expiry = $isApproved ? $o->updated_at->addYear()->format('d M Y') : 'Menunggu Approval';
                                    $purchasedItems->push([
                                        'label' => $name,
                                        'start' => $o->created_at->format('d M Y'),
                                        'expiry' => $expiry,
                                        'status' => $isApproved
                                    ]);
                                }

                                foreach($user->meetingRoomBookings as $mb) {
                                    $isApproved = ($mb->payment_status === 'approved');
                                    $expiry = $isApproved ? $mb->updated_at->addYear()->format('d M Y') : 'Menunggu Approval';
                                    $purchasedItems->push([
                                        'label' => 'Meeting Room – ' . ucfirst($mb->package ?? 'Regular'),
                                        'start' => $mb->created_at->format('d M Y'),
                                        'expiry' => $expiry,
                                        'status' => $isApproved
                                    ]);
                                }

                                foreach($user->podcastRoomBookings as $pb) {
                                    $isApproved = ($pb->payment_status === 'approved');
                                    $expiry = $isApproved ? $pb->updated_at->addYear()->format('d M Y') : 'Menunggu Approval';
                                    $purchasedItems->push([
                                        'label' => 'Podcast Room – ' . ucfirst($pb->package ?? '2 Jam'),
                                        'start' => $pb->created_at->format('d M Y'),
                                        'expiry' => $expiry,
                                        'status' => $isApproved
                                    ]);
                                }
                            @endphp
                            
                            @if($purchasedItems->count() > 0)
                                <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailServices{{ $user->id }}">
                                    <ion-icon name="eye-outline"></ion-icon> {{ $purchasedItems->count() }} Layanan
                                </button>

                                <!-- Modal Detail Layanan -->
                                <div class="modal fade" id="detailServices{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Layanan - {{ $user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="list-group list-group-flush">
                                                    @foreach($purchasedItems as $item)
                                                        <div class="list-group-item px-0">
                                                            <h6 class="mb-1 text-primary">{{ $item['label'] }}</h6>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <ion-icon name="play-outline" class="align-middle"></ion-icon> 
                                                                    Start: {{ $item['start'] }}
                                                                </small>
                                                                <small class="text-muted">
                                                                    <ion-icon name="calendar-outline" class="align-middle"></ion-icon> 
                                                                    Exp: <span class="{{ $item['status'] ? 'text-danger fw-bold' : '' }}">{{ $item['expiry'] }}</span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">Belum ada pembelian</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Akun">
                                    <ion-icon name="pencil-outline"></ion-icon>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus akun client ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0 border-0 bg-transparent text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hapus Akun">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data client.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
