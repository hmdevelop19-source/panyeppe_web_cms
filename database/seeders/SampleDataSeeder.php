<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (! $admin) {
            $admin = User::create([
                'name' => 'Administrator Pesantren',
                'email' => 'admin@pesantren.ac.id',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'status' => 'active',
            ]);
        }

        // 1. Categories
        $catNews = Category::updateOrCreate(['slug' => 'news-pesantren'], ['name' => 'News Pesantren']);
        $catAlumni = Category::updateOrCreate(['slug' => 'kolom-alumni'], ['name' => 'Kolom Alumni']);
        $catSantri = Category::updateOrCreate(['slug' => 'kolom-santri'], ['name' => 'Kolom Santri']);
        $catKajian = Category::updateOrCreate(['slug' => 'kajian'], ['name' => 'Kajian']);
        $catArticle = Category::updateOrCreate(['slug' => 'artikel'], ['name' => 'Artikel']);

        // 2. Banners
        Banner::truncate();
        Banner::create([
            'title' => 'Membangun Masa Depan Berasaskan Wahyu',
            'subtitle' => 'Pendaftaran Santri Baru Tahun Ajaran 2026/2027 telah dibuka. Bergabunglah bersama kami mencetak generasi Qurani.',
            'image_path' => 'https://images.unsplash.com/photo-1541339907198-e08756ebafe3?auto=format&fit=crop&q=80&w=2000',
            'link_url' => '/pendaftaran',
            'order' => 1,
            'is_active' => true,
        ]);
        Banner::create([
            'title' => 'Kurikulum Integrasi IPTEK & IMTAQ',
            'subtitle' => 'Metode pembelajaran modern yang dipadukan dengan pendalaman kitab kuning klasik secara mendalam.',
            'image_path' => 'https://images.unsplash.com/photo-1523050335392-9befbf0887c1?auto=format&fit=crop&q=80&w=2000',
            'link_url' => '/profil',
            'order' => 2,
            'is_active' => true,
        ]);
        Banner::create([
            'title' => 'Pesantren Digital & Eco-Friendly',
            'subtitle' => 'Kami menerapkan sistem smart-campus dan pengelolaan lingkungan yang asri demi kenyamanan santri.',
            'image_path' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=2000',
            'link_url' => '/fasilitas',
            'order' => 3,
            'is_active' => true,
        ]);

        // 3. Posts (Berita & Artikel)
        Post::truncate();
        for ($i = 1; $i <= 4; $i++) {
            $title = "Kegiatan Pesantren Minggu ke-$i: Fokus pada Tahfidz Quran";
            Post::create([
                'user_id' => $admin->id,
                'category_id' => $catNews->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => "<p>Alhamdulillah, kegiatan belajar mengajar di Pesantren Modern pada minggu ke-$i berjalan lancar. Seluruh santri sangat antusias mengikuti program Tahfidz Quran pagi.</p><p>Diharapkan dengan adanya program intensif ini, target pencapaian hafalan santri di semester ini dapat tercapai sesuai kurikulum yang telah ditetapkan oleh Majelis Pengasuh.</p>",
                'status' => 'published',
                'published_at' => now()->subDays($i),
            ]);
        }

        $artTitle = 'Urgensi Adab Sebelum Ilmu dalam Menuntut Bekal Akhirat';
        Post::create([
            'user_id' => $admin->id,
            'category_id' => $catArticle->id,
            'title' => $artTitle,
            'slug' => Str::slug($artTitle),
            'content' => '<p>Dalam tradisi pesantren, adab diletakkan lebih tinggi daripada ilmu. Hal ini dikarenakan ilmu tanpa adab hanya akan melahirkan kesombongan...</p>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // 4. Agendas
        Agenda::truncate();
        Agenda::create([
            'title' => 'Ujian Akhir Semester Ganjil',
            'slug' => Str::slug('Ujian Akhir Semester Ganjil'),
            'content' => 'Seluruh santri wajib mempersiapkan diri untuk mengikuti evaluasi belajar tahap pertama.',
            'location' => 'Aula Utama & Gedung Serbaguna',
            'event_date' => now()->addDays(14),
            'status' => 'published',
        ]);
        Agenda::create([
            'title' => 'Wisuda Tahfidz & Haflah Akhirussanah',
            'slug' => Str::slug('Wisuda Tahfidz & Haflah Akhirussanah'),
            'content' => 'Perayaan kelulusan santri angkatan ke-12 dan penghargaan bagi para penghafal Al-Quran.',
            'location' => 'Halaman Tengah Pesantren',
            'event_date' => now()->addDays(30),
            'status' => 'published',
        ]);

        // 5. Announcements
        Announcement::truncate();
        Announcement::create([
            'title' => 'Pengumuman Libur Hari Raya',
            'slug' => Str::slug('Pengumuman Libur Hari Raya'),
            'content' => 'Diberitahukan kepada wali santri bahwa liburan semester akan dimulai pada tanggal 10 Juni.',
            'priority' => 'high',
            'status' => 'published',
        ]);

        // 6. Videos
        Video::truncate();
        Video::create([
            'title' => 'Profil Singkat Pesantren Modern 2026',
            'description' => 'Tampilan lingkungan, fasilitas, dan kegiatan harian santri di kampus kami.',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_featured' => true,
        ]);

        // 7. Pages
        Page::truncate();
        Page::create([
            'title' => 'Pusat Pendidikan & Penelitian Peradaban Umat',
            'slug' => 'sejarah',
            'content' => '<p>Didirikan sejak puluhan tahun yang lalu, eksistensi institusi ini berangkat dari keyakinan kuat bahwa pendidikan agama dan penerapan IPTEK modern tidak boleh terpisah. Kami didedikasikan untuk menjembatani kearifan ajaran klasik dalam merespon berbagai tantangan global yang berkembang secara dinamis.</p><p>Perjalanan panjang kami telah melahirkan ribuan alumni yang kini berkiprah di berbagai sektor, mulai dari akademisi, praktisi profesional, hingga pemimpin masyarakat, dengan tetap membawa nilai-nilai luhur yang ditanamkan selama masa studi.</p>',
            'status' => 'published',
        ]);
        Page::create([
            'title' => 'Visi Utama',
            'slug' => 'visi-misi',
            'content' => 'Terwujudnya insan kamil yang bertaqwa. Mengembangkan IPTEK berbasis Al-Quran. Mencetak pemimpin masa depan yang berakhlakul karimah.',
            'status' => 'published',
        ]);
        Page::create([
            'title' => 'Misi Strategis',
            'slug' => 'misi-strategis',
            'content' => '<ul><li>Mendorong pelaksanaan riset fundamental berbasis nilai Islami yang diakui secara global.</li><li>Menyelenggarakan tata kelola lembaga secara transparan, partisipatif dan dinamis.</li><li>Melaksanakan pengabdian kepada masyarakat secara holistik untuk pengentasan kemiskinan dan ketahanan keluarga.</li><li>Mengembangkan sinergitas internal dan jaringan berkelanjutan di tingkat lokal, nasional dan global.</li></ul>',
            'status' => 'published',
        ]);

        // 8. Settings
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => 'Pesantren Modern Digital']);
        Setting::updateOrCreate(['key' => 'site_tagline'], ['value' => 'Mencetak Generasi Qurani di Era Digital']);
        Setting::updateOrCreate(['key' => 'primary_color'], ['value' => '#0B5C3B']);
        Setting::updateOrCreate(['key' => 'secondary_color'], ['value' => '#F4C41B']);
        Setting::updateOrCreate(['key' => 'site_description'], ['value' => 'Pusat keunggulan pendidikan Islam yang mengintegrasikan nilai-nilai kepesantrenan dengan kemajuan teknologi modern untuk masa depan yang lebih baik.']);
        Setting::updateOrCreate(['key' => 'site_address'], ['value' => 'Jl. Pendidikan No. 123, Pamekasan, Jawa Timur']);
        Setting::updateOrCreate(['key' => 'contact_phone'], ['value' => '0812-3456-7890']);
        Setting::updateOrCreate(['key' => 'contact_email'], ['value' => 'info@pesantren.ac.id']);
    }
}
