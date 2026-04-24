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
            <option value="Di atas 1 Miliar" {{ old('modal_dasar') == 'Di atas 1 Miliar' ? 'selected' : '' }}>Di atas 1 Miliar</option>
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
    <textarea name="notes" class="form-control" rows="3" placeholder="Tuliskan nama perusahaan yang diinginkan, bidang usaha, atau pertanyaan lainnya…">{{ old('notes') }}</textarea>
</div>

{{-- Upload Dokumen Wajib --}}
<p class="form-section-title"><i class="fa-solid fa-upload me-1"></i> Lampiran Dokumen (Wajib)</p>
<p class="text-muted small mb-3">Semua dokumen wajib diunggah. Format: JPG, PNG, PDF (maks. 5MB)</p>

<div class="row g-3 mb-4">
    @php
        $uploads = [
            ['name' => 'ktp_direktur',    'label' => 'KTP Direktur',              'icon' => 'fa-id-card'],
            ['name' => 'npwp_direktur',   'label' => 'NPWP Direktur',             'icon' => 'fa-file-invoice'],
        ];
    @endphp

    @foreach($uploads as $upload)
        <div class="col-12">
            <label class="form-label">{{ $upload['label'] }} <span class="req">*</span></label>
            <div class="file-upload-box @error($upload['name']) border-danger-upload @enderror">
                <input type="file" name="{{ $upload['name'] }}" accept=".jpg,.jpeg,.png,.pdf" required
                       onchange="showFileName(this, '{{ $upload['name'] }}Name')">
                <div class="upload-icon"><i class="fa-solid {{ $upload['icon'] }}"></i></div>
                <div class="upload-label">Klik atau seret file ke sini</div>
                <div class="upload-hint">JPG, PNG, PDF (maks. 5MB)</div>
                <div class="file-name" id="{{ $upload['name'] }}Name"></div>
            </div>
            @error($upload['name'])
                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
            @enderror
        </div>
    @endforeach
</div>
