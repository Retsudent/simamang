<?= $this->extend('layouts/main') ?>

<?php helper('TimeHelper'); ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="welcome-greeting"><?= get_greeting(session()->get('nama')) ?></div>
            <div class="welcome-subtitle">Selamat datang kembali di SIMAMANG</div>
            <div class="welcome-info">
                <div class="welcome-item">
                    <i class="bi bi-calendar3"></i>
                    <span><?= get_current_date() ?></span>
                </div>
                <div class="welcome-item">
                    <i class="bi bi-clock"></i>
                    <span><?= date('H:i') ?> WIB</span>
                </div>
                <div class="welcome-item">
                    <i class="bi bi-person-workspace"></i>
                    <span>Pembimbing Magang</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-number"><?= $totalSiswa ?? 0 ?></div>
                    <div class="stat-label">Siswa Bimbingan</div>
                    <div class="stat-change">Aktif magang</div>
                </div>
                <div class="stat-icon primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-number"><?= $totalLog ?? 0 ?></div>
                    <div class="stat-label">Total Log Aktivitas</div>
                    <div class="stat-change">Dari semua siswa</div>
                </div>
                <div class="stat-icon success">
                    <i class="bi bi-clipboard-data"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-number"><?= $logPending ?? 0 ?></div>
                    <div class="stat-label">Log Menunggu Review</div>
                    <div class="stat-change">Perlu validasi</div>
                </div>
                <div class="stat-icon warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-number"><?= $logApproved ?? 0 ?></div>
                    <div class="stat-label">Log Disetujui</div>
                    <div class="stat-change">Sudah divalidasi</div>
                </div>
                <div class="stat-icon info">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-number"><?= $logRevisi ?? 0 ?></div>
                    <div class="stat-label">Log Revisi</div>
                    <div class="stat-change">Perlu perbaikan</div>
                </div>
                <div class="stat-icon danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3><i class="bi bi-lightning"></i> Akses Cepat</h3>
        <div class="actions-grid">
            <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="action-item">
                <div class="action-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="action-text">Lihat Aktivitas Siswa</div>
            </a>
            <a href="<?= base_url('pembimbing/komentar') ?>" class="action-item">
                <div class="action-icon">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <div class="action-text">Komentar & Validasi</div>
            </a>
            <a href="<?= base_url('profile') ?>" class="action-item">
                <div class="action-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="action-text">Profil Saya</div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-activity">
        <h3><i class="bi bi-activity"></i> Aktivitas Terbaru Siswa</h3>
        <?php if (empty($recentActivities ?? [])): ?>
            <div class="text-center py-4">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-muted);"></i>
                <p class="text-muted mt-2">Belum ada aktivitas terbaru dari siswa</p>
            </div>
        <?php else: ?>
            <?php foreach (array_slice($recentActivities, 0, 5) as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">
                            <strong><?= $activity['siswa_nama'] ?? 'Siswa' ?></strong> - <?= substr($activity['uraian'] ?? '', 0, 50) ?>...
                        </div>
                        <div class="activity-time">
                            <?= date('d/m/Y H:i', strtotime(($activity['tanggal'] ?? '') . ' ' . ($activity['jam_mulai'] ?? ''))) ?>
                            <span class="badge bg-<?= ($activity['status'] ?? 'menunggu') === 'disetujui' ? 'success' : (($activity['status'] ?? 'menunggu') === 'revisi' ? 'warning' : 'secondary') ?> ms-2">
                                <?= ucfirst($activity['status'] ?? 'menunggu') ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// Real-time clock and greeting update
document.addEventListener('DOMContentLoaded', function() {
    const greetingElement = document.getElementById('greeting');
    const timeElement = document.getElementById('current-time');
    const dateElement = document.getElementById('current-date');
    const userName = '<?= session()->get('nama') ?>';
    
    // Function to get greeting based on hour
    function getGreeting(hour) {
        if (hour >= 5 && hour < 12) {
            return 'Selamat Pagi';
        } else if (hour >= 12 && hour < 15) {
            return 'Selamat Siang';
        } else if (hour >= 15 && hour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }
    
    // Function to update time and greeting
    function updateTime() {
        const now = new Date();
        
        // Use local time directly (no manual timezone adjustment needed)
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const day = now.getDay();
        const date = now.getDate();
        const month = now.getMonth();
        const year = now.getFullYear();
        
        // Update time with local timezone
        const timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        if (timeElement) {
            timeElement.textContent = timeString;
        }
        
        // Update greeting if hour changed
        if (greetingElement) {
            const currentGreeting = getGreeting(hours);
            const fullGreeting = `${currentGreeting}${userName ? ', ' + userName : ''}`;
            if (greetingElement.textContent !== fullGreeting) {
                greetingElement.textContent = fullGreeting;
            }
        }
        
        // Update date (only once per day)
        if (dateElement) {
            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dateString = `${dayNames[day]}, ${date} ${monthNames[month]} ${year}`;
            if (dateElement.textContent !== dateString) {
                dateElement.textContent = dateString;
            }
        }
    }
    
    // Update time every second
    updateTime();
    setInterval(updateTime, 1000);
    
    // Animate stats on load
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const finalValue = parseInt(stat.textContent);
        let currentValue = 0;
        const increment = finalValue / 50;
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(timer);
            }
            stat.textContent = Math.floor(currentValue);
        }, 30);
    });

    // Add hover effects to action items
    const actionItems = document.querySelectorAll('.action-item');
    actionItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(8px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0) scale(1)';
        });
    });
});
</script>
<?= $this->endSection() ?>
