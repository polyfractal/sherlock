Sherlock
========

_Sherlock_ is a PHP (>=5.3.9) client for [ElasticSearch](http://www.elasticsearch.org/).  _Sherlock_ can be used to search and manage ElasticSearch clusters.

## Sherlock is Unmaintained! DO NOT USE!
Sherlock is no longer maintained and should not be considered a usable Elasticsearch client for anything remotely resembling a production environment.  It is *not* compatible with Elasticsearch 1.0+, nor does it have complete API coverage of Elasticsearch 0.90.x

*Please* use the official Elasticsearch client (which I wrote, and maintaine):  https://github.com/elasticsearch/elasticsearch-php

The official client is actively maintained and well-tested against ES and PHP versions.

If you are interested in updating Sherlock to use the official client, please open a ticket and let me know! I'd love to see the syntax of Sherlock on top of the new client...but I just don't have time myself.



Features
--------

 - One-to-one mapping with ElasticSearch's API and query DSL.
 - Option to use powerful ORM-like interface, associative arrays or raw JSON
 - Concurrent request streaming with RollingCurl and cURL multi-handle
 - Autodetection of cluster nodes and subsequent round-robin requests to the entire cluster
 - Configurable logging capabilities

Disclaimer
--------
Sherlock should be considered **Alpha** status.  Use in production at your own risk!  The current 0.1 branch is in maintenance mode - I'm only fixing bugs and accepting PRs.

The new 0.2 branch that is coming *will* break backwards compatibility in places.  The new 0.2 branch, however, should be a lot easier to maintain and I'll likely be promoting it to true 1.0 status soon after release (along with associated SemVer, backwards compatibility, etc).

Resources
---------------
 - Read the [Quickstart Guide](http://sherlockphp.com/quickstart/)
 - Read the [Full Documentation](http://sherlockphp.com/documentation/)
 - [Roadmap for Sherlock](Roadmap.md)
 - [Changelog](Changelog.md)

Installation via Composer
-------------------------
The recommended method to install _Sherlock_ is through [Composer](http://getcomposer.org).

1. Add ``sherlock/sherlock`` as a dependency in your project's ``composer.json`` file:

        {
            "require": {
                "sherlock/sherlock": "~0.1.0"
            }
        }

   *Note*: If you would like to use the latest stable development version, specify ``dev-master`` in your composer file instead of ``0.1.*``.  Master is kept at the most recent, stable development version but may not be tagged and pushed to Packagist yet.  Unstable dev versions are kept secluded in the ``develop`` branch.

2. Download and install Composer:

        curl -s http://getcomposer.org/installer | php

3. Install your dependencies:

        php composer.phar install

4. Require Composer's autoloader

    Composer also prepares an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process:

        <?php
        require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).


Manual Installation
-------------------
_Sherlock_ can be installed even if you don't use Composer.  Download Sherlock and include the following in your index.php or equivalent

```php
        <?php
        require 'Sherlock/Sherlock.php';
        \Sherlock\Sherlock::registerAutoloader();
```

Usage
-----
The library interface is still under flux...this section will be updated once _Sherlock_ has been fleshed out a bit more.

```php
   require 'vendor/autoload.php';
   use \Sherlock\Sherlock;

   //The Sherlock object manages cluster state, builds queries, etc
   $sherlock = new Sherlock();

   //Add a node to our cluster.  Sherlock can optionally autodetect nodes given one starting seed
   $sherlock->addNode('localhost', 9200);

   //Build a new search request
   $request = $sherlock->search();

   //Set the index, type and from/to parameters of the request.
   //The query is at the end of the chain, although it could be placed anywhere
   $request->index("test")
            ->type("tweet")
            ->from(0)
            ->size(10)
            ->query(Sherlock::queryBuilder()->Term()->field("message")
                                              ->term("ElasticSearch"));

   //Execute the search and return results
   $response = $request->execute();

   echo "Took: ".$response->took."\r\n";
   echo "Number of Hits: ".count($response)."\r\n";

   //Iterate over the hits and print out some data
   foreach($response as $hit)
   {
      echo $hit['score'].' - '.$hit['source']['message']."\r\n";
   }


   //Let's try a more advanced query now.
   //Each section is it's own variable to help show how everything fits together
   $must = Sherlock::queryBuilder()->Term()->field("message")
                                     ->term("ElasticSearch");

   $should = Sherlock::queryBuilder()->Match()->field("author")
                                        ->query("Zachary Tong")
                                        ->boost(2.5);

   $must_not = Sherlock::queryBuilder()->Term()->field("message")
                                           ->term("Solr");

   $bool = Sherlock::queryBuilder()->Bool->must($must)
                                   ->should($should)
                                   ->must_not($must_not);
   $request->query($bool);
   $request->execute();

```

Other types of queries
----------------------
You can use _Sherlock_ with every type of query listed in the elasticsearch docs.
E.g. if you'd like to use a _fuzzy like this (flt)_ query, you can build your query like this:

```php
	$sherlock = new Sherlock();
    $sherlock->addNode('localhost', 9200);
    $request = $sherlock->search();

	$request->index('test')
			->type('tweet')
			->query(Sherlock::queryBuilder()
				->FuzzyLikeThis()
				->fields( array('description', 'tags', 'name') )
				->like_text( $query )
				->min_similarity( 0.6 )
			);

	$response = $request->execute();
```

Filters
-------
Building filters is identical to building queries, but requires the use of _filterBuilder()_ instead of _queryBuilder()_.
Again, a simple example would be:

```php
    $request->index('test')
		->type('tweet')
		->filter(Sherlock::filterBuilder()
			->Term()
			->field($type)
			->term($value)
		);
	
	$response = $request->execute();
```



Non-ORM style
-------------
Not a fan of ORM style construction?  Don't worry, _Sherlock_ supports "raw" associative arrays

```php
    //Build a new search request
    $request = $sherlock->search();

    //We can compose queries using hashmaps instead of the ORM.
    $manualData = array("field" => "field1", "term" => "town");

    $request->query(Sherlock::query()->Term($manualData));

```

Need to consume and use raw JSON?  No problem
```php
    //Build a new search request
    $request = $sherlock->search();

    //We can compose queries using hashmaps instead of the ORM.
    $json = '{ "term" : { "field1" : "town" } }';

    $request->query(Sherlock::query()->Raw($json));

```

(There will be a RawQuery method soon, that lets you construct entirely arbitrary queries with arrays or JSON)

For more examples check out the [Quickstart Guide](http://sherlockphp.com/quickstart.html)


Philosophy
----------
_Sherlock_ aims to continue the precendent set by ElasticSearch: work out of the box with minimal configuration and provide a simple interface.

_Sherlock's_ API uses a "fluent" interface that relies heavily on method chaining and type hinting for brevity.  The developer should never need to stop and look up a class name to instantiate, or dig through docs to remember which class accepts which arguments.

Secondary to the interface comes _Sherlock_ developer sanity: reduce code as much as possible.  Rather than write a million getter/setter functions to expose ElasticSearch's various parameters, _Sherlock_ relies heavily upon templates, auto-generated class stubs, magic methods and PHPdoc.
