<?php
/**
 * Mini Cashier - Landing Page
 * 
 * Main entry point with modern split-screen layout.
 * Professional POS system for Indonesian small businesses.
 * 
 * @link auth/login.php Login page
 * @link auth/register.php Registration page
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KasirKu - Sistem Kasir Modern untuk UMKM</title>
    
    <!-- Google Fonts: DM Sans (Display) + Lexend (Body) - Modern Indonesian Tech -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;500;600;700&family=Lexend:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.8 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="landing-wrapper">
        <!-- Animated Background Elements -->
        <div class="bg-mesh-gradient"></div>
        <div class="bg-grid-overlay"></div>
        
        <!-- Left Panel: Brand Story -->
        <div class="brand-panel">
            <div class="brand-content">
                <!-- Logo Mark -->
                <div class="logo-mark mb-4">
                    <div class="logo-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="logo-icon">
                            <defs>
                                <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <rect x="8" y="8" width="48" height="48" rx="12" fill="url(#logoGradient)"/>
                            <path d="M20 32 L28 40 L44 24" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                <line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Kelola Produk</h4>
                            <p>Input dan pantau ratusan produk dengan mudah. Track stok otomatis setiap transaksi.</p>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                <line x1="2" y1="10" x2="22" y2="10"/>
                                <line x1="7" y1="15" x2="7.01" y2="15"/>
                                <line x1="11" y1="15" x2="13" y2="15"/>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Transaksi Cepat</h4>
                            <p>Proses pembayaran dalam hitungan detik. Tampilan sederhana untuk kasir.</p>
                        </div>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"/>
                                <line x1="12" y1="20" x2="12" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
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
                    <h2 class="portal-title">Selamat Datang</h2>
                    <p class="portal-subtitle">Masuk untuk mulai mengelola toko Anda</p>
                </div>
                
                <div class="auth-actions">
                    <a href="auth/login.php" class="auth-card-btn primary">
                        <div class="auth-card-content">
                            <div class="auth-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                    <polyline points="10 17 15 12 10 7"/>
                                    <line x1="15" y1="12" x2="3" y2="12"/>
                                </svg>
                            </div>
                            <div class="auth-card-text">
                                <span class="auth-card-label">Masuk ke Akun</span>
                                <span class="auth-card-hint">Sudah punya akun</span>
                            </div>
                        </div>
                        <div class="auth-card-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </div>
                    </a>
                    
                    <a href="auth/register.php" class="auth-card-btn secondary">
                        <div class="auth-card-content">
                            <div class="auth-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="8.5" cy="7" r="4"/>
                                    <line x1="20" y1="8" x2="20" y2="14"/>
                                    <line x1="23" y1="11" x2="17" y2="11"/>
                                </svg>
                            </div>
                            <div class="auth-card-text">
                                <span class="auth-card-label">Daftar Gratis</span>
                                <span class="auth-card-hint">Mulai dalam 30 detik</span>
                            </div>
                        </div>
                        <div class="auth-card-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </div>
                    </a>
                </div>
                
                <!-- Benefits List -->
                <div class="quick-benefits">
                    <div class="benefit-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Setup 2 menit, langsung pakai</span>
                    </div>
                    <div class="benefit-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span>Tanpa kartu kredit</span>
                    </div>
                    <div class="benefit-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
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
    </div>
</body>
</html>
