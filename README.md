#Amazon Api Helper
## Installation
Add this package to your composer.json or execute<br>
`composer require flostone/amazon`<br>
Include the Amazon Class using<br>
`use FloStone\Amazon\Amazon;`<br>
and<br>
`use FloStone\Amazon\AmazonCountry;`<br>
for the available countries.
##Usage
###Amazon Credentials
All you need is an Amazon Access Key (public and secret) and an Associate Tag.<br>
You can get your Access Key [here](https://console.aws.amazon.com/iam/home?#home)<br>
For the Associate Tag you must create an Associate Account [here](https://affiliate-program.amazon.com/)<br>

###Using the Code
Create a new Amazon instance using<br>
`$amazon = new Amazon($accesskey, $associatetag, $secretkey, $locale);`<br>
The locale can be any of the supported Amazon Locales.<br>
You can use strings or the pre-defined constants found in AmazonCountry.php.<br>
Constants:<br>
`AmazonCountry::US // USA`<br>
`AmazonCountry::DE // Germany`<br>
`AmazonCountry::FR // France`<br>
`AmazonCountry::UK // United Kingdom`<br>
`AmazonCountry::IT // Italy`<br>
`AmazonCountry::ES // Spain`<br>
`AmazonCountry::BR // Brasil`<br>
`AmazonCountry::CA // Canada`<br>
`AmazonCountry::CN // China`<br>
`AmazonCountry::IN // India`<br>
`AmazonCountry::JP // Japan`<br>
`AmazonCountry::MX // Mexico`<br>
After initializing the Instance, you can now add Parameters using the "param" function:<br>
`$amazon->param('Operation', 'ItemSearch');`<br>
These parameters will be in the request url.<br>
After adding all your parameters, simply use the "request" function to send the request:<br>
`$response = $amazon->request()`<br>
By default the response will be a Page of the Amazon API parsed through the SimpleXMLElement class.<br>
However, if you wish to get all Pages in a returned collection, simply add<br>
`$amazon->allPages = true`<br>
to the instance and it will return a collection of 10 Pages.<br>
