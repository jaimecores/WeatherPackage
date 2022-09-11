# Introduction
Laravel Package to let us know the 5-day weather forecast for a guest identified only by IP address.

## Requirements:
- Must be a standalone Laravel package, to be loaded via Composer -> DONE
- Can use any API or service to accomplish the task -> DONE
- Must use an icon or graphic to represent the weather forecasted -> DONE
- Identify the Global location -> DONE
- Display initial IP address in a form field and for use for manual import -> DONE
- As a minimum provide two different drivers for any API or service used -> DONE
- Aim to avoid unnecessary repeat hits to any API or service used -> DONE
- Results must be saved in the database across multiple tables -> DONE
- Must return the data to be used elsewhere in the application -> DONE
- Must also return data when called via the CLI -> DONE
- All code must be source controlled and we must be provided access to review your code -> DONE

## Bonuses:
- The package is testable -> DONE
- The package is documented -> DONE
- Display a log of all results run with an embedded map of the location
- Compute and save differences in forecast data over time for a given location -> DONE
- The package is translatable -> DONE

### Package installation
- Clone repository: `git clone https://github.com/jaimecores/weatherpackage.git`
- Install dependencies: `composer install`
- Testing: `composer test`

### Installion in a Laravel app

- Require package: `composer require jaimecores/weatherpackage`
- Install package: `php artisan weatherpackage:install`

#### Optional

- Publish the package assets: `php artisan vendor:publish --provider="JaimeCores\WeatherPackage\WeatherPackageServiceProvider" --tag="tagName"` (tagName = config, migrations, views, assets)

- Return data when called via the CLI: `php artisan weatherpackage:getweather IPAddress` (E.g. IPAddress = 123.211.61.50)