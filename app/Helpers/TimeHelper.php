<?php

/**
 * Get greeting based on current time (Indonesia timezone)
 */
function get_greeting($name = '') {
    // Set timezone ke Indonesia
    date_default_timezone_set('Asia/Jakarta');
    
    $hour = (int)date('H');
    
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour >= 12 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }
    
    return $greeting . ($name ? ', ' . $name : '');
}

/**
 * Get current date in Indonesian format (Indonesia timezone)
 */
function get_current_date() {
    // Set timezone ke Indonesia
    date_default_timezone_set('Asia/Jakarta');
    
    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    
    $bulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    
    $day = $hari[date('l')];
    $date = date('j');
    $month = $bulan[date('F')];
    $year = date('Y');
    
    return "$day, $date $month $year";
}

if (!function_exists('get_time_ago')) {
    /**
     * Get time ago in Indonesian
     */
    function get_time_ago($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) {
            return 'Baru saja';
        } elseif ($time < 3600) {
            $minutes = floor($time / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($time < 86400) {
            $hours = floor($time / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($time < 2592000) {
            $days = floor($time / 86400);
            return $days . ' hari yang lalu';
        } else {
            return date('d/m/Y H:i', strtotime($datetime));
        }
    }
}

if (!function_exists('get_week_progress')) {
    /**
     * Get current week progress percentage
     */
    function get_week_progress() {
        $current_day = date('N'); // 1 (Monday) to 7 (Sunday)
        $progress = ($current_day / 7) * 100;
        return round($progress, 1);
    }
}

if (!function_exists('get_month_progress')) {
    /**
     * Get current month progress percentage
     */
    function get_month_progress() {
        $current_day = date('j');
        $days_in_month = date('t');
        $progress = ($current_day / $days_in_month) * 100;
        return round($progress, 1);
    }
}
