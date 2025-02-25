# Snappy Shopper PHP Code Test

This code covers the required points as outlined in the test document. It has been completed using only 
API routes, there are no front end pages available to view.

Below contains information that will help with the setup and testing the code.

The available API routes with examples of expected data are also listed.

At the end of file I list the areas I would want to improve, add or investigate further if I were to spend
additional time on this test.

## Setting up the test locally

 - Clone this repo
 - composer install
 - create .env with relevant MySQL Database details to an empty DB
 - php artisan key:generate
 - php artisan migrate
 - php artisan db:seed --class=ShopTypeSeeder
 - php artisan serve

# Test points

## Console command to download and import UK postcodes (e.g. http://parlvid.mysociety.org/os/) into some kind of database

I have created a console command as requested to do this. The command is:

php artisan app:import-uk-postcode-data

My current laptop is a Windows machine, so it can have issues with the location of the file missing slashes
when running the MySQL LOAD DATA LOCAL INFILE, if this the case for you, you can use addslashes() method
around the storage_path() in ImportUkPostcodeData.php line 77. I have removed this from the repo
as Linux should process correctly.

## A controller action to add a new store/shop to the database

I have created a POST API endpoint (/api/shop) for this point. Required information is:
 
 - name 
 - postcode
 - opening_time
 - closing_time
 - shop_type_id (1 = Takeaway, 2 = Shop, 3 = Restaurant)
 - max_delivery_km

Additional endpoints include:

 - /api/shop - GET for list of all shops
 - /api/shop/{$id} - GET for retrieval of a specific shop
 - /api/shop/{$id} - DELETE for removal of a specific shop

## Controller action to return stores near to a postcode

I have created a POST API endpoint (/api/localShops). POST request values:

 - postcode (required)
 - distance (optional, in km)

## Controller action to return stores can deliver to a certain postcode

I have created a POST API endpoint (/api/availableLocalShops). POST request values:

 - postcode (required)

# Areas for improvement/further investigation

- Finish the test cases - Due to not doing them before, I built the tests after creating the controller logic, which I now know is the incorrect way to do things if following TDD principles. I stopped at the end of the shop create/destroy tests, but would continue to test the other API end points. I chose to stop there as it would be similar logic to the examples provided and I wanted to use the remaining time elsewhere on the test.
- Investigate optimisations to the data import process, perhaps using the smaller csv files and getting only the required columns from it
- There is no real handling of any of the steps failing
- Adding auth to the API endpoints
- Investigate rate limiting on API requests
- More detailed method descriptions with return types/declarations
- Adding front end pages in order to add shops, shop types and run postcode lookups
- Shop with same name/postcode checks on create
- Seeder for Shops to make initial setup/testing easier
- Add in logic so shopType relationship data is returned with responses
- Investigate ways of tidying up the code for the API responses to remove duplication
