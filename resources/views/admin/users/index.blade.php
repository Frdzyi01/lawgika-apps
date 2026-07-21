@extends('layouts-admin.admin')

@section('title', 'Master Client - Lawgika Admin')

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
                    Master Client
                </li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="card radius-10 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3 flex-wrap gap-2">
            <h6 class="mb-0">
                <ion-icon name="people-outline" class="align-middle"></ion-icon> Master Client
            </h6>
            @if(auth()->user()->isSPV())
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm ms-auto d-flex align-items-center gap-1">
                <ion-icon name="person-add-outline"></ion-icon> Tambah Client
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

        {{-- Search & Filter Bar --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><ion-icon name="search-outline"></ion-icon></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama PT, PIC, telepon, email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="admin1" {{ request('role') == 'admin1' ? 'selected' : '' }}>Admin Order</option>
                    <option value="admin2" {{ request('role') == 'admin2' ? 'selected' : '' }}>Admin Konten</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <ion-icon name="filter-outline"></ion-icon> Filter
                </button>
            </div>
            @if(request('search') || request('role'))
            <div class="col-md-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                    <ion-icon name="refresh-outline"></ion-icon> Reset
                </a>
            </div>
            @endif
        </form>

        {{-- Stats Row --}}
        <div class="row g-2 mb-3">
            <div class="col-auto">
                <span class="badge bg-primary fs-6 px-3 py-2">
                    <ion-icon name="people-outline"></ion-icon> Total: {{ $users->count() }} Data
                </span>
            </div>
            <div class="col-auto">
                <span class="badge bg-success fs-6 px-3 py-2">
                    Customer: {{ $users->where('role', 'customer')->count() }}
                </span>
            </div>
        </div>

        <div class="table-responsive mt-2">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Perusahaan / Nama</th>
                        <th>PIC</th>
                        <th>Kontak</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                        <th>Paket Jasa</th>
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
                                    <h6 class="mb-0 product-title">{{ $user->company_name ?: $user->name }}</h6>
                                    @if($user->company_name)
                                    <p class="mb-0 product-category small text-muted">{{ $user->name }}</p>
                                    @endif
                                    @if($user->business_type)
                                    <small class="text-muted"><ion-icon name="briefcase-outline" class="align-middle"></ion-icon> {{ $user->business_type }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="d-block">{{ $user->pic_name ?: $user->name }}</span>
                        </td>
                        <td>
                            <small class="d-block text-dark fw-bold">{{ $user->email }}</small>
                            <small class="text-muted">{{ $user->phone ?? '-' }}</small>
                        </td>
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
                        <td>{{ $user->created_at->format('d M Y') }}</td>
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
                                                <h5 class="modal-title">Detail Layanan - {{ $user->company_name ?: $user->name }}</h5>
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
                            <div class="d-flex align-items-center gap-2 fs-6">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Profil">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>
                                @if(auth()->user()->isSPV())
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit Client">
                                    <ion-icon name="pencil-outline"></ion-icon>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus data client ini? Semua data terkait akan ikut dihapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0 border-0 bg-transparent text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hapus Client">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data client.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
