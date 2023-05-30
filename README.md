TOTK-Recipe-Calculator
====
## Before you start

Before you start using this Library, you **need** to know how PHP works. This is a fundamental requirement before you start. Without this knowledge, you will only suffer.

## FAQ

1. Can I run TOTK-Recipe-Calculator on a webserver (e.g. Apache, nginx)?
    - Yes! I'm also working on a version that is compatible with the DiscordPHP Library that will only run in CLI. If that's what you're looking for, you can find it [here](https://github.com/VZGCoders/TOTK-Recipe-Calculator-Bot) (unless it's still in development, in which case the repository is still set to private).

## Getting Started

### Requirements

- PHP 8.0
- Composer

#### Recommended Extensions

- The latest PHP version.

### Basic Configuration
The Civ13 class intends to streamline the development process when using the DiscordPHP and other libraries while simultaneously avoiding bloating the main bot.php file with a bunch of function definitions. It accomplishes this by defining functions as variables and passing them into the construction method at runtime via the declaration of an $options array. Functions are to be declared either according to the DiscordPHP event they should execute and in the order they should be executed or as a miscellaneous function that can simply be stored and referenced later.

```php
use \TOTK\Crafter;
use \TOTK\Helpers\Collection;
use \TOTK\Parts\Ingredient;

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
ignore_user_abort(1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1'); //Unlimited memory usage
if (! include getcwd() . '/vendor/autoload.php') { //If you clone the GitHub project instead of using Composer we will need to load these classes manually
    include __DIR__ . '/src/TOTK/crafter.php';
    include __DIR__ . '/src/TOTK/Helpers/collection.php';
    include __DIR__ . '/src/TOTK/Parts/ingredient.php';
}

//This is what will be used to calculate meals from a list of ingredients
$crafter = new Crafter();

//For ease of use, create a Collection to retrieve a list of Ingredients
if (! $materials_file = file(__DIR__ . '\vendor\vzgcoders\totk-recipe-calculator\src\TOTK\CSVs\materials.csv')) $materials_file = file(__DIR__ . '\src\TOTK\CSVs\materials.csv');
$csv = array_map('str_getcsv', $materials_file);
$keys = array_shift($csv);
$materials = array();
foreach ($csv as $row) $materials[] = array_combine($keys, $row);
$materials_collection = new Collection([], $keys[2]);
foreach ($materials as $array) $materials_collection->pushItem($array);

// Pick out some ingredients for your recipe. This is an example for Fruitcase.
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Apple'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Wildberry'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Cane Sugar'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Tabantha Wheat'));
$ingredient5 = null;

//Throw the array into the crafter and display the results
$ingredients = [$ingredient1 ?? NULL, $ingredient2 ?? NULL, $ingredient3 ?? NULL, $ingredient4 ?? NULL, $ingredient5 ?? NULL];
var_dump('[MEAL]', $meal = $crafter->process($ingredients));
```

See [main.php](main.php) for function examples.

## Contributing

We are open to contributions, just open a pull request and we will review it.

## License

MIT License, &copy; Valithor Obsidion and other contributers 2023-present.
