# T.U.D.L.O. Online Tutor Booking App | CAPSTONE PROJECT 2024

## 🚀 How to Install

### Backend
1. `cd backend`
2. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. `php artisan storage:link`
6. `php artisan migrate --seed` (or `php artisan migrate:fresh --seed`)
7. `php artisan serve`

### Frontend
1. `cd frontend`
2. `npm install`
3. `npm run dev`

### CMS
1. `cd cms`
2. `npm install`
3. `npm run dev`

## 💻 Technologies Used

### Backend
- [Laravel](https://laravel.com/) - Backend framework
- [Laravel Sanctum](https://laravel.com/docs/sanctum) - API authentication

### Frontend
- [Vue.js](https://vuejs.org/) - Frontend framework
- [Axios](https://axios-http.com/) - HTTP requests
- [Vue Cal](https://antoniandre.github.io/vue-cal/) - Booking and calendar scheduling
- [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS framework
- [Preline UI](https://preline.co/) - UI components and styling

### To Be Implemented
#### Backend
- [Laravel Broadcasting](https://laravel.com/docs/broadcasting) - Real-time events

#### Frontend
- [Swiper.js](https://swiperjs.com/) - Carousel functionality
