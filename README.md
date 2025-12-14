# 🎄 Innomytree - Dijital Yılbaşı Ağacı

**Innomytree**, kullanıcıların kendi dijital yılbaşı ağaçlarını oluşturup arkadaşlarıyla paylaşabildiği ve onlardan "süs" (gizli mesajlar) toplayabildiği interaktif bir web uygulamasıdır. Popüler **Decomytree** konseptinin bir klonu olarak geliştirilmiştir.

## 🌟 Özellikler

*   **Kişiselleştirilebilir Ağaçlar:** Kullanıcılar kayıt olurken farklı ağaç tiplerinden (Mavi, Kahverengi, Yeşil, Kırmızı, Inno) birini seçebilir.
*   **Gizli Mesajlar (Süsler):** Arkadaşlarınız ağacınıza süs bırakarak size mesaj gönderebilir.
*   **Noel Kilidi:** Bırakılan mesajlar **25 Aralık 2025** tarihine kadar kilitlidir ve okunamaz. Geri sayım sayacı ile heyecan canlı tutulur.
*   **Admin Paneli:** Site istatistiklerini görüntülemek ve kullanıcıları yönetmek için gelişmiş bir admin paneli bulunur.
*   **Güvenlik:**
    *   Giriş ve Admin panellerinde matematiksel CAPTCHA koruması.
    *   Güvenli oturum yönetimi ve şifreleme (bcrypt).
    *   PDO ile SQL Injection koruması.
*   **Responsive Tasarım:** Mobil ve masaüstü cihazlarla tam uyumlu modern arayüz.

## 🛠️ Teknolojiler

*   **Backend:** PHP 8.x
*   **Veritabanı:** MySQL
*   **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
*   **Sunucu:** Apache / Nginx (WampServer üzerinde geliştirildi)

## 🚀 Kurulum

Projeyi kendi sunucunuzda veya local ortamınızda çalıştırmak için aşağıdaki adımları izleyin:

1.  **Dosyaları İndirin:** Bu repoyu klonlayın veya zip olarak indirin.
2.  **Veritabanını Oluşturun:**
    *   `database.sql` dosyasını phpMyAdmin veya benzeri bir araçla veritabanınıza içe aktarın.
    *   Bu işlem `innomist_mytree` adında bir veritabanı ve gerekli tabloları oluşturacaktır.
3.  **Veritabanı Bağlantısını Yapılandırın:**
    *   `includes/db.example.php` dosyasının adını `includes/db.php` olarak değiştirin.
    *   Dosyayı açın ve veritabanı bilgilerinizi (host, dbname, username, password) girin.

    ```php
    // includes/db.php
    $host = 'localhost';
    $dbname = 'innomist_mytree';
    $username = 'root'; // Kendi kullanıcı adınız
    $password = '';     // Kendi şifreniz
    ```

## 📂 Dosya Yapısı

*   `admin/` - Yönetici paneli dosyaları.
*   `assets/` - Görseller, logolar ve süs ikonları.
*   `css/` - Stil dosyaları (`style.css`, `metree.css`, `friend.css`).
*   `includes/` - Veritabanı bağlantı dosyası.
*   `index.php` - Ana sayfa.
*   `me_tree.php` - Kullanıcının kendi ağacını görüntülediği sayfa.
*   `friend_tree.php` - Başkasının ağacına mesaj bırakılan sayfa.
*   `login.php`, `register.php` vb. - Kimlik doğrulama sayfaları.

## 👤 Admin Girişi

Varsayılan admin hesabı veritabanı kurulumu ile birlikte gelir:
*   **Kullanıcı Adı:** `admin`
*   **Şifre:** `admin123`

## 📄 Lisans

Bu proje açık kaynaklıdır ve eğitim/hobi amaçlı geliştirilmiştir.

---
**Geliştirici:** [oguzkaanekin](https://oguzkaanekin.site) | **UI Tasarım:** Furkan Demirbaş
