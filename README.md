# AutoTranslationShopCSV
This project show how to use the google translation API for free with proxies and how to use Doctrine ORM, CSV reader, translate and store multilanguage

### Requeriments
*	PHP 7.* installed
*	composer installed
*	Database installed (could be anyone in [Drivers list](https://www.doctrine-project.org/projects/doctrine-dbal/en/2.10/reference/configuration.html))

### STEPS
#### Step 1

```cmd
> composer update
```
#### Step 2

```
Edit .env file with your personal configuration
```
#### Step 3

```cmd
> .\vendor\bin\doctrine orm:schema-tool:create
```
#### Step 4

```cmd
> .\vendor\bin\doctrine orm:schema-tool:update
```

#### Step 5

```cmd
> php index.php
```

#### Step 6

```
Use your personal database editor to show the 'example.csv' result in the database.
It created a 'Category' for the row with only one column non empty and otherwise a 'Product'.

For more info inspect index.php
```