<?php

namespace Database\Seeders;

use App\Models\UploadCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UploadCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Foto',
                'slug' => 'photo',
                'price' => 'Rp 15.000',
                'description' => 'Kirim foto kamu yang ingin di buatkan video Jedag-jedug.',
            ],
            [
                'title' => 'Video',
                'slug' => 'video',
                'price' => 'Rp 10.000',
                'description' => 'Kirim video Jedag-jedug kamu yang mau di posting, tanpa batasan durasi dan ukuran.',
            ],
            [
                'title' => 'Video bersyarat',
                'slug' => 'free',
                'price' => 'Rp 0 (Gratis)',
                'description' => 'Kirim video berdurasi 25 hingga 60 detik dan ukuran maksimal 2MB.',
            ],
        ];

        foreach ($categories as $category) {
            UploadCategory::create($category);
        }
    }
}
