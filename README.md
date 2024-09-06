# MyEdSpace Tutor Manager

## Setup Instructions

### Prerequisites

- Docker (for PHP, database)
- Node.js 18.x (for Vite build)
- Composer 2.x
- Git

### Installation

1.**Clone the repository**:

```bash
git clone https://github.com/ngoviethungdn/myedspace-tutor-manager.git
cd myedspace-tutor-manager
```

2.**Set up environment variables**:

Copy the `.env.example` to `.env` and configure your environment variables as needed.

```bash
cp .env.example .env
```

Ensure the following variables are correctly set in the `.env` file:

```bash
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=tutor_manager
DB_USERNAME=root
DB_PASSWORD=root
APP_URL=http://127.0.0.1:8080
```

3.**Start Docker containers**:

```bash
docker-compose up -d
```

4.**Install PHP and JavaScript dependencies**:

```bash
docker-compose exec app composer install
npm install
```

5.**Build Vite assets**:

```bash
npm run build
```

6.**Run migrations**:

```bash
docker-compose exec app php artisan migrate
```

7.**Generate the application key**:

```bash
docker-compose exec app php artisan key:generate
```

### Usages

- Admin pannel: <http://127.0.0.1:8080/admin>
- Tutor search: <http://127.0.0.1:8080>

### Running Tests

1.**Run unit and feature tests**:

```bash
docker-compose exec app php artisan test
```

2.**Check code coverage**:

```bash
docker-compose exec app php artisan test --coverage
```

### Design Decisions & Architectural Choices

- **Livewire Components**: We utilize Livewire for interactive frontend components (e.g., tutor search) to simplify the process of building dynamic UIs.
- **Filament Resources**: Filament is used for managing admin panel functionalities like creating, editing, and deleting resources.
- **Database**: MySQL is used for its robust relational data handling, supporting features like JSON fields for subjects and indexing.
- **SQLite for Tests**: SQLite is used for faster unit tests, except for JSON operations, which require MySQL.

### Assumptions

- **No real-time communication**: While Livewire enables real-time UI updates, there’s no need for WebSockets or Redis-based queues for this task.

### Future Improvements

1.**Service Layer**: All business logic is handled using a service layer, separating concerns and improving testability.
2.**Caching**: Implement caching for searching Tutor result.
3.**User Roles**: Add different user roles (admin, tutor, student) with permission control.

### Scalability Considerations

- **Caching**: Using redis for caching.
- **Lazy Loading**: Where possible, avoid eager loading in large lists to prevent memory overloads.
