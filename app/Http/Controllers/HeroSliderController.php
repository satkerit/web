<?php

namespace App\Http\Controllers;

use App\Services\HeroImageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class HeroSliderController extends Controller
{
    protected $heroImageService;

    public function __construct(HeroImageService $heroImageService)
    {
        $this->heroImageService = $heroImageService;
    }

    /**
     * Upload hero image dan generate responsive sizes
     */
    public function upload(Request $request): JsonResponse
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'hero_image' => [
                    'required',
                    'image',
                    'mimes:jpeg,jpg,png,webp',
                    'max:5120', // 5MB max
                ],
                'title' => 'nullable|string|max:255',
                'subtitle' => 'nullable|string|max:500',
                'cta_text' => 'nullable|string|max:100',
                'cta_url' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Upload dan generate responsive images
            $uploadedImages = $this->heroImageService->uploadHeroImage(
                $request->file('hero_image')
            );

            // Simpan data ke database (contoh)
            $heroSlide = [
                'images' => $uploadedImages,
                'title' => $request->input('title'),
                'subtitle' => $request->input('subtitle'),
                'cta_text' => $request->input('cta_text'),
                'cta_url' => $request->input('cta_url'),
                'created_at' => now(),
            ];

            // TODO: Simpan ke database model HeroSlider
            // HeroSlider::create($heroSlide);

            return response()->json([
                'success' => true,
                'message' => 'Hero image berhasil diupload',
                'data' => [
                    'images' => $uploadedImages,
                    'picture_html' => $this->heroImageService->generatePictureElement(
                        $uploadedImages,
                        $request->input('title', 'Hero Image')
                    )
                ]
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview responsive images
     */
    public function preview(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'hero_image' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120',
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate temporary preview
            $uploadedImages = $this->heroImageService->uploadHeroImage(
                $request->file('hero_image'),
                'preview_' . time()
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'images' => $uploadedImages,
                    'sizes_info' => [
                        'desktop_large' => '1920x800px - Desktop Large',
                        'desktop_medium' => '1440x600px - Desktop Medium',
                        'desktop_small' => '1024x427px - Desktop Small',
                        'tablet' => '768x480px - Tablet',
                        'mobile_large' => '480x360px - Mobile Large',
                        'mobile_small' => '320x240px - Mobile Small',
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get image size recommendations
     */
    public function getSizeRecommendations(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'recommended_upload_size' => [
                    'width' => 1920,
                    'height' => 800,
                    'aspect_ratio' => '2.4:1',
                    'format' => 'JPG atau PNG',
                    'max_file_size' => '5MB'
                ],
                'generated_sizes' => [
                    [
                        'name' => 'Desktop Large',
                        'size' => '1920x800px',
                        'breakpoint' => '1920px+',
                        'aspect_ratio' => '2.4:1'
                    ],
                    [
                        'name' => 'Desktop Medium',
                        'size' => '1440x600px',
                        'breakpoint' => '1440px - 1919px',
                        'aspect_ratio' => '2.4:1'
                    ],
                    [
                        'name' => 'Desktop Small',
                        'size' => '1024x427px',
                        'breakpoint' => '1024px - 1439px',
                        'aspect_ratio' => '2.4:1'
                    ],
                    [
                        'name' => 'Tablet',
                        'size' => '768x480px',
                        'breakpoint' => '768px - 1023px',
                        'aspect_ratio' => '1.6:1'
                    ],
                    [
                        'name' => 'Mobile Large',
                        'size' => '480x360px',
                        'breakpoint' => '480px - 767px',
                        'aspect_ratio' => '1.33:1'
                    ],
                    [
                        'name' => 'Mobile Small',
                        'size' => '320x240px',
                        'breakpoint' => '320px - 479px',
                        'aspect_ratio' => '1.33:1'
                    ]
                ],
                'tips' => [
                    'Upload gambar dengan resolusi tertinggi (1920x800px) untuk hasil terbaik',
                    'Sistem akan otomatis generate semua ukuran responsif',
                    'Format WebP akan dibuat otomatis untuk performa lebih baik',
                    'Pastikan subjek utama gambar berada di tengah untuk crop yang optimal',
                    'Hindari teks kecil pada gambar karena mungkin tidak terbaca di mobile'
                ]
            ]
        ]);
    }
}
