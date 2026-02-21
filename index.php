<?php
/**
 * KasirKu - Landing Page
 * SMK Certification Quality
 * 
 * Modern SaaS landing page with navigation, hero section,
 * features, how it works, testimonials, and footer.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KasirKu - Sistem Kasir Modern untuk UMKM Indonesia</title>
    
    <!-- Google Fonts: Plus Jakarta Sans + Satoshi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Satoshi:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.8 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar (Fixed Top) -->
    <nav class="landing-navbar">
        <div class="navbar-container">
            <a href="index.php" class="navbar-brand">
                <div class="navbar-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                        <defs>
                            <linearGradient id="navLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#d1fae5;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#navLogoGradient)"/>
                        <path d="M20 32 L28 40 L44 24" stroke="#10b981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <span class="navbar-brand-name">KasirKu</span>
            </a>
            
            <div class="navbar-menu">
                <a href="#fitur" class="navbar-link">Fitur</a>
                <a href="#cara-kerja" class="navbar-link">Cara Kerja</a>
                <a href="#testimoni" class="navbar-link">Testimoni</a>
            </div>
            
            <div class="navbar-actions">
                <a href="auth/login.php" class="btn-navbar btn-navbar-outline">Masuk</a>
                <a href="auth/register.php" class="btn-navbar btn-navbar-primary">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Full viewport) -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="bi bi-stars"></i>
                    <span>#1 POS System untuk UMKM Indonesia</span>
                </div>
                
                <h1 class="hero-headline">
                    Kelola Toko Lebih Cerdas dengan <span class="highlight">KasirKu</span>
                </h1>
                
                <p class="hero-subheadline">
                    Sistem kasir modern yang sederhana, cepat, dan terjangkau. 
                    Tingkatkan penjualan, pantau stok, dan analisis bisnis Anda dalam satu platform.
                </p>
                
                <div class="hero-cta">
                    <a href="auth/register.php" class="btn-hero-primary">
                        <i class="bi bi-rocket-takeoff"></i>
                        Mulai Gratis Sekarang
                    </a>
                    <a href="#demo" class="btn-hero-secondary">
                        <i class="bi bi-play-circle"></i>
                        Lihat Demo
                    </a>
                </div>
                
                <div class="hero-trust">
                    <div class="trust-stat">
                        <span class="stat-number">10,000+</span>
                        <span class="stat-label">UMKM Terpercaya</span>
                    </div>
                    <div class="trust-divider"></div>
                    <div class="trust-stat">
                        <span class="stat-number">1M+</span>
                        <span class="stat-label">Transaksi/Bulan</span>
                    </div>
                    <div class="trust-divider"></div>
                    <div class="trust-stat">
                        <span class="stat-number">99.9%</span>
                        <span class="stat-label">Uptime</span>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="hero-illustration">
                    <!-- Dashboard Preview Illustration -->
                    <div class="dashboard-preview">
                        <div class="preview-header">
                            <div class="preview-dots">
                                <span class="dot red"></span>
                                <span class="dot yellow"></span>
                                <span class="dot green"></span>
                            </div>
                            <span class="preview-title">KasirKu Dashboard</span>
                        </div>
                        <div class="preview-content">
                            <div class="preview-chart">
                                <div class="chart-bar" style="height: 40%;"></div>
                                <div class="chart-bar" style="height: 65%;"></div>
                                <div class="chart-bar" style="height: 45%;"></div>
                                <div class="chart-bar" style="height: 80%;"></div>
                                <div class="chart-bar" style="height: 55%;"></div>
                                <div class="chart-bar" style="height: 90%;"></div>
                                <div class="chart-bar" style="height: 70%;"></div>
                            </div>
                            <div class="preview-stats">
                                <div class="mini-stat">
                                    <span class="mini-label">Penjualan</span>
                                    <span class="mini-value">Rp 2.5Jt</span>
                                </div>
                                <div class="mini-stat">
                                    <span class="mini-label">Transaksi</span>
                                    <span class="mini-value">124</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating Cards -->
                    <div class="floating-card card-1">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Transaksi Berhasil</span>
                    </div>
                    <div class="floating-card card-2">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>+25% Penjualan</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Background Decorations -->
        <div class="hero-bg-gradient"></div>
        <div class="hero-bg-grid"></div>
    </section>

    <!-- Features Section (Grid cards) -->
    <section id="fitur" class="features-section">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Fitur Unggulan</span>
                <h2 class="section-title">Semua yang Anda Butuhkan untuk Kelola Toko</h2>
                <p class="section-subtitle">
                    Fitur lengkap yang dirancang khusus untuk kebutuhan UMKM Indonesia
                </p>
            </div>
            
            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                    <h3 class="feature-title">Kelola Produk</h3>
                    <p class="feature-description">
                        Input dan pantau ratusan produk dengan mudah. Track stok otomatis setiap transaksi dengan notifikasi stok menipis.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Input produk massal</li>
                        <li><i class="bi bi-check-lg"></i> Kategori & varian</li>
                        <li><i class="bi bi-check-lg"></i> Alert stok minimum</li>
                    </ul>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card featured">
                    <div class="feature-badge-popular">Paling Populer</div>
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                    <h3 class="feature-title">Transaksi Cepat</h3>
                    <p class="feature-description">
                        Proses pembayaran dalam hitungan detik. Tampilan kasir yang sederhana dan intuitif untuk kecepatan maksimal.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Scan barcode</li>
                        <li><i class="bi bi-check-lg"></i> Multiple payment methods</li>
                        <li><i class="bi bi-check-lg"></i> Cetak struk otomatis</li>
                    </ul>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                    <h3 class="feature-title">Laporan Real-time</h3>
                    <p class="feature-description">
                        Grafik dan statistik penjualan real-time. Ambil keputusan bisnis berdasarkan data yang akurat.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Dashboard analitik</li>
                        <li><i class="bi bi-check-lg"></i> Export Excel/PDF</li>
                        <li><i class="bi bi-check-lg"></i> Laporan harian/bulanan</li>
                    </ul>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <h3 class="feature-title">Multi Kasir</h3>
                    <p class="feature-description">
                        Kelola banyak kasir dalam satu sistem. Track performa setiap kasir dengan laporan individual.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Role-based access</li>
                        <li><i class="bi bi-check-lg"></i> Shift management</li>
                        <li><i class="bi bi-check-lg"></i> Performance tracking</li>
                    </ul>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="bi bi-cloud-check"></i>
                        </div>
                    </div>
                    <h3 class="feature-title">Offline Mode</h3>
                    <p class="feature-description">
                        Tetap bisa transaksi tanpa internet. Data otomatis sinkron saat koneksi tersedia kembali.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> Works offline</li>
                        <li><i class="bi bi-check-lg"></i> Auto sync</li>
                        <li><i class="bi bi-check-lg"></i> No data loss</li>
                    </ul>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                    <h3 class="feature-title">Keamanan Terjamin</h3>
                    <p class="feature-description">
                        Data Anda aman dengan enkripsi tingkat bank. Backup otomatis setiap hari ke cloud.
                    </p>
                    <ul class="feature-list">
                        <li><i class="bi bi-check-lg"></i> SSL encryption</li>
                        <li><i class="bi bi-check-lg"></i> Daily backup</li>
                        <li><i class="bi bi-check-lg"></i> Audit trail</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="cara-kerja" class="how-it-works-section">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Cara Kerja</span>
                <h2 class="section-title">Mulai Dalam 3 Langkah Mudah</h2>
                <p class="section-subtitle">
                    Tidak perlu ribet. KasirKu dirancang untuk langsung bisa digunakan
                </p>
            </div>
            
            <div class="steps-container">
                <!-- Step 1 -->
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h3 class="step-title">Daftar Gratis</h3>
                    <p class="step-description">
                        Buat akun dalam 30 detik. Tidak perlu kartu kredit. Langsung bisa digunakan.
                    </p>
                    <div class="step-visual">
                        <div class="visual-mockup">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="step-connector">
                    <i class="bi bi-arrow-right"></i>
                </div>
                
                <!-- Step 2 -->
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h3 class="step-title">Tambah Produk</h3>
                    <p class="step-description">
                        Input produk Anda. Bisa satu-satu atau import dari Excel. Tambahkan foto dan harga.
                    </p>
                    <div class="step-visual">
                        <div class="visual-mockup">
                            <i class="bi bi-grid-3x3"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3 connector -->
                <div class="step-connector">
                    <i class="bi bi-arrow-right"></i>
                </div>
                
                <!-- Step 3 -->
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <h3 class="step-title">Mulai Transaksi</h3>
                    <p class="step-description">
                        Kasir Anda siap digunakan! Proses penjualan, cetak struk, dan lihat laporan real-time.
                    </p>
                    <div class="step-visual">
                        <div class="visual-mockup">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="steps-cta">
                <a href="auth/register.php" class="btn-steps-primary">
                    <i class="bi bi-rocket-takeoff"></i>
                    Coba Sekarang - Gratis!
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimoni" class="testimonials-section">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Testimoni</span>
                <h2 class="section-title">Dipercaya Ribuan UMKM Indonesia</h2>
                <p class="section-subtitle">
                    Dengar langsung dari mereka yang telah menggunakan KasirKu
                </p>
            </div>
            
            <div class="testimonials-grid">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">
                        "Sejak pakai KasirKu, penjualan toko saya naik 30%. Sistemnya mudah dipahami bahkan oleh karyawan yang gaptek."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="author-info">
                            <span class="author-name">Budi Santoso</span>
                            <span class="author-role">Owner, Toko Berkah</span>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">
                        "Fitur lengkap tapi harganya terjangkau. Yang paling saya suka adalah laporan otomatis, jadi bisa pantau bisnis dari mana saja."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="author-info">
                            <span class="author-name">Siti Rahma</span>
                            <span class="author-role">Owner, Coffee Shop Nusantara</span>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">
                        "Offline mode nya penyelamat! Internet mati pun kasir tetap jalan. Data otomatis sync begitu koneksi balik."
                    </p>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="author-info">
                            <span class="author-name">Ahmad Fauzi</span>
                            <span class="author-role">Manager, MinimMart Jaya</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Social Proof Stats -->
            <div class="social-proof">
                <div class="proof-stat">
                    <span class="proof-number">10,000+</span>
                    <span class="proof-label">UMKM Aktif</span>
                </div>
                <div class="proof-stat">
                    <span class="proof-number">50+</span>
                    <span class="proof-label">Kota di Indonesia</span>
                </div>
                <div class="proof-stat">
                    <span class="proof-number">4.9/5</span>
                    <span class="proof-label">Rating Pengguna</span>
                </div>
                <div class="proof-stat">
                    <span class="proof-number">24/7</span>
                    <span class="proof-label">Support Available</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-container">
            <div class="cta-content">
                <h2 class="cta-title">Siap Meningkatkan Bisnis Anda?</h2>
                <p class="cta-subtitle">
                    Bergabunglah dengan ribuan UMKM yang telah sukses mengelola bisnis dengan KasirKu. 
                    Gratis selamanya, tanpa biaya tersembunyi.
                </p>
                
                <div class="cta-benefits">
                    <div class="cta-benefit">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gratis selamanya</span>
                    </div>
                    <div class="cta-benefit">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Tanpa kartu kredit</span>
                    </div>
                    <div class="cta-benefit">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Setup 2 menit</span>
                    </div>
                    <div class="cta-benefit">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Support 24/7</span>
                    </div>
                </div>
                
                <div class="cta-actions">
                    <a href="auth/register.php" class="btn-cta-primary">
                        <i class="bi bi-rocket-takeoff"></i>
                        Mulai Gratis Sekarang
                    </a>
                    <a href="auth/login.php" class="btn-cta-secondary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Sudah Punya Akun? Masuk
                    </a>
                </div>
            </div>
            
            <div class="cta-visual">
                <div class="cta-illustration">
                    <div class="illustration-circle circle-1"></div>
                    <div class="illustration-circle circle-2"></div>
                    <div class="illustration-circle circle-3"></div>
                    <i class="bi bi-graph-up-arrow cta-icon"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-landing">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Brand Column -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                            <defs>
                                <linearGradient id="footerLogoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#d1fae5;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#footerLogoGradient)"/>
                            <path d="M20 32 L28 40 L44 24" stroke="#10b981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        </svg>
                    </div>
                    <h3 class="footer-brand-name">KasirKu</h3>
                    <p class="footer-brand-description">
                        Sistem kasir modern untuk UMKM Indonesia. 
                        Kelola toko lebih cerdas, tingkatkan penjualan.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                
                <!-- Product Links -->
                <div class="footer-links">
                    <h4 class="footer-heading">Produk</h4>
                    <ul>
                        <li><a href="#fitur">Fitur</a></li>
                        <li><a href="#harga">Harga</a></li>
                        <li><a href="#cara-kerja">Cara Kerja</a></li>
                        <li><a href="#testimoni">Testimoni</a></li>
                    </ul>
                </div>
                
                <!-- Company Links -->
                <div class="footer-links">
                    <h4 class="footer-heading">Perusahaan</h4>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Kontak</a></li>
                    </ul>
                </div>
                
                <!-- Support Links -->
                <div class="footer-links">
                    <h4 class="footer-heading">Bantuan</h4>
                    <ul>
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Dokumentasi</a></li>
                        <li><a href="#">API</a></li>
                        <li><a href="#">Status</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="footer-contact">
                    <h4 class="footer-heading">Hubungi Kami</h4>
                    <ul class="contact-list">
                        <li>
                            <i class="bi bi-envelope"></i>
                            <span>support@kasirku.id</span>
                        </li>
                        <li>
                            <i class="bi bi-whatsapp"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt"></i>
                            <span>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <p>&copy; <?php echo date('Y'); ?> KasirKu. All rights reserved.</p>
                </div>
                <div class="footer-bottom-right">
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.8 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Navbar Scroll Effect -->
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.landing-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
