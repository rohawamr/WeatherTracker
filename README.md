# WeatherTracker
Things to do before using this service: 1. Create an RDB instance. 2. Connect to the RDB instance with db_connect.php. And db_connect.php should be in scripts folder. 3. Connect and run the script install.php to create the tables in your RDB instance.

Note: main.css file should be in stye folder

The services we have used are: 1. LiveWeather: LiveWeather is a service that reports the current weather conditions based on the users current location.Service used is 'https://www.thecollegestash.com/json/out.php?zip=' which takes a zipcode.

Gmap Geolocation: To get the zipcode from lattitude and longitude of the users current location, we used the Google's geolocation API. Add Google API Key for GeoLocation access. example: $data = file_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$_REQUEST["lat"].','.$_REQUEST["lon"].'&key=AIzaSyBFU6OZngXGc-IlU00rKAvIZ9V95XUFmlX');

Memcached: Memcached is used as a service to optimise query performance. Memcached needs to be installed on the host instance to be available for use.

Created an AWS RDB instance to store the weather requests generated for the purpose of analytics.
