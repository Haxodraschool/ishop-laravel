# 🛍️ iShop - Laravel E-commerce Project

Dự án website bán hàng được xây dựng bằng Laravel, đã được tối ưu hóa để triển khai (deploy) lên **Railway** bằng Docker.

## 🚀 Hướng dẫn Deployment lên Railway

Dự án này đã bao gồm đầy đủ cấu hình Docker và Railway.

### 1. Các file cấu hình quan trọng
- **`Dockerfile`**: Sử dụng PHP 8.4-fpm-alpine, tối ưu hóa multi-stage build để giảm dung lượng image.
- **`railway.json`**: Cấu hình healthcheck qua route `/health` để đảm bảo quá trình deploy ổn định.
- **`docker/entrypoint.sh`**: Tự động nhận diện Port, chạy migration, cache ứng dụng và khởi động Supervisor.
- **`docker/nginx/default.conf`**: Cấu hình Nginx tối ưu cho Laravel.

### 2. Biến môi trường (Variables)
Cần thiết lập các biến sau trên Railway:

```text
APP_NAME=ishop
APP_ENV=production
APP_KEY=base64:klClw9pN74x07EAUhUoZC+XqYrKinDhI8aCSt+8tnUQ=
APP_DEBUG=false
APP_URL=https://your-app-url.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=ExvRoDSEDtmdzXQgorHOxXJhCAvEfhCU

FILESYSTEM_DISK=public
LOG_CHANNEL=errorlog
```

### 3. Cấu hình Volume (Lưu trữ ảnh)
Để ảnh sản phẩm không bị mất khi deploy lại, hãy gắn một Volume trên Railway:
- **Mount Path**: `/var/www/html/storage/app/public`

---

## 🗄️ Quản lý Database

### Kết nối Database Cloud (MySQL)
Sử dụng TablePlus hoặc DBeaver với các thông số:
- **Host**: `viaduct.proxy.rlwy.net`
- **Port**: `31617`
- **User**: `root`
- **Password**: `ExvRoDSEDtmdzXQgorHOxXJhCAvEfhCU`
- **Database**: `railway`

### Lệnh đồng bộ dữ liệu từ Local lên Cloud
Chạy lệnh này tại terminal máy local để đẩy toàn bộ dữ liệu hiện tại lên Cloud:

```bash
mysqldump -u root ishop | mysql -h viaduct.proxy.rlwy.net -P 31617 -u root -pExvRoDSEDtmdzXQgorHOxXJhCAvEfhCU railway
```

---

## 🛠️ Phát triển cục bộ (Local)

1. Clone dự án.
2. Chạy `composer install` và `npm install`.
3. Cấu hình `.env` trỏ về MySQL local.
4. Chạy `php artisan migrate --seed`.
5. Chạy `php artisan serve` và `npm run dev`.

---

## 📄 Giấy phép
Dự án được phát triển dựa trên Laravel Framework (MIT License).
