<?php
namespace SilexSphinxSearch;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Verdet\SphinxSearchBundle\Services\Search\SphinxSearch;

/**
 * Sphinx search extension for Silex
 */
class SphinxSearchExtension implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     */
    public function boot(Application $app)
    {

    }

    /**
     * @param \Silex\Application $app
     * @throws \Exception
     */
    public function register(Application $app)
    {
        $app['sphinxsearch.default_options'] = array(
            'searchd' => array(
                'host' => 'localhost',
                'port' => 9312,
                'socket' => null
            ),
        );

        $app['sphinxsearch.options.initializer'] = $app->protect(
            function () use ($app) {
                static $initialized = false;

                if ($initialized) {
                    return;
                }

                $initialized = true;

                if (!isset($app['sphinxsearch.options']['indexes']) || !count(
                    $app['sphinxsearch.options']['indexes']
                )
                ) {
                    throw new \Exception("At least one index must be defined");
                }


                $tmp = $app['sphinxsearch.options'];

                foreach ($tmp as $name => &$options) {
                    if (isset($app['sphinxsearch.default_options'][$name])) {
                        $options = array_replace($app['sphinxsearch.default_options'][$name], $options);
                    }

                }

                $app['sphinxsearch.options'] = $tmp;
            }
        );

        $app['sphinxsearch'] = $app->share(
            function ($app) {
                $app['sphinxsearch.options.initializer']();

                $options = $app['sphinxsearch.options'];

                $sphinxSearch = new Sphinxsearch($options['searchd']['host'], $options['searchd']['port'], $options['searchd']['socket'], $options['indexes']);

                return $sphinxSearch;
            }
        );
    }
}
