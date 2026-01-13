<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Slider Demo - Responsive Images</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        .demo-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .demo-info {
            padding: 20px;
            background: #f8f9fa;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        .demo-info h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .demo-info p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .size-indicator {
            position: fixed;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 1000;
        }

        .content-section {
            padding: 60px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .content-section h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .feature-card h3 {
            color: #007bff;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #666;
        }

        @media (max-width: 768px) {
            .demo-info {
                padding: 15px;
            }

            .content-section {
                padding: 40px 15px;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Size Indicator -->
    <div class="size-indicator" id="sizeIndicator">
        <span id="screenSize"></span>
    </div>

    <div class="demo-container">
        <!-- Demo Info -->
        <div class="demo-info">
            <h1>Hero Slider Responsive Demo</h1>
            <p>
                Demo ini menampilkan hero slider dengan gambar yang otomatis menyesuaikan ukuran layar.
                Resize browser Anda untuk melihat perubahan gambar secara real-time.
            </p>
        </div>

        <!-- Hero Slider Component -->
        <x-hero-slider
            :slides="$slides"
            :autoplay="true"
            :interval="4000"
            :showNavigation="true"
            :showDots="true"
        />

        <!-- Content Section -->
        <div class="content-section">
            <h2>Fitur Hero Slider Responsif</h2>

            <div class="features-grid">
                <div class="feature-card">
                    <h3>🖼️ Multi-Size Images</h3>
                    <p>
                        Otomatis generate 6 ukuran gambar berbeda untuk setiap breakpoint:
                        Desktop Large, Desktop Medium, Desktop Small, Tablet, Mobile Large, dan Mobile Small.
                    </p>
                </div>

                <div class="feature-card">
                    <h3>⚡ WebP Support</h3>
                    <p>
                        Menggunakan format WebP untuk performa lebih cepat dengan fallback JPG
                        untuk browser yang tidak mendukung WebP.
                    </p>
                </div>

                <div class="feature-card">
                    <h3>📱 Responsive Design</h3>
                    <p>
                        Aspect ratio yang berbeda untuk setiap perangkat: 2.4:1 untuk desktop,
                        1.6:1 untuk tablet, dan 1.33:1 untuk mobile.
                    </p>
                </div>

                <div class="feature-card">
                    <h3>🎯 Lazy Loading</h3>
                    <p>
                        Implementasi lazy loading untuk slide yang tidak aktif,
                        meningkatkan performa loading halaman.
                    </p>
                </div>

                <div class="feature-card">
                    <h3>🎮 Interactive Controls</h3>
                    <p>
                        Navigasi dengan arrow keys, touch/swipe support, autoplay dengan pause on hover,
                        dan dot indicators.
                    </p>
                </div>

                <div class="feature-card">
                    <h3>🔧 Easy Integration</h3>
                    <p>
                        Blade component yang mudah digunakan dengan konfigurasi yang fleksibel
                        dan service class untuk upload otomatis.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Screen size indicator
        function updateSizeIndicator() {
            const width = window.innerWidth;
            const indicator = document.getElementById('screenSize');

            let sizeLabel = '';
            if (width >= 1920) {
                sizeLabel = `Desktop Large (${width}px) - 1920x800`;
            } else if (width >= 1440) {
                sizeLabel = `Desktop Medium (${width}px) - 1440x600`;
            } else if (width >= 1024) {
                sizeLabel = `Desktop Small (${width}px) - 1024x427`;
            } else if (width >= 768) {
                sizeLabel = `Tablet (${width}px) - 768x480`;
            } else if (width >= 480) {
                sizeLabel = `Mobile Large (${width}px) - 480x360`;
            } else {
                sizeLabel = `Mobile Small (${width}px) - 320x240`;
            }

            indicator.textContent = sizeLabel;
        }

        // Update on load and resize
        window.addEventListener('load', updateSizeIndicator);
        window.addEventListener('resize', updateSizeIndicator);

        // Log image loads for debugging
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.hero-slider img');
            images.forEach((img, index) => {
                img.addEventListener('load', function() {
                    console.log(`Image ${index + 1} loaded:`, {
                        src: this.src,
                        naturalWidth: this.naturalWidth,
                        naturalHeight: this.naturalHeight,
                        displayWidth: this.offsetWidth,
                        displayHeight: this.offsetHeight
                    });
                });
            });
        });
    </script>
</body>
</html>
