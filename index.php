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
    <!-- Navigation Bar -->
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

    <!-- Hero Section -->
    <div class="landing-wrapper">
        <section class="landing-hero">
            <!-- Left Panel: Brand Story -->
            <div class="brand-panel">
                <div class="brand-content">
                    <!-- Logo Mark -->
                    <div class="logo-mark mb-4">
                        <div class="logo-icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="logo-icon">
                                <defs>
                                    <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#d1fae5;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#logoGradient)"/>
                                <path d="M20 32 L28 40 L44 24" stroke="#10b981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Brand Typography -->
                    <h1 class="brand-headline">
                        <span class="brand-name">KasirKu</span>
                        <span class="brand-descriptor">Solusi Kasir<br/><span class="highlight">UMKM Indonesia</span></span>
                    </h1>
                    
                    <p class="brand-story">
                        Kelola toko Anda lebih cerdas dengan sistem kasir modern yang sederhana, 
                        cepat, dan terjangkau. Tingkatkan penjualan, pantau stok, dan analisis 
                        bisnis Anda dalam satu platform.
                    </p>
                    
                    <!-- Feature Cards -->
                    <div class="feature-showcase">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="feature-text">
                                <h4>Kelola Produk</h4>
                                <p>Input dan pantau ratusan produk dengan mudah. Track stok otomatis setiap transaksi.</p>
                            </div>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div class="feature-text">
                                <h4>Transaksi Cepat</h4>
                                <p>Proses pembayaran dalam hitungan detik. Tampilan sederhana untuk kasir.</p>
                            </div>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <div class="feature-text">
                                <h4>Laporan Penjualan</h4>
                                <p>Grafik dan statistik real-time. Ambil keputusan berdasarkan data akurat.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="trust-section">
                        <div class="trust-item">
                            <span class="trust-number">100%</span>
                            <span class="trust-label">Gratis Selamanya</span>
                        </div>
                        <div class="trust-divider"></div>
                        <div class="trust-item">
                            <span class="trust-number">Tanpa</span>
                            <span class="trust-label">Batasan Transaksi</span>
                        </div>
                        <div class="trust-divider"></div>
                        <div class="trust-item">
                            <span class="trust-number">Offline</span>
                            <span class="trust-label">Bisa Tanpa Internet</span>
                        </div>
                    </div>
                </div>
                
                <!-- Brand Footer -->
                <div class="brand-footer">
                    <p class="made-with-love">
                        <span class="heart">❤️</span> Dibuat untuk UMKM Indonesia
                    </p>
                </div>
            </div>
            
            <!-- Right Panel: Auth Portal -->
            <div class="auth-panel">
                <div class="auth-portal">
                    <div class="portal-header">
                        <h2 class="portal-title">Mulai Sekarang</h2>
                        <p class="portal-subtitle">Bergabunglah dengan ribuan UMKM yang telah menggunakan KasirKu</p>
                    </div>
                    
                    <div class="auth-actions">
                        <a href="auth/login.php" class="auth-card-btn primary">
                            <div class="auth-card-content">
                                <div class="auth-card-icon">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>
                                <div class="auth-card-text">
                                    <span class="auth-card-label">Masuk ke Akun</span>
                                    <span class="auth-card-hint">Sudah punya akun</span>
                                </div>
                            </div>
                            <div class="auth-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </a>
                        
                        <a href="auth/register.php" class="auth-card-btn secondary">
                            <div class="auth-card-content">
                                <div class="auth-card-icon">
                                    <i class="bi bi-person-plus"></i>
                                </div>
                                <div class="auth-card-text">
                                    <span class="auth-card-label">Daftar Gratis</span>
                                    <span class="auth-card-hint">Mulai dalam 30 detik</span>
                                </div>
                            </div>
                            <div class="auth-card-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Benefits List -->
                    <div class="quick-benefits">
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill" style="color: var(--emerald-500);"></i>
                            <span>Setup 2 menit, langsung pakai</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill" style="color: var(--emerald-500);"></i>
                            <span>Tanpa kartu kredit</span>
                        </div>
                        <div class="benefit-item">
                            <i class="bi bi-check-circle-fill" style="color: var(--emerald-500);"></i>
                            <span>Support lokal Indonesia</span>
                        </div>
                    </div>
                </div>
                
                <!-- Auth Panel Footer -->
                <div class="auth-footer">
                    <div class="footer-links">
                        <a href="#" class="footer-link">Dokumentasi</a>
                        <span class="footer-divider">•</span>
                        <a href="#" class="footer-link">Privasi</a>
                        <span class="footer-divider">•</span>
                        <a href="#" class="footer-link">Tentang</a>
                    </div>
                    <p class="copyright">&copy; <?php echo date('Y'); ?> KasirKu. All rights reserved.</p>
                </div>
            </div>
        </section>
    </div>

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
    </script>
</body>
</html>
