# Contributing

Thanks for considering a contribution. Here's how to get started.

## Setup

```bash
git clone git@github.com:conduit-ui/calendar.git
cd calendar
composer install
```

## Development

### Run tests

```bash
vendor/bin/pest
```

### Code style

```bash
vendor/bin/pint
```

### Static analysis

```bash
vendor/bin/phpstan analyse src --level=5
```

## Pull Requests

1. Fork the repo and create a feature branch from `main`
2. Write tests for new functionality
3. Run `vendor/bin/pint` before committing
4. Open a PR against `main`

## Adding a New Driver

1. Create a new directory under `src/Drivers/` (e.g., `Outlook/`)
2. Implement `CalendarProvider` interface
3. Add a Saloon connector + request classes
4. Register the driver in `CalendarManager::resolve()`
5. Add config entry in `config/calendar.php`
6. Write tests using Saloon's `MockClient`

## Conventions

- PHP 8.2+ with strict types
- Pest for testing (BDD syntax: `describe`/`it`)
- Pint for formatting (Laravel preset)
- Spatie Laravel Data for DTOs
- Saloon for HTTP
