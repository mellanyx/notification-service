# 📬 Notification Service (Laravel + RabbitMQ + MySQL)

Простой сервис уведомлений, реализованный на **Laravel (PHP 8.4)** с использованием **RabbitMQ** и **MySQL**.  
Сервис позволяет сохранять уведомления в базу данных двумя способами:
1. Через очередь RabbitMQ (консьюмер обрабатывает сообщения).
2. Через HTTP API (`POST /notifications`).

Также предусмотрен эндпоинт для получения списка уведомлений (`GET /notifications`).

---

## 🚀 Возможности
- Приём уведомлений через RabbitMQ
- Приём уведомлений через HTTP API
- Хранение уведомлений в MySQL
- Просмотр списка всех уведомлений через REST API
- Docker-окружение для быстрого запуска

---

## 📂 Структура проекта

```
├── app/
│ ├── Console/Commands/ConsumeNotifications.php # Консьюмер RabbitMQ
│ ├── Console/Commands/SendNotifications.php # Сендер уведомления в RabbitMQ
│ ├── Http/Controllers/NotificationController.php # Контроллер API
│ ├── Models/Notification.php # Модель
├── database/migrations/xxxx_create_notifications_table.php
├── docker-compose.yml
├── Dockerfile
├── README.md
└── ...
```

---

## ⚙️ Установка и запуск

### 1. Клонирование репозитория
```bash
git clone https://github.com/your-repo/notification-service.git
cd notification-service
```

### 2. Запуск Docker-контейнеров
```bash
docker-compose up -d --build
```

### 3. Выполнение миграций
```bash
docker exec -it notification-service-app php artisan migrate
```

### 4. Генерация ключа приложения
```bash
docker exec -it notification-service-app php artisan key:generate
```

---

## 🔌 API

### Создание уведомления

POST /api/notifications

Пример запроса:
```json
{
    "sender_email": "test@test.com",
    "recipient_email": "user@mail.com",
    "message": "Hello!"
}
```

Ответ (201):
```json
{
    "sender_email": "hello@example.com",
    "recipient_email": "test@mail.ru",
    "message": "message",
    "updated_at": "2025-10-02T18:38:35.000000Z",
    "created_at": "2025-10-02T18:38:35.000000Z",
    "id": 1
}
```

### Получение списка уведомлений

GET /api/notifications

Ответ (200):
```json
[
    {
        "id": 1,
        "sender_email": "hello@example.com",
        "recipient_email": "test@mail.ru",
        "message": "Test_1",
        "created_at": "2025-10-02T08:32:51.000000Z",
        "updated_at": "2025-10-02T08:32:51.000000Z"
    },
    {
        "id": 2,
        "sender_email": "hello@example.com",
        "recipient_email": "test@mail.ru",
        "message": "Test_2",
        "created_at": "2025-10-02T18:38:35.000000Z",
        "updated_at": "2025-10-02T18:38:35.000000Z"
    },
    {
        "id": 3,
        "sender_email": "hello@example.com",
        "recipient_email": "test@mail.ru",
        "message": "Test_3",
        "created_at": "2025-10-02T18:42:44.000000Z",
        "updated_at": "2025-10-02T18:42:44.000000Z"
    }
]
```

---

## 🐇 RabbitMQ

### 1. Запуск консьюмера
```bash
docker exec -it notification-service-app php artisan notification:consume
```

### 2. Отправка сообщения в очередь
```bash
docker exec -it notification-service-app php artisan notification:send {msg} {recipient_email}
```

---

## 🔗 Сервисы

* Laravel API → http://localhost:8000/api/notifications
* RabbitMQ UI → http://localhost:15672
(guest/guest)
* MySQL → localhost:33077 (user: laravel, password: secret)

---

## 🛠 Стек

* PHP 8.4 / Laravel
* MySQL 8
* RabbitMQ 3 (management)
* Docker / Docker Compose
