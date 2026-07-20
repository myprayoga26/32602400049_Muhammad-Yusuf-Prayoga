# LITERIA - Premium Digital Library

## 📚 Tentang LITERIA

**LITERIA** adalah platform perpustakaan digital premium yang menghadirkan pengalaman katalog perpustakaan privat dengan koleksi buku yang benar-benar terkurasi. Dibangun dengan fokus pada estetika, pengalaman pengguna yang mulus, dan fungsionalitas yang mendalam.

### ✨ Fitur Utama

- **🎨 Desain Premium** - Tampilan elegan dengan dark mode support
- **📖 Editor's Pick Carousel** - Sorotan koleksi pilihan dengan efek coverflow
- **🔍 Pencarian Real-time** - Temukan buku berdasarkan judul atau penulis
- **🏷️ Filter Kategori** - Navigasi koleksi berdasarkan genre
- **📱 PWA Support** - Install sebagai aplikasi di perangkat Anda
- **🌙 Dark Mode** - Pengalaman membaca yang nyaman di segala kondisi
- **📊 Statistik Koleksi** - Informasi jumlah buku, stok, dan kategori

## 🚀 Teknologi

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![PWA](https://img.shields.io/badge/PWA-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)

</div>

- **Frontend**: TailwindCSS, Alpine.js, Swiper.js
- **Icons**: Phosphor Icons
- **Font**: Inter + Lora
- **Animations**: CSS Transitions & Scroll Reveal
- **Service Worker**: PWA ready

## 📦 Instalasi

```bash
# Clone repository
git clone https://github.com/yourusername/literia.git

# Masuk ke direktori
cd literia

# Install dependencies (jika menggunakan Composer)
composer install

# Copy environment file
cp .env.example .env

# Generate key
php spark key:generate

# Jalankan server development
php spark serve
```

### Prasyarat

- PHP 7.4+
- MySQL 5.7+
- Composer
- Node.js (optional, untuk development assets)

## 🎨 Palet Warna

| Warna | Hex | Penggunaan |
|-------|-----|------------|
| Ivory 100 | `#FFFEFA` | Background utama |
| Ivory 200 | `#F7F1E3` | Background sekunder |
| Navy 900 | `#002925` | Teks utama, dark mode |
| Gold 500 | `#AC554C` | Aksen utama |
| Gold 600 | `#C5A556` | Aksen sekunder |

## 📁 Struktur Kode

```
literia/
├── assets/
│   └── images/
│       └── cinematic_library.png
├── app/
│   ├── Views/
│   │   ├── web/
│   │   │   └── _catalog_list.php
│   │   └── header.php
├── public/
│   ├── sw.js
│   └── manifest.json
└── README.md
```

## 🔧 Konfigurasi

### Tailwind Customization

```javascript
tailwind.config = {
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
                serif: ['Lora', 'serif'],
            },
            colors: {
                ivory: { /* ... */ },
                navy: { /* ... */ },
                gold: { /* ... */ }
            }
        }
    }
}
```

### CSS Variables

```css
:root {
    --color-ivory-100: 255 254 250;
    --color-navy-900: 0 41 37;
    --bg-body: #f7f1e3;
    /* ... */
}

.dark {
    --color-ivory-100: 30 41 59;
    --bg-body: #0f172a;
    /* ... */
}
```

## 📱 PWA Support

LITERIA siap digunakan sebagai Progressive Web App:

- **Manifest**: `/manifest.json`
- **Service Worker**: `/sw.js`
- **Theme Color**: `#002925`
- **Offline Support**: Ya (dengan cache strategi)

## 🧩 Komponen Utama

### 1. Hero Section
- Background cinematic library
- Overlay gradient
- Statistik koleksi real-time
- CTA buttons

### 2. Editor's Pick Carousel
- Swiper.js dengan efek coverflow
- Autoplay
- Active slide scaling
- Informasi buku (judul, pengarang, rating)

### 3. Koleksi Kategori
- Grid layout
- Count per kategori
- Filter interaktif

### 4. Katalog Buku
- Pencarian real-time
- Filter kategori
- Filter akses (readable)
- Responsive grid

## 🌟 Best Practices

- ✅ Semantic HTML5
- ✅ CSS Custom Properties untuk theming
- ✅ Lazy loading images
- ✅ Scroll reveal animations
- ✅ Performance optimized
- ✅ Accessible (ARIA labels)
- ✅ Reduced motion support
- ✅ Dark mode preference

## 🛠️ Development

```bash
# Watch for changes (Tailwind)
npx tailwindcss -i ./src/input.css -o ./public/css/output.css --watch

# Build for production
npx tailwindcss -i ./src/input.css -o ./public/css/output.css --minify
```


## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.


---

<div align="center">
  <sub>Built by LITERIA Team</sub>
  <br/>
  <sub>© 2026 LITERIA. Pengetahuan, Terstruktur.</sub>
</div>
