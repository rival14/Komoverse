## Installation

1. Run `composer install`
2. Set up `.env` file (change db name, username, password if any)
3. Run `php artisan migrate`
4. Run `php artisan db:seed --class=UserSeeder`
4. Run `php artisan db:seed --class=HistorySubmitScoreSeeder`
5. Run `php artisan serve`

## API Endpoints

https://www.postman.com/sadmin-team/workspace/rival-private/collection/17463654-c6dc337f-9176-4bbf-bdf1-2a72e57bd6ec?action=share&creator=17463654

# Task 1
- `POST /api/submit-score`: Submit score for a user
- `GET /api/leaderboard`: Get leaderboard (with optional `username` parameter)

# Task 2
- `GET /api/submit-assessment`: Submit leaderboard (with optional `username` parameter)
