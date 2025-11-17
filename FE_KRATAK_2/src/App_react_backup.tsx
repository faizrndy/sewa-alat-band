import { Music2, Guitar, Drum, Mic2, Piano, Radio, Package, Search, Star, Check, Facebook, Instagram, Mail, Phone, MapPin, ArrowRight } from 'lucide-react';
import { Button } from './components/ui/button';
import { Badge } from './components/ui/badge';
import { Card } from './components/ui/card';
import { ImageWithFallback } from './components/figma/ImageWithFallback';

export default function App() {
  const categories = [
    { icon: Guitar, name: 'Gitar', count: '24 items' },
    { icon: Music2, name: 'Bass', count: '18 items' },
    { icon: Drum, name: 'Drum', count: '15 items' },
    { icon: Piano, name: 'Keyboard', count: '12 items' },
    { icon: Radio, name: 'Amplifier', count: '20 items' },
    { icon: Mic2, name: 'Microphone', count: '30 items' },
  ];

  const featuredProducts = [
    {
      name: 'Gitar Akustik Yamaha',
      category: 'Gitar',
      price: 'Rp 30.000',
      period: 'per hari',
      available: true,
      image: 'music instruments guitar',
      rating: 4.8
    },
    {
      name: 'Drum Set Pearl Export',
      category: 'Drum',
      price: 'Rp 75.000',
      period: 'per hari',
      available: true,
      image: 'drum set',
      rating: 4.9
    },
    {
      name: 'Bass Fender Precision',
      category: 'Bass',
      price: 'Rp 35.000',
      period: 'per hari',
      available: true,
      image: 'bass guitar',
      rating: 4.7
    },
    {
      name: 'Keyboard Yamaha PSR',
      category: 'Keyboard',
      price: 'Rp 40.000',
      period: 'per hari',
      available: true,
      image: 'keyboard piano',
      rating: 4.6
    },
  ];

  const features = [
    { icon: Check, title: 'Kualitas Terjamin', desc: 'Semua alat musik dalam kondisi prima dan terawat' },
    { icon: Package, title: 'Pengiriman Cepat', desc: 'Layanan antar-jemput untuk kemudahan Anda' },
    { icon: Star, title: 'Harga Terbaik', desc: 'Harga sewa kompetitif dengan kualitas profesional' },
  ];

  return (
    <div className="min-h-screen bg-gradient-to-b from-slate-50 to-white">
      {/* Header */}
      <header className="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16">
            <div className="flex items-center gap-2">
              <Music2 className="w-8 h-8 text-blue-600" />
              <span className="text-slate-900">Kratak FC</span>
            </div>
            
            <nav className="hidden md:flex items-center gap-8">
              <a href="#home" className="text-slate-600 hover:text-blue-600 transition-colors">Home</a>
              <a href="#catalog" className="text-slate-600 hover:text-blue-600 transition-colors">Katalog</a>
              <a href="#about" className="text-slate-600 hover:text-blue-600 transition-colors">Tentang</a>
              <a href="#contact" className="text-slate-600 hover:text-blue-600 transition-colors">Kontak</a>
            </nav>

            <Button className="bg-blue-600 hover:bg-blue-700">
              Dashboard
            </Button>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section id="home" className="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
        <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDEzNGg4djJoLTh6bTAtMTBoOHYyaC04em0wLTEwaDh2MmgtOHptMC0xMGg4djJoLTh6bTAtMTBoOHYyaC04em0wLTEwaDh2MmgtOHoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-40"></div>
        
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
          <div className="text-center max-w-4xl mx-auto">
            <Badge className="mb-6 bg-white/20 text-white border-white/30 hover:bg-white/30">
              ✨ Sewa Alat Band Profesional
            </Badge>
            
            <h1 className="text-white mb-6 tracking-tight">
              Wujudkan Impian Bermusik Anda dengan Koleksi Alat Band Terbaik
            </h1>
            
            <p className="text-blue-100 mb-10 max-w-2xl mx-auto">
              Sewa alat musik berkualitas profesional untuk konser, latihan band, recording, atau acara spesial Anda. 
              Harga terjangkau, kualitas terjamin.
            </p>

            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Button size="lg" className="bg-white text-blue-600 hover:bg-blue-50 shadow-xl">
                <Search className="w-5 h-5 mr-2" />
                Lihat Katalog
              </Button>
              <Button size="lg" variant="outline" className="bg-transparent border-2 border-white text-white hover:bg-white/10">
                <Star className="w-5 h-5 mr-2" />
                Produk Unggulan
              </Button>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-3 gap-8 mt-16 max-w-2xl mx-auto">
              <div>
                <div className="text-white mb-1">500+</div>
                <div className="text-blue-200">Alat Musik</div>
              </div>
              <div>
                <div className="text-white mb-1">1000+</div>
                <div className="text-blue-200">Pelanggan</div>
              </div>
              <div>
                <div className="text-white mb-1">4.9/5</div>
                <div className="text-blue-200">Rating</div>
              </div>
            </div>
          </div>
        </div>

        <div className="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-slate-50 to-transparent"></div>
      </section>

      {/* Features Section */}
      <section className="py-16 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-3 gap-8">
            {features.map((feature, index) => (
              <div key={index} className="flex gap-4 items-start">
                <div className="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                  <feature.icon className="w-6 h-6 text-blue-600" />
                </div>
                <div>
                  <h3 className="text-slate-900 mb-2">{feature.title}</h3>
                  <p className="text-slate-600">{feature.desc}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Categories Section */}
      <section id="catalog" className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <Badge className="mb-4 bg-blue-100 text-blue-700 hover:bg-blue-200">
              Kategori
            </Badge>
            <h2 className="text-slate-900 mb-4">Kategori Alat Band</h2>
            <p className="text-slate-600 max-w-2xl mx-auto">
              Temukan alat band sesuai kebutuhan Anda dari berbagai kategori yang tersedia
            </p>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            {categories.map((category, index) => (
              <Card 
                key={index}
                className="p-6 hover:shadow-lg transition-all duration-300 cursor-pointer group border-slate-200 hover:border-blue-300 hover:-translate-y-1"
              >
                <div className="flex flex-col items-center text-center gap-3">
                  <div className="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center group-hover:from-blue-100 group-hover:to-indigo-200 transition-all">
                    <category.icon className="w-8 h-8 text-blue-600" />
                  </div>
                  <div>
                    <div className="text-slate-900 mb-1">{category.name}</div>
                    <div className="text-slate-500">{category.count}</div>
                  </div>
                </div>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Featured Products Section */}
      <section className="py-20 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <Badge className="mb-4 bg-amber-100 text-amber-700 hover:bg-amber-200">
              <Star className="w-3 h-3 mr-1" />
              Populer
            </Badge>
            <h2 className="text-slate-900 mb-4">Produk Unggulan</h2>
            <p className="text-slate-600 max-w-2xl mx-auto">
              Pilihan alat musik terpopuler dengan rating terbaik dari pelanggan kami
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {featuredProducts.map((product, index) => (
              <Card key={index} className="overflow-hidden hover:shadow-xl transition-all duration-300 group border-slate-200">
                <div className="aspect-square bg-gradient-to-br from-slate-100 to-slate-200 relative overflow-hidden">
                  <ImageWithFallback 
                    src={`https://source.unsplash.com/400x400/?${product.image}`}
                    alt={product.name}
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <div className="absolute top-3 right-3">
                    <Badge className="bg-white/90 text-slate-900 backdrop-blur">
                      {product.category}
                    </Badge>
                  </div>
                  {product.available && (
                    <div className="absolute top-3 left-3">
                      <Badge className="bg-green-500 text-white">
                        <Check className="w-3 h-3 mr-1" />
                        Tersedia
                      </Badge>
                    </div>
                  )}
                </div>
                <div className="p-5">
                  <div className="flex items-center gap-1 mb-2">
                    <Star className="w-4 h-4 fill-amber-400 text-amber-400" />
                    <span className="text-slate-900">{product.rating}</span>
                  </div>
                  <h3 className="text-slate-900 mb-3">{product.name}</h3>
                  <div className="flex items-end justify-between mb-4">
                    <div>
                      <div className="text-blue-600">{product.price}</div>
                      <div className="text-slate-500">{product.period}</div>
                    </div>
                  </div>
                  <Button className="w-full bg-blue-600 hover:bg-blue-700">
                    Lihat Detail
                    <ArrowRight className="w-4 h-4 ml-2" />
                  </Button>
                </div>
              </Card>
            ))}
          </div>

          <div className="text-center">
            <Button size="lg" variant="outline" className="border-2 border-blue-600 text-blue-600 hover:bg-blue-50">
              Lihat Semua Produk
              <ArrowRight className="w-5 h-5 ml-2" />
            </Button>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section id="about" className="py-20 bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 relative overflow-hidden">
        <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PHBhdGggZD0iTTM2IDEzNGg4djJoLTh6bTAtMTBoOHYyaC04em0wLTEwaDh2MmgtOHptMC0xMGg4djJoLTh6bTAtMTBoOHYyaC04em0wLTEwaDh2MmgtOHoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-40"></div>
        
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-2 gap-12 items-center">
            <div className="order-2 md:order-1">
              <Badge className="mb-4 bg-white/20 text-white border-white/30 hover:bg-white/30">
                Tentang Kami
              </Badge>
              <h2 className="text-white mb-6">
                Partner Terpercaya untuk Kebutuhan Alat Musik Anda
              </h2>
              <p className="text-emerald-50 mb-6">
                Kratak FC telah melayani ribuan musisi dan band sejak 2015. Kami menyediakan alat musik berkualitas 
                profesional dengan harga sewa yang terjangkau untuk semua kalangan.
              </p>
              <p className="text-emerald-50 mb-8">
                Setiap alat musik kami jaga dengan perawatan rutin dan profesional untuk memastikan performa terbaik 
                saat Anda gunakan. Kepuasan pelanggan adalah prioritas utama kami.
              </p>
              <div className="flex flex-wrap gap-4">
                <div className="flex items-center gap-2">
                  <div className="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <Check className="w-5 h-5 text-white" strokeWidth={2.5} />
                  </div>
                  <span className="text-white">Terawat Profesional</span>
                </div>
                <div className="flex items-center gap-2">
                  <div className="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <Check className="w-5 h-5 text-white" strokeWidth={2.5} />
                  </div>
                  <span className="text-white">Harga Terjangkau</span>
                </div>
                <div className="flex items-center gap-2">
                  <div className="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <Check className="w-5 h-5 text-white" strokeWidth={2.5} />
                  </div>
                  <span className="text-white">Layanan 24/7</span>
                </div>
              </div>
            </div>
            <div className="relative order-1 md:order-2">
              <div className="aspect-square rounded-2xl bg-white/10 backdrop-blur-sm overflow-hidden border border-white/20">
                <ImageWithFallback 
                  src="https://source.unsplash.com/600x600/?band,music,instruments"
                  alt="About Kratak FC"
                  className="w-full h-full object-cover"
                />
              </div>
              <div className="absolute -bottom-6 -right-6 w-32 h-32 bg-teal-700/50 rounded-2xl -z-10 backdrop-blur-sm"></div>
              <div className="absolute -top-6 -left-6 w-32 h-32 bg-cyan-700/50 rounded-2xl -z-10 backdrop-blur-sm"></div>
            </div>
          </div>
        </div>
      </section>

      {/* Contact Section */}
      <section id="contact" className="py-20 bg-gradient-to-br from-orange-500 via-pink-500 to-rose-600 relative overflow-hidden">
        <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PHBhdGggZD0iTTM2IDEzNGg4djJoLTh6bTAtMTBoOHYyaC04em0wLTEwaDh2MmgtOHptMC0xMGg4djJoLTh6bTAtMTBoOHYyaC04em0wLTEwaDh2MmgtOHoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-40"></div>
        
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <Badge className="mb-4 bg-white/20 text-white border-white/30 hover:bg-white/30">
              Hubungi Kami
            </Badge>
            <h2 className="text-white mb-4">Siap Membantu Anda</h2>
            <p className="text-orange-50 max-w-2xl mx-auto">
              Tim kami siap menjawab pertanyaan dan membantu Anda menemukan alat musik yang tepat
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div className="p-8 text-center bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 hover:bg-white/15 transition-all">
              <div className="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-4">
                <Phone className="w-8 h-8 text-white" strokeWidth={2} />
              </div>
              <h3 className="text-white mb-2">Telepon</h3>
              <p className="text-orange-100 mb-3">Hubungi kami langsung</p>
              <a href="tel:+6281234567890" className="text-white hover:underline">
                +62 812-3456-7890
              </a>
            </div>

            <div className="p-8 text-center bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 hover:bg-white/15 transition-all">
              <div className="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-4">
                <Mail className="w-8 h-8 text-white" strokeWidth={2} />
              </div>
              <h3 className="text-white mb-2">Email</h3>
              <p className="text-orange-100 mb-3">Kirim pesan ke kami</p>
              <a href="mailto:info@kratakfc.com" className="text-white hover:underline">
                info@kratakfc.com
              </a>
            </div>

            <div className="p-8 text-center bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 hover:bg-white/15 transition-all">
              <div className="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-4">
                <MapPin className="w-8 h-8 text-white" strokeWidth={2} />
              </div>
              <h3 className="text-white mb-2">Lokasi</h3>
              <p className="text-orange-100 mb-3">Kunjungi showroom kami</p>
              <p className="text-white">Jakarta Selatan</p>
            </div>
          </div>
        </div>
      </section>

      {/* Why Choose Us Section */}
      <section className="py-20 bg-gradient-to-br from-purple-600 via-purple-500 to-pink-500 relative overflow-hidden">
        <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PHBhdGggZD0iTTM2IDEzNGg4djJoLTh6bTAtMTBoOHYyaC04em0wLTEwaDh2MmgtOHptMC0xMGg4djJoLTh6bTAtMTBoOHYyaC04em0wLTEwaDh2MmgtOHoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-40"></div>
        
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-white mb-4">Mengapa Memilih Kami?</h2>
            <p className="text-purple-100 max-w-2xl mx-auto">
              Keunggulan layanan kami
            </p>
          </div>

          <div className="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div className="text-center">
              <div className="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-6">
                <div className="w-16 h-16 rounded-full bg-white/30 flex items-center justify-center">
                  <Check className="w-8 h-8 text-white" strokeWidth={3} />
                </div>
              </div>
              <h3 className="text-white mb-3">Kualitas Terjamin</h3>
              <p className="text-purple-100">
                Semua alat band dalam kondisi prima dan terawat dengan baik
              </p>
            </div>

            <div className="text-center">
              <div className="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-6">
                <div className="w-16 h-16 rounded-full bg-white/30 flex items-center justify-center">
                  <svg className="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                </div>
              </div>
              <h3 className="text-white mb-3">Harga Terjangkau</h3>
              <p className="text-purple-100">
                Sistem sewa dengan harga yang kompetitif dan fleksibel
              </p>
            </div>

            <div className="text-center">
              <div className="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-6">
                <div className="w-16 h-16 rounded-full bg-white/30 flex items-center justify-center">
                  <svg className="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2.5}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
              </div>
              <h3 className="text-white mb-3">Layanan 24/7</h3>
              <p className="text-purple-100">
                Customer service siap membantu kapan saja Anda membutuhkan
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-slate-900 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid md:grid-cols-4 gap-8 mb-8">
            <div>
              <div className="flex items-center gap-2 mb-4">
                <Music2 className="w-8 h-8 text-blue-400" />
                <span className="text-white">Kratak FC</span>
              </div>
              <p className="text-slate-400">
                Partner terpercaya untuk sewa alat musik berkualitas profesional.
              </p>
            </div>

            <div>
              <h4 className="text-white mb-4">Kategori</h4>
              <ul className="space-y-2 text-slate-400">
                <li><a href="#" className="hover:text-blue-400 transition-colors">Gitar</a></li>
                <li><a href="#" className="hover:text-blue-400 transition-colors">Bass</a></li>
                <li><a href="#" className="hover:text-blue-400 transition-colors">Drum</a></li>
                <li><a href="#" className="hover:text-blue-400 transition-colors">Keyboard</a></li>
              </ul>
            </div>

            <div>
              <h4 className="text-white mb-4">Perusahaan</h4>
              <ul className="space-y-2 text-slate-400">
                <li><a href="#" className="hover:text-blue-400 transition-colors">Tentang Kami</a></li>
                <li><a href="#" className="hover:text-blue-400 transition-colors">Syarat & Ketentuan</a></li>
                <li><a href="#" className="hover:text-blue-400 transition-colors">Kebijakan Privasi</a></li>
                <li><a href="#" className="hover:text-blue-400 transition-colors">FAQ</a></li>
              </ul>
            </div>

            <div>
              <h4 className="text-white mb-4">Ikuti Kami</h4>
              <div className="flex gap-3">
                <a href="#" className="w-10 h-10 rounded-full bg-slate-800 hover:bg-blue-600 flex items-center justify-center transition-colors">
                  <Facebook className="w-5 h-5" />
                </a>
                <a href="#" className="w-10 h-10 rounded-full bg-slate-800 hover:bg-blue-600 flex items-center justify-center transition-colors">
                  <Instagram className="w-5 h-5" />
                </a>
                <a href="#" className="w-10 h-10 rounded-full bg-slate-800 hover:bg-blue-600 flex items-center justify-center transition-colors">
                  <Mail className="w-5 h-5" />
                </a>
              </div>
            </div>
          </div>

          <div className="border-t border-slate-800 pt-8 text-center text-slate-400">
            <p>© 2025 Kratak FC. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  );
}