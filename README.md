#Amazon Api Helper
## Installation
Add this package to your composer.json or execute<br>
`composer require flo5581/amazon`<br>
Include the Amazon Class using<br>
`use Flo\Amazon\Amazon`
##Usage
###Amazon Credentials
All you need is an Amazon Access Key (public and secret) and an Associate Tag.<br>
You can get your Access Key [here](https://console.aws.amazon.com/iam/home?#home)<br>
For the Associate Tag you must create an Associate Account [here](https://affiliate-program.amazon.com/)<br>

###Using the Code
Create a new Amazon instance using<br>
`$amazon = new Amazon($accesskey, $associatetag, $secretkey, $locale);`
The locale can be any of the supported Amazon Locales, you can look up the locales in the "AmazonCountry" interface.<br>
You can use strings or the pre-defined 