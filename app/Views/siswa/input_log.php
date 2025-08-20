<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Input Log Aktivitas Harian</h2>
                    <p class="text-muted mb-0">Catat aktivitas magang Anda hari ini dengan detail yang lengkap</p>
                </div>
                <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-journal-plus"></i>
                    Form Input Log Aktivitas
                </div>
                <div class="card-body">
                    <form action="<?= base_url('siswa/save-log') ?>" method="post" enctype="multipart/form-data" id="logForm" data-loader="page">
                        <?= csrf_field() ?>
                        
                        <!-- Date and Time Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?= date('Y-m-d') ?>" required>
                                    <label for="tanggal">
                                        <i class="bi bi-calendar me-2"></i>Tanggal Aktivitas
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                                    <label for="jam_mulai">
                                        <i class="bi bi-clock me-2"></i>Jam Mulai
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                                    <label for="jam_selesai">
                                        <i class="bi bi-clock-fill me-2"></i>Jam Selesai
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="durasi" readonly>
                                    <label for="durasi">
                                        <i class="bi bi-stopwatch me-2"></i>Durasi Aktivitas
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Description -->
                        <div class="mb-4">
                            <div class="form-floating">
                            <textarea class="form-control" id="uraian" name="uraian" rows="6" 
                                          placeholder="Jelaskan detail aktivitas yang Anda lakukan hari ini..." required></textarea>
                                <label for="uraian">
                                    <i class="bi bi-text-paragraph me-2"></i>Uraian Aktivitas
                                </label>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Jelaskan secara detail aktivitas yang Anda lakukan, termasuk:
                                <ul class="mt-2 mb-0">
                                    <li>Tugas atau proyek yang dikerjakan</li>
                                    <li>Teknologi atau tools yang digunakan</li>
                                    <li>Kendala yang dihadapi dan solusinya</li>
                                    <li>Pelajaran atau pengalaman yang didapat</li>
                                </ul>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="bukti" class="form-label">
                                <i class="bi bi-paperclip me-2"></i>Bukti Aktivitas (Opsional)
                            </label>
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-content">
                                    <i class="bi bi-cloud-upload text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-2">Drag & drop file di sini atau klik untuk memilih</p>
                                    <p class="text-muted small mb-0">
                                        Format: PDF, JPG, PNG, DOC, DOCX (Maks. 2MB)
                                    </p>
                                </div>
                                <input type="file" class="form-control" id="bukti" name="bukti" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;">
                            </div>
                            <div id="filePreview" class="mt-3" style="display: none;">
                                <div class="selected-file">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    <span id="fileName"></span>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="removeFile">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="konfirmasi" required>
                                <label class="form-check-label" for="konfirmasi">
                                    Saya menyatakan bahwa informasi yang saya berikan adalah benar dan akurat
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary flex-fill" id="submitLogBtn">
                                <span class="btn-text"><i class="bi bi-check-circle me-2"></i>Simpan Log Aktivitas</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Reset Form
                                </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="bi bi-lightbulb"></i>
                    Tips Menulis Log Aktivitas yang Baik
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">✅ Yang Harus Dilakukan:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Tulis dengan detail dan spesifik
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Sertakan teknologi/tools yang digunakan
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Jelaskan hasil atau output yang dihasilkan
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    Tulis pembelajaran yang didapat
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning mb-3">❌ Yang Harus Dihindari:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                    Menulis terlalu singkat dan tidak jelas
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                    Menggunakan bahasa yang tidak formal
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                    Tidak menyertakan detail teknis
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                    Menyalin log dari hari sebelumnya
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.upload-area {
    border: 2px dashed var(--border-color);
    border-radius: 0.75rem;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: var(--background-light);
}

.upload-area:hover {
    border-color: var(--primary-light);
    background: rgba(59, 130, 246, 0.05);
}

.upload-area.dragover {
    border-color: var(--primary-color);
    background: rgba(59, 130, 246, 0.1);
}

.selected-file {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    font-size: 0.875rem;
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.form-check-input:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

.form-text {
    background: var(--background-light);
    border-left: 4px solid var(--primary-light);
    padding: 1rem;
    border-radius: 0.5rem;
    margin-top: 0.5rem;
}

.form-text ul {
    padding-left: 1.5rem;
}

.form-text li {
    margin-bottom: 0.25rem;
}
</style>

<script>
// Duration calculation
function calculateDuration() {
    const startTime = document.getElementById('jam_mulai').value;
    const endTime = document.getElementById('jam_selesai').value;
    
    if (startTime && endTime) {
        const start = new Date(`2000-01-01T${startTime}`);
        const end = new Date(`2000-01-01T${endTime}`);
        
        if (end < start) {
            end.setDate(end.getDate() + 1);
        }
        
        const diff = end - start;
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        
        document.getElementById('durasi').value = `${hours} jam ${minutes} menit`;
    }
}

document.getElementById('jam_mulai').addEventListener('change', calculateDuration);
document.getElementById('jam_selesai').addEventListener('change', calculateDuration);

// File upload handling
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('bukti');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');
const removeFile = document.getElementById('removeFile');

uploadArea.addEventListener('click', () => fileInput.click());

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
});

fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        handleFile(e.target.files[0]);
    }
});

function handleFile(file) {
    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('File terlalu besar. Maksimal 2MB.');
        return;
    }
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!allowedTypes.includes(file.type)) {
        alert('Format file tidak didukung. Gunakan PDF, JPG, PNG, DOC, atau DOCX.');
        return;
    }
    
    fileName.textContent = file.name;
    filePreview.style.display = 'block';
    uploadArea.style.display = 'none';
}

removeFile.addEventListener('click', () => {
    fileInput.value = '';
    filePreview.style.display = 'none';
    uploadArea.style.display = 'block';
});

// Form validation
document.getElementById('logForm').addEventListener('submit', function(e) {
    const uraian = document.getElementById('uraian').value.trim();
    const konfirmasi = document.getElementById('konfirmasi').checked;
    
    if (uraian.length < 15) {
        e.preventDefault();
        alert('Uraian aktivitas minimal 15 karakter. Silakan jelaskan aktivitas Anda dengan lebih detail.');
        return;
    }
    
    if (!konfirmasi) {
        e.preventDefault();
        alert('Anda harus menyetujui pernyataan sebelum menyimpan log aktivitas.');
        return;
    }
    // Show loading state
    const submitBtn = document.getElementById('submitLogBtn');
    submitBtn.classList.add('is-loading');
    submitBtn.querySelector('.btn-text').classList.add('d-none');
    submitBtn.querySelector('.spinner-border').classList.remove('d-none');
});

// Auto-save draft (optional)
let autoSaveTimer;
document.getElementById('uraian').addEventListener('input', function() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        const formData = new FormData(document.getElementById('logForm'));
        localStorage.setItem('logDraft', JSON.stringify({
            tanggal: formData.get('tanggal'),
            jam_mulai: formData.get('jam_mulai'),
            jam_selesai: formData.get('jam_selesai'),
            uraian: formData.get('uraian')
        }));
    }, 2000);
});

// Load draft on page load
window.addEventListener('load', function() {
    const draft = localStorage.getItem('logDraft');
    if (draft) {
        const data = JSON.parse(draft);
        if (data.uraian) {
            document.getElementById('uraian').value = data.uraian;
        }
    }
});
</script>
<?= $this->endSection() ?>
