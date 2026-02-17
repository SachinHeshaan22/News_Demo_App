# News App Backend (Laravel)

This is the backend API for the News App built with Laravel and MySQL.

## Features

- **Complete CRUD Operations**: Create, Read, Update, Delete news items
- **Image Upload**: Support for image uploads with validation
- **Validation**: Comprehensive input validation
- **Publish/Unpublish**: Status management for news items
- **RESTful API**: Clean, standardized API endpoints
- **CORS Support**: Configured for frontend communication

## API Endpoints

### News Endpoints

- `GET /api/news` - Get all news items
- `POST /api/news` - Create a new news item
- `GET /api/news/{id}` - Get a specific news item
- `PUT/PATCH /api/news/{id}` - Update a news item
- `DELETE /api/news/{id}` - Delete a news item
- `PATCH /api/news/{id}/publish` - Publish a news item
- `PATCH /api/news/{id}/unpublish` - Unpublish a news item

## Validation Rules

### Required Fields
- **date**: Required, must be a valid date, cannot be in the future
- **title**: Required, max 200 characters
- **category**: Required, must be one of: technology, business, sports, entertainment, health, science, politics, world
- **url**: Required, must be a valid URL, max 500 characters
- **image**: Required for creation, optional for updates. Must be image file (jpeg, png, gif, webp), max 5MB

### Optional Fields
- **status**: Can be 'published' or 'unpublished' (defaults to 'unpublished')

## Setup Instructions

1. **Database Setup**
   - Create a MySQL database named `news_app`
   - Update `.env` file with your database credentials

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Run Migrations**
   ```bash
   php artisan migrate
   ```

4. **Seed Database** (Optional)
   ```bash
   php artisan db:seed --class=NewsSeeder
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```

## Database Structure

### News Table
- `id` - Primary key
- `date` - News date
- `title` - News title (max 200 chars)
- `category` - News category
- `url` - News URL
- `image_url` - Image URL (nullable)
- `status` - Publication status (published/unpublished)
- `created_at` - Timestamp
- `updated_at` - Timestamp

## CORS Configuration

The backend is configured to accept requests from:
- `http://localhost:5173`
- `http://127.0.0.1:5173`

## Error Handling

All API responses follow a consistent format:
```json
{
  "success": true/false,
  "data": {...}, // for successful requests
  "message": "Description of the response",
  "errors": {...} // for validation errors
}
```

## File Uploads

Images are stored in `storage/app/public/news_images/` and accessible via `storage/news_images/` after running:
```bash
php artisan storage:link
```

## Testing

You can test the API endpoints using the provided test script:
```bash
php test_api.php
```

## Requirements

- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.2+
- Composer
- PDO MySQL extension enabled

## Frontend Integration

The frontend is configured to communicate with this backend at `http://localhost:8000/api`.