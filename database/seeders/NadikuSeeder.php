<?php
namespace Database\Seeders;
use App\Models\ContentItem;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class NadikuSeeder extends Seeder {public function run():void{
 User::updateOrCreate(['username'=>'admin'],['name'=>'Nadiku Admin','email'=>'admin@13nadi.local','password'=>Hash::make('admin')]);
 $items=[['release','Pulang Perlahan','pulang-perlahan','Arunika Senja','Indie Pop','https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=900&q=85'],['release','Ruang yang Sama','ruang-yang-sama','KALA','Alternative','https://images.unsplash.com/photo-1524368535928-5b5e00ddc76b?auto=format&fit=crop&w=900&q=85'],['release','Setelah Hujan','setelah-hujan','Nara Aksara','Folk','https://images.unsplash.com/photo-1524650359799-842906ca1c06?auto=format&fit=crop&w=900&q=85'],['artist','Arunika Senja','arunika-senja','Indie Pop','Musisi dengan cerita yang hangat.','https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=85'],['artist','KALA','kala','Alternative Rock','Energi baru dari selatan.','https://images.unsplash.com/photo-1521119989659-a83eee488004?auto=format&fit=crop&w=800&q=85']];
 foreach($items as $i=>$x)ContentItem::updateOrCreate(['type'=>$x[0],'slug'=>$x[2]],['title'=>$x[1],'subtitle'=>$x[3],'description'=>$x[4],'image_url'=>$x[5],'sort_order'=>$i,'is_active'=>true]);
 ContentItem::where('type','slider')->where('slug','nada-yang-menghubungkan-cerita')->delete();
 ContentItem::updateOrCreate(['type'=>'slider','slug'=>'hero-utama'],['title'=>'The Dusty Rusty','subtitle'=>'Dengarkan Sekarang','description'=>"The Dusty Rusty is a band originating from Bogor, West Java, Indonesia. Its members, initially friends from junior high school, reunited in their 20s to form the band, using it as a platform for self-expression and creativity. The name 'The Dusty Rusty', meaning 'dust and rust', was chosen because philosophically, it symbolizes something redolent of the 'past'.",'image_url'=>null,'external_url'=>null,'metadata'=>['asset_key'=>'slide1'],'sort_order'=>0,'is_active'=>true]);
 ContentItem::updateOrCreate(['type'=>'slider','slug'=>'temukan-suara-baru'],['title'=>'Temukan Suara Yang Baru Hari Ini','subtitle'=>'Lihat Rilisan Baru','description'=>'Dengarkan rilisan terbaru dari musisi independen dengan karakter, cerita, dan warna yang berbeda.','image_url'=>null,'external_url'=>null,'metadata'=>['asset_key'=>'slide2'],'sort_order'=>1,'is_active'=>true]);
 ContentItem::updateOrCreate(['type'=>'banner','slug'=>'banner-utama'],['title'=>'Banner Utama','image_url'=>'/slide2.jpg','external_url'=>null,'metadata'=>['link_enabled'=>false],'sort_order'=>0,'is_active'=>true]);
 $programs=[
  ['musik-independen','Musik Independen','Ruang untuk karya yang jujur, berkarakter, dan tumbuh bersama pendengarnya.','#rilisan','disc'],
  ['live-session','Live Session','Penampilan intim yang menangkap energi musisi dalam format panggung yang autentik.','#galeri','activity'],
  ['artist-spotlight','Artist Spotlight','Mengenal lebih dekat cerita, proses kreatif, dan perjalanan para artis 13 Nadi.','#berita','users'],
  ['creative-collaboration','Creative Collaboration','Kolaborasi lintas disiplin untuk melahirkan pengalaman musik yang lebih bermakna.','#kolaborasi','handshake'],
 ];
 foreach($programs as $i=>$item)ContentItem::updateOrCreate(['type'=>'program','slug'=>$item[0]],['title'=>$item[1],'description'=>$item[2],'external_url'=>null,'metadata'=>['link'=>$item[3],'icon'=>$item[4]],'sort_order'=>$i,'is_active'=>true]);
 $news=[
  ['13 Nadi Live Session Membawa Suara Baru ke Jakarta','live-session-jakarta','Live Session','Pertunjukan intim yang mempertemukan musisi, cerita, dan pendengar dalam satu panggung.','https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1000&q=88','22 Agustus 2026'],
  ['Di Balik Studio: Merancang Karakter Sebuah Lagu','proses-kreatif-studio','Behind The Sound','Menjelajahi proses kreatif dari gagasan pertama hingga aransemen menemukan bentuk terbaiknya.','https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1000&q=88','18 Agustus 2026'],
  ['Aurelia dan Keberanian Bercerita Lewat Musik','artist-spotlight-aurelia','Artist Spotlight','Perjalanan personal tentang keberanian, kejujuran, dan suara yang tumbuh bersama pendengar.','https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=1000&q=88','12 Agustus 2026'],
  ['Ketika Musik, Visual, dan Panggung Menjadi Satu','kolaborasi-lintas-suara','Kolaborasi','Kolaborasi lintas disiplin menghadirkan pengalaman pertunjukan yang lebih hidup dan bermakna.','https://images.unsplash.com/photo-1521337581100-8ca9a73a5f79?auto=format&fit=crop&w=1000&q=88','8 Agustus 2026'],
 ];
 foreach($news as $i=>$item)ContentItem::updateOrCreate(['type'=>'news','slug'=>$item[1]],['title'=>$item[0],'subtitle'=>$item[2],'description'=>$item[3],'image_url'=>$item[4],'metadata'=>['date'=>$item[5]],'sort_order'=>$i,'is_active'=>true]);
 $photos=[
  ['Energi Penonton','energi-penonton','https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=900&q=85'],
  ['Sorot Panggung','sorot-panggung','https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?auto=format&fit=crop&w=900&q=85'],
  ['Nada yang Hidup','nada-yang-hidup','https://images.unsplash.com/photo-1521337581100-8ca9a73a5f79?auto=format&fit=crop&w=900&q=85'],
  ['Di Balik Cahaya','di-balik-cahaya','https://images.unsplash.com/photo-1501612780327-45045538702b?auto=format&fit=crop&w=900&q=85'],
  ['Cerita Sang Musisi','cerita-sang-musisi','https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=900&q=85'],
 ];
 foreach($photos as $i=>$item)ContentItem::updateOrCreate(['type'=>'photo','slug'=>$item[1]],['title'=>$item[0],'image_url'=>$item[2],'sort_order'=>$i,'is_active'=>true]);
 $videos=[
  ['Live Session — Suara dari Panggung','live-session-suara-panggung','04:28','https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1000&q=88'],
  ['Behind The Sound — Studio Stories','behind-the-sound-studio-stories','06:12','https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?auto=format&fit=crop&w=1000&q=88'],
  ['Artist Spotlight — Cerita di Balik Nada','artist-spotlight-cerita-nada','03:46','https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?auto=format&fit=crop&w=1000&q=88'],
 ];
 foreach($videos as $i=>$item)ContentItem::updateOrCreate(['type'=>'video','slug'=>$item[1]],['title'=>$item[0],'image_url'=>$item[3],'metadata'=>['duration'=>$item[2]],'sort_order'=>$i,'is_active'=>true]);
 ContentItem::updateOrCreate(
  ['type'=>'about','slug'=>'tentang-13-nadi-musik'],
  [
   'title'=>'13 Nadi Musik',
   'subtitle'=>'Digital Content Creator Partner',
   'description'=>'Berawal dari label musik digital, di tahun 2019, 13NadiMusik kini berevolusi menjadi “Digital content creator partner”, banyaknya para content creator, yang memiliki potensi untuk bisa menghadirkan konten konten berkualitas, namun terkendala oleh hal-hal teknis, mendasari pemikiran 13NadiMusik untuk hadir memberikan solusinya.',
   'metadata'=>['secondary_text'=>'Ide orisinil dipadu dengan bakat kuat, kadang tidak cukup, urusan teknis, seperti algoritma platform, kualitas audio visual, digital marketing dan hal teknis lainnya, membuat para content creator, jadi kurang fokus dengan esensi kreatif dan visi misi konten. Kami hadir sebagai partner para konten kreator, agar para content creator bisa lebih fokus berkarya, mewujudkan ide-ide kreatif, untuk menghibur, mengedukasi, atau membagikan pengalaman yang inspiratif untuk viewers nya.'],
   'sort_order'=>0,
   'is_active'=>true,
  ]
 );
 $information=[
  ['ecosystem-heading','Dari ide pertama hingga didengar dunia.','EKOSISTEM KREATIF','Kami membangun perjalanan karya yang terarah, autentik, dan dekat dengan pendengar.',['kind'=>'heading']],
  ['artist-development','Artist Development','Kenali Artis','Pengembangan identitas, arah musikal, dan strategi karya untuk setiap fase perjalanan artis.',['kind'=>'service','link'=>'#artis','icon'=>'disc']],
  ['music-production','Music Production','Lihat Rilisan','Pendampingan kreatif dari penulisan, rekaman, hingga karya siap hadir dengan karakter terbaiknya.',['kind'=>'service','link'=>'#rilisan','icon'=>'music']],
  ['digital-distribution','Digital Distribution','Berkolaborasi','Distribusi dan kampanye digital yang membantu musik menjangkau komunitas pendengar yang tepat.',['kind'=>'service','link'=>'#kolaborasi','icon'=>'activity']],
 ];
 foreach($information as $i=>$item)ContentItem::updateOrCreate(['type'=>'info','slug'=>$item[0]],['title'=>$item[1],'subtitle'=>$item[2],'description'=>$item[3],'metadata'=>$item[4],'sort_order'=>$i,'is_active'=>true]);
 foreach(['site_name'=>'13 Nadi Records','email'=>'hello@13nadi.com','instagram'=>'https://instagram.com/13nadi'] as $key=>$value)Setting::updateOrCreate(['key'=>$key],['group'=>'identity','value'=>$value]);
}}
