# Salsa  [![Open Source Helpers](https://www.codetriage.com/tomkiernan120/sourcream/badges/users.svg)](https://www.codetriage.com/tomkiernan120/sourcream)

Open source, PHP routing enging

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

PHP >= 5.4

### Installing

```
$ composer require tom.kiernan/salsa
```

```
$Salsa = new Salsa\Salsa( array( "baseRoute" => "test/route/ ) ); // Set base route for woking in sub directories

$Salsa->addRoute( "/", "test" ); // Ouputs "test"

$Salsa->addRoute( "name/:name", function( $name ) { echo $name  } ) // example "name/tomkiernan" will output "tomkiernan"

$Salsa->run(); // actually runs the router;

```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Composer](http://www.dropwizard.io/1.0.2/docs/) - Composer
* 
## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We are still looking to settle on a versioning system (open to suggestions)

## Authors

* **Tom Kiernan** - *Creator* - [Tom Kiernan](composer require tom.kiernan/salsa)

## License

This project is licensed under the GNU License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
