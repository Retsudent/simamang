<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin: 0 0 12px 0; }
        h2 { font-size: 14px; margin: 16px 0 8px 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; vertical-align: top; }
        th { background: #f2f2f2; }
        .meta td { border: none; padding: 2px 0; }
        .muted { color: #555; }
    </style>
    <title>Laporan Magang</title>
    </head>
<body>
    <h1 style="text-align:center">LAPORAN AKTIVITAS MAGANG</h1>
    <p style="text-align:center" class="muted">Sistem Monitoring Aktivitas Magang (SIMAMANG)</p>

    <h2>Informasi Siswa</h2>
    <table class="meta">
        <tr><td>Nama Siswa</td><td>: <strong><?= htmlspecialchars($siswa['nama'] ?? '-') ?></strong></td></tr>
        <tr><td>NIS</td><td>: <?= htmlspecialchars($siswa['nis'] ?? '-') ?></td></tr>
        <tr><td>Tempat Magang</td><td>: <?= htmlspecialchars($siswa['tempat_magang'] ?? '-') ?></td></tr>
        <tr><td>Periode</td><td>: <strong><?= date('d/m/Y', strtotime($startDate)) ?> - <?= date('d/m/Y', strtotime($endDate)) ?></strong></td></tr>
    </table>

    <h2>Ringkasan</h2>
    <table class="meta">
        <tr><td>Total Aktivitas</td><td>: <?= count($logs) ?></td></tr>
        <tr><td>Total Jam</td><td>: <?= $totalHours ?> jam</td></tr>
        <tr><td>Disetujui</td><td>: <?= $disetujuiCount ?></td></tr>
        <tr><td>Revisi</td><td>: <?= $revisiCount ?></td></tr>
        <tr><td>Menunggu</td><td>: <?= $menungguCount ?></td></tr>
    </table>

    <h2>Daftar Aktivitas Harian</h2>
    <table>
        <thead>
            <tr>
                <th style="width:32px">No</th>
                <th style="width:82px">Tanggal</th>
                <th style="width:110px">Waktu</th>
                <th style="width:70px">Durasi</th>
                <th>Aktivitas</th>
                <th style="width:90px">Status</th>
                <th style="width:160px">Komentar</th>
            </tr>
        </thead>
        <tbody>
        <?php $no=1; foreach ($logs as $log): ?>
            <?php
                $start = strtotime($log['jam_mulai']);
                $end = strtotime($log['jam_selesai']);
                $hours = $start && $end && $end>$start ? floor(($end-$start)/3600) : 0;
                $minutes = $start && $end && $end>$start ? floor((($end-$start)%3600)/60) : 0;
            ?>
            <tr>
                <td style="text-align:center;"><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($log['tanggal'])) ?></td>
                <td><?= htmlspecialchars($log['jam_mulai']) ?> - <?= htmlspecialchars($log['jam_selesai']) ?></td>
                <td><?= $hours ?>j <?= $minutes ?>m</td>
                <td><?= nl2br(htmlspecialchars($log['uraian'])) ?></td>
                <td><?= ucfirst($log['status'] ?? 'menunggu') ?></td>
                <td><?= nl2br(htmlspecialchars($log['komentar'] ?? '-')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>



