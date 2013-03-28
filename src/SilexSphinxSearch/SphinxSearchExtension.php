<?php
namespace SilexSphinxSearch;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Search\SphinxSearchBundle\Services\Search\Sphinxsearch;

/**
 * Sphinx search extension for Silex
 */
class SphinxsearchExtension implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     */
    public function boot(Application $app)
    {

    }

    /**
     * @param \Silex\Application $app
     */
    public function register(Application $app)
    {
        $app['sphinxsearch'] = $app->share(function ($app) {
                $options = $app['sphinxsearch.options'];

                $sphinxSearch = new Sphinxsearch($options['searchd']['host'], $options['searchd']['port'], $options['searchd']['socket'], $options['indexes']);

                return $sphinxSearch;
            });
    }
}
