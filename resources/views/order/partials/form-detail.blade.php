{{-- Data Perusahaan & Direktur --}}
<p class="form-section-title"><i class="fa-solid fa-building me-1"></i> Informasi Perusahaan & Direktur</p>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Nama Lengkap Direktur <span class="req">*</span></label>
        <input type="text" name="director_name" value="{{ old('director_name') }}" class="form-control @error('director_name') is-invalid @enderror" required>
        @error('director_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Nomor Telepon Direktur <span class="req">*</span></label>
        <input type="text" name="director_phone" value="{{ old('director_phone') }}" class="form-control @error('director_phone') is-invalid @enderror" required>
        @error('director_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Perusahaan <span class="req">*</span></label>
        <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control @error('company_name') is-invalid @enderror" required>
        @error('company_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    @if(isset($service) && $service === 'pendirian-pt')
    <div class="col-md-6">
        <label class="form-label">Modal Dasar <span class="req">*</span></label>
        <select name="modal_dasar" class="form-control @error('modal_dasar') is-invalid @enderror" required>
            <option value="">-- Pilih Modal Dasar --</option>
            <option value="Di bawah 1 Miliar" {{ old('modal_dasar') == 'Di bawah 1 Miliar' ? 'selected' : '' }}>Di bawah 1 Miliar</option>
            <option value="Di atas 1 Miliar"  {{ old('modal_dasar') == 'Di atas 1 Miliar'  ? 'selected' : '' }}>Di atas 1 Miliar</option>
        </select>
        @error('modal_dasar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    @endif
    <div class="col-md-6">
        <label class="form-label">Nama PIC <span class="req">*</span></label>
        <input type="text" name="pic_name" value="{{ old('pic_name') }}" class="form-control @error('pic_name') is-invalid @enderror" required>
        @error('pic_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Nomor Telepon PIC <span class="req">*</span></label>
        <input type="text" name="pic_phone" value="{{ old('pic_phone') }}" class="form-control @error('pic_phone') is-invalid @enderror" required>
        @error('pic_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Alamat Email Perusahaan <span class="req">*</span></label>
        <input type="email" name="company_email" value="{{ old('company_email') }}" class="form-control @error('company_email') is-invalid @enderror" required>
        @error('company_email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Alamat Operasional / Direktur <span class="req">*</span></label>
        <textarea name="operational_address" class="form-control" rows="2" required>{{ old('operational_address') }}</textarea>
        @error('operational_address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Bidang Usaha <span class="req">*</span></label>
        <input type="text" name="business_field" value="{{ old('business_field') }}" class="form-control @error('business_field') is-invalid @enderror" required>
        @error('business_field')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

{{-- Catatan Tambahan --}}
<p class="form-section-title"><i class="fa-solid fa-note-sticky me-1"></i> Catatan Tambahan</p>
<div class="mb-4">
    <label class="form-label">Catatan / Pertanyaan <span class="opt-badge">Opsional</span></label>
    <textarea name="notes" class="form-control" rows="3"
              placeholder="Tuliskan nama perusahaan yang diinginkan, bidang usaha, atau pertanyaan lainnya…">{{ old('notes') }}</textarea>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- LAMPIRAN DOKUMEN — DYNAMIC dari database (document_requirements)       --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<p class="form-section-title"><i class="fa-solid fa-upload me-1"></i> Lampiran Dokumen (Wajib)</p>
<p class="text-muted small mb-3">Semua dokumen wajib diunggah. Format: JPG, PNG, PDF (maks. 5MB)</p>

@if(isset($requirements) && $requirements->count() > 0)

    @php
        /**
         * Icon mapping per document_type — fallback ke fa-file-alt jika tidak ada.
         */
        $iconMap = [
            'KTP_DIREKTUR'        => 'fa-id-card',
            'NPWP_DIREKTUR'       => 'fa-file-invoice',
            'KTP_PEMEGANG_SAHAM'  => 'fa-id-card',
            'NPWP_PEMEGANG_SAHAM' => 'fa-file-invoice',
            'KTP_KOMISARIS'       => 'fa-id-card',
            'NPWP_KOMISARIS'      => 'fa-file-invoice',
            'KTP_SEKUTU_AKTIF'    => 'fa-id-card',
            'NPWP_SEKUTU_AKTIF'   => 'fa-file-invoice',
            'KTP_SEKUTU_PASIF'    => 'fa-id-card',
            'NPWP_SEKUTU_PASIF'   => 'fa-file-invoice',
            'KTP_PEMBINA'         => 'fa-id-card',
            'NPWP_PEMBINA'        => 'fa-file-invoice',
            'KTP_PENGURUS'        => 'fa-id-card',
            'NPWP_PENGURUS'       => 'fa-file-invoice',
            'KTP_PENGAWAS'        => 'fa-id-card',
            'NPWP_PENGAWAS'       => 'fa-file-invoice',
            'KTP_SEKUTU'          => 'fa-id-card',
            'NPWP_SEKUTU'         => 'fa-file-invoice',
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach($requirements as $req)
            @php
                $fieldKey  = 'documents.' . $req->document_type;
                $inputName = 'documents[' . $req->document_type . '][]';
                $inputId   = 'doc_' . Str::slug($req->document_type);
                $icon      = $iconMap[$req->document_type] ?? 'fa-file-alt';
                $isRequired = $req->min_required > 0;

                // Tampilkan pesan error jika ada
                $hasError = $errors->has($fieldKey) || $errors->has($fieldKey . '.0');
            @endphp
            <div class="col-12">
                <label class="form-label">
                    {{ $req->label }}
                    @if($isRequired)
                        <span class="req">*</span>
                    @else
                        <span class="opt-badge">Opsional</span>
                    @endif
                    @if($req->min_required > 1)
                        <span class="opt-badge" style="background:#fff3cd;color:#856404">Min. {{ $req->min_required }} file</span>
                    @endif
                </label>

                <div class="file-upload-box {{ $hasError ? 'border-danger-upload' : '' }}"
                     id="box_{{ $inputId }}"
                     onclick="document.getElementById('{{ $inputId }}').click()"
                     ondragover="event.preventDefault(); this.classList.add('drag-over')"
                     ondragleave="this.classList.remove('drag-over')"
                     ondrop="handleDrop(event, '{{ $inputId }}', '{{ $req->document_type }}')">

                    {{-- Hidden actual input --}}
                    <input type="file"
                           id="{{ $inputId }}"
                           name="{{ $inputName }}"
                           accept=".jpg,.jpeg,.png,.pdf"
                           {{ $req->min_required > 1 ? 'multiple' : '' }}
                           style="display:none"
                           onchange="handleFileSelect(this, '{{ $req->document_type }}')">

                    <div class="upload-icon"><i class="fa-solid {{ $icon }}"></i></div>
                    <div class="upload-label">Klik atau seret file ke sini</div>
                    <div class="upload-hint">JPG, PNG, PDF (maks. 5MB{{ $req->min_required > 1 ? ', bisa multi file' : '' }})</div>
                    <div id="preview_{{ $req->document_type }}" class="file-name mt-2"></div>
                </div>

                @if($hasError)
                    @error($fieldKey)
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                    @error($fieldKey . '.0')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                @endif
            </div>
        @endforeach
    </div>

@else
    {{-- Fallback jika service tidak punya requirements di DB --}}
    <div class="row g-3 mb-4">
        @php
            $fallbackUploads = [
                ['type' => 'KTP_DIREKTUR',  'label' => 'KTP Direktur',  'icon' => 'fa-id-card'],
                ['type' => 'NPWP_DIREKTUR', 'label' => 'NPWP Direktur', 'icon' => 'fa-file-invoice'],
            ];
        @endphp
        @foreach($fallbackUploads as $up)
            @php $inputId = 'doc_' . Str::slug($up['type']); @endphp
            <div class="col-12">
                <label class="form-label">{{ $up['label'] }} <span class="req">*</span></label>
                <div class="file-upload-box @error('documents.' . $up['type']) border-danger-upload @enderror"
                     onclick="document.getElementById('{{ $inputId }}').click()">
                    <input type="file" id="{{ $inputId }}"
                           name="documents[{{ $up['type'] }}][]"
                           accept=".jpg,.jpeg,.png,.pdf" required
                           style="display:none"
                           onchange="handleFileSelect(this, '{{ $up['type'] }}')">
                    <div class="upload-icon"><i class="fa-solid {{ $up['icon'] }}"></i></div>
                    <div class="upload-label">Klik atau seret file ke sini</div>
                    <div class="upload-hint">JPG, PNG, PDF (maks. 5MB)</div>
                    <div id="preview_{{ $up['type'] }}" class="file-name mt-2"></div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- Script: file preview & drag-drop                                       --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<style>
    .file-upload-box.drag-over {
        border-color: var(--primary) !important;
        background: #fdf5f7 !important;
    }
    .file-preview-list {
        margin-top: 10px;
        list-style: none;
        padding: 0;
    }
    .file-preview-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .82rem;
        color: #16a34a;
        font-weight: 600;
        padding: 3px 0;
    }
    .file-preview-list li i { color: #16a34a; }
</style>

<script>
/**
 * Preview file yang dipilih — ditampilkan di bawah upload box.
 */
function handleFileSelect(input, docType) {
    const previewEl = document.getElementById('preview_' + docType);
    if (!input.files || input.files.length === 0) {
        previewEl.innerHTML = '';
        return;
    }
    let html = '<ul class="file-preview-list">';
    Array.from(input.files).forEach(f => {
        html += `<li><i class="fa-solid fa-circle-check"></i> ${f.name}</li>`;
    });
    html += '</ul>';
    previewEl.innerHTML = html;
}

/**
 * Drag & drop support.
 */
function handleDrop(event, inputId, docType) {
    event.preventDefault();
    const box   = event.currentTarget;
    box.classList.remove('drag-over');

    const input = document.getElementById(inputId);
    const dt    = event.dataTransfer;
    if (!dt || !dt.files) return;

    // Assign dropped files ke input (via DataTransfer workaround)
    try {
        input.files = dt.files;
    } catch (e) {
        // Fallback: browser tidak support assignment langsung
    }
    handleFileSelect(input, docType);
}
</script>
