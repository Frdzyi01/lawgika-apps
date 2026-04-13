@extends('layout.app')

@section('content')

{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative" style="background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%); padding-top: 180px; padding-bottom: 80px;">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika Blog</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Explore Latest News</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Update terbaru seputar hukum, bisnis, dan perizinan langsung dari pakar Lawgika.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                 <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Berita & Artikel</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- Blog Grid Section --}}
<section class="blog-grid-area py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            @forelse($beritas as $item)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('berita.show', $item->slug) }}" class="berita-card-link text-decoration-none" aria-label="Baca berita: {{ $item->judul }}">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden blog-card text-dark">
                        {{-- Thumbnail --}}
                        <div class="position-relative overflow-hidden" style="height: 250px;">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" class="w-100 h-100 object-fit-cover hover-scale transition-all" alt="{{ $item->judul }}">
                            @else
                                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white-50">
                                    <i class="fas fa-image fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Card Body --}}
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="text-primary fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">{{ $item->kategori }}</span>
                                <span class="text-muted" style="font-size: 0.8rem;">
                                    <i class="far fa-calendar-alt me-1"></i> 
                                    {{ strtoupper($item->published_at->translatedFormat('F d, Y')) }}
                                </span>
                            </div>
                            
                            <h4 class="card-title fw-bold mb-3 hover-primary-heading">
                                {{ $item->judul }}
                            </h4>
                            
                            <p class="card-text text-muted mb-auto">
                                {{ $item->excerpt ?: Str::limit(strip_tags($item->konten), 100) }}
                            </p>
                            
                            <div class="mt-4 pt-3 border-top d-flex align-items-center">
                                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                    {{ substr(strtoupper($item->penulis), 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold fs-6">{{ $item->penulis }} <span class="text-muted fw-normal">· {{ $item->jabatan_penulis }}</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 py-5 text-center">
                <i class="far fa-newspaper fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Belum ada berita yang diterbitkan.</h4>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $beritas->links('pagination::bootstrap-5') }}
        </div>
    </div>
</section>

<style>
    .berita-card-link {
        display: block;
        height: 100%;
        color: inherit;
        transition: transform 0.3s ease;
    }
    .blog-card {
        transition: box-shadow 0.3s ease;
    }
    .hover-scale {
        transition: transform 0.4s ease;
    }
    
    @media (hover: hover) {
        .berita-card-link:hover {
            transform: translateY(-5px);
        }
        .berita-card-link:hover .blog-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
        }
        .berita-card-link:hover .hover-scale {
            transform: scale(1.05);
        }
        .berita-card-link:hover .hover-primary-heading {
            color: #dc3545 !important;
            transition: color 0.2s ease;
        }
    }
    
    @media (hover: none) {
        .berita-card-link:active {
            transform: scale(0.98);
        }
    }
</style>
@endsection
