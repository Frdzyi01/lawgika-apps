@php
    $canApproveDocs = auth()->user()->canApproveOrders();
    $editDocType = ($useTypeFallback ?? false)
        ? ($doc->document_type ?? $doc->type ?? '')
        : ($doc->document_type ?? '');
@endphp
<div class="d-flex align-items-center gap-2 flex-wrap">
    @if(!$order->trashed())
        <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-1" style="font-size:.78rem;"
            onclick="openEditDocumentModal('{{ route('admin.documents.update', $doc->id) }}', '{{ addslashes($doc->original_name) }}', '{{ addslashes($editDocType) }}')"
            title="Edit / Ganti Dokumen"
            aria-label="Edit / Ganti Dokumen">
            <i class="fa fa-pen-to-square"></i>
        </button>
    @endif
    @if(in_array($doc->status, ['approved', 'verified']))
        <span class="badge bg-success px-2.5 py-1.5" style="font-size:.78rem; color:#fff !important;">
            <i class="fa fa-circle-check me-1"></i>Disetujui
        </span>
        @if($canApproveDocs)
            <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1" style="font-size:.78rem;"
                onclick="openRejectModal('{{ route('admin.documents.reject', $doc->id) }}', '{{ addslashes($doc->original_name) }}')"
                title="Tolak Dokumen">
                <i class="fa fa-xmark me-1"></i>Tolak
            </button>
        @endif
    @elseif($doc->status === 'rejected')
        <span class="badge bg-danger px-2.5 py-1.5" style="font-size:.78rem; color:#fff !important;">
            <i class="fa fa-circle-xmark me-1"></i>Ditolak
        </span>
        @if($canApproveDocs)
            <form action="{{ route('admin.documents.approve', $doc->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm px-2.5 py-1" style="font-size:.78rem;"
                    onclick="return confirm('Setujui dokumen ini?')">
                    <i class="fa fa-check me-1"></i>Setujui
                </button>
            </form>
        @endif
    @else
        <span class="badge bg-warning text-dark px-2.5 py-1.5" style="font-size:.78rem;">
            <i class="fa fa-clock me-1"></i>Menunggu Verifikasi
        </span>
        @if($canApproveDocs)
            <form action="{{ route('admin.documents.approve', $doc->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm px-2.5 py-1" style="font-size:.78rem;"
                    onclick="return confirm('Setujui dokumen ini?')">
                    <i class="fa fa-check me-1"></i>Setujui
                </button>
            </form>
            <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1" style="font-size:.78rem;"
                onclick="openRejectModal('{{ route('admin.documents.reject', $doc->id) }}', '{{ addslashes($doc->original_name) }}')">
                <i class="fa fa-xmark me-1"></i>Tolak
            </button>
        @endif
    @endif
</div>
