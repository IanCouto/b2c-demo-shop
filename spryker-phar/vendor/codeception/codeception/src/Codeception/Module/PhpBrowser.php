<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Codeception\Module;

use Closure;
use Codeception\Lib\Connector\Guzzle;
use Codeception\Lib\Connector\Guzzle6;
use Codeception\Lib\InnerBrowser;
use Codeception\Lib\Interfaces\MultiSession;
use Codeception\Lib\Interfaces\Remote;
use Codeception\Lib\Interfaces\RequiresPackage;
use Codeception\TestInterface;
use Codeception\Util\Uri;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Uses [Guzzle](http://guzzlephp.org/) to interact with your application over CURL.
 * Module works over CURL and requires **PHP CURL extension** to be enabled.
 *
 * Use to perform web acceptance tests with non-javascript browser.
 *
 * If test fails stores last shown page in 'output' dir.
 *
 * ## Status
 *
 * * Maintainer: **davert**
 * * Stability: **stable**
 * * Contact: codeception@codeception.com
 * * Works with [Guzzle](http://guzzlephp.org/)
 *
 * *Please review the code of non-stable modules and provide patches if you have issues.*
 *
 * ## Configuration
 *
 * * url *required* - start url of your app
 * * handler (default: curl) - Guzzle handler to use. By default curl is used, also possible to pass `stream`, or any valid class name as [Handler](http://docs.guzzlephp.org/en/latest/handlers-and-middleware.html#handlers).
 * * middleware - Guzzle middlewares to add. An array of valid callables is required.
 * * curl - curl options
 * * headers - ...
 * * cookies - ...
 * * auth - ...
 * * verify - ...
 * * .. those and other [Guzzle Request options](http://docs.guzzlephp.org/en/latest/request-options.html)
 *
 *
 * ### Example (`acceptance.suite.yml`)
 *
 *     modules:
 *        enabled:
 *            - PhpBrowser:
 *                url: 'http://localhost'
 *                auth: ['admin', '123345']
 *                curl:
 *                    CURLOPT_RETURNTRANSFER: true
 *                cookies:
 *                    cookie-1:
 *                        Name: userName
 *                        Value: john.doe
 *                    cookie-2:
 *                        Name: authToken
 *                        Value: 1abcd2345
 *                        Domain: subdomain.domain.com
 *                        Path: /admin/
 *                        Expires: 1292177455
 *                        Secure: true
 *                        HttpOnly: false
 *
 *
 * All SSL certification checks are disabled by default.
 * Use Guzzle request options to configure certifications and others.
 *
 * ## Public API
 *
 * Those properties and methods are expected to be used in Helper classes:
 *
 * Properties:
 *
 * * `guzzle` - contains [Guzzle](http://guzzlephp.org/) client instance: `\GuzzleHttp\Client`
 * * `client` - Symfony BrowserKit instance.
 *
 */
class PhpBrowser extends InnerBrowser implements Remote, MultiSession, RequiresPackage
{

    private $isGuzzlePsr7;

    protected $requiredFields = ['url'];

    protected $config = [
        'verify' => false,
        'expect' => false,
        'timeout' => 30,
        'curl' => [],
        'refresh_max_interval' => 10,
        'handler' => 'curl',
        'middleware' => null,

        // required defaults (not recommended to change)
        'allow_redirects' => false,
        'http_errors' => false,
        'cookies' => true,
    ];

    protected $guzzleConfigFields = [
        'headers',
        'auth',
        'proxy',
        'verify',
        'cert',
        'query',
        'ssl_key',
        'proxy',
        'expect',
        'version',
        'timeout',
        'connect_timeout',
    ];

    /**
     * @var \Codeception\Lib\Connector\Guzzle6
     */
    public $client;

    /**
     * @var \GuzzleHttp\Client
     */
    public $guzzle;

    public function _requires()
    {
        return ['GuzzleHttp\Client' => '"guzzlehttp/guzzle": ">=4.1.4 <7.0"'];
    }

    /**
     * @return void
     */
    public function _initialize()
    {
        $this->_initializeSession();
    }

    protected function guessGuzzleConnector()
    {
        if (class_exists('GuzzleHttp\Url')) {
            $this->isGuzzlePsr7 = false;
            return new Guzzle();
        }
        $this->isGuzzlePsr7 = true;
        return new Guzzle6();
    }

    /**
     * @return void
     */
    public function _before(TestInterface $test)
    {
        if (!$this->client) {
            $this->client = $this->guessGuzzleConnector();
        }
        $this->_prepareSession();
    }

    public function _getUrl()
    {
        return $this->config['url'];
    }

    /**
     * Alias to `haveHttpHeader`
     *
     * @param $name
     * @param $value
     *
     * @return void
     */
    public function setHeader($name, $value)
    {
        $this->haveHttpHeader($name, $value);
    }

    /**
     * @return void
     */
    public function amHttpAuthenticated($username, $password)
    {
        $this->client->setAuth($username, $password);
    }

    /**
     * @return void
     */
    public function amOnUrl($url)
    {
        $host = Uri::retrieveHost($url);
        $this->_reconfigure(['url' => $host]);
        $page = substr($url, strlen($host));
        if ($page === '') {
            $page = '/';
        }
        $this->debugSection('Host', $host);
        $this->amOnPage($page);
    }

    /**
     * @return void
     */
    public function amOnSubdomain($subdomain)
    {
        $url = $this->config['url'];
        $url = preg_replace('~(https?:\/\/)(.*\.)(.*\.)~', "$1$3", $url); // removing current subdomain
        $url = preg_replace('~(https?:\/\/)(.*)~', "$1$subdomain.$2", $url); // inserting new
        $this->_reconfigure(['url' => $url]);
    }

    /**
     * @return void
     */
    protected function onReconfigure()
    {
        $this->_prepareSession();
    }

    /**
     * Low-level API method.
     * If Codeception commands are not enough, use [Guzzle HTTP Client](http://guzzlephp.org/) methods directly
     *
     * Example:
     *
     * ``` php
     * <?php
     * $I->executeInGuzzle(function (\GuzzleHttp\Client $client) {
     *      $client->get('/get', ['query' => ['foo' => 'bar']]);
     * });
     * ?>
     * ```
     *
     * It is not recommended to use this command on a regular basis.
     * If Codeception lacks important Guzzle Client methods, implement them and submit patches.
     *
     * @param callable $function
     */
    public function executeInGuzzle(Closure $function)
    {
        return $function($this->guzzle);
    }

    public function _getResponseCode()
    {
        return $this->getResponseStatusCode();
    }

    /**
     * @return void
     */
    public function _initializeSession()
    {
        // independent sessions need independent cookies
        $this->client = $this->guessGuzzleConnector();
        $this->_prepareSession();
    }

    /**
     * @return void
     */
    public function _prepareSession()
    {
        $defaults = array_intersect_key($this->config, array_flip($this->guzzleConfigFields));
        $curlOptions = [];

        foreach ($this->config['curl'] as $key => $val) {
            if (defined($key)) {
                $curlOptions[constant($key)] = $val;
            }
        }

        $this->setCookiesFromOptions();

        if ($this->isGuzzlePsr7) {
            $defaults['base_uri'] = $this->config['url'];
            $defaults['curl'] = $curlOptions;
            $handler = Guzzle6::createHandler($this->config['handler']);
            if ($handler && is_array($this->config['middleware'])) {
                foreach ($this->config['middleware'] as $middleware) {
                    $handler->push($middleware);
                }
            }
            $defaults['handler'] = $handler;
            $this->guzzle = new GuzzleClient($defaults);
        } else {
            $defaults['config']['curl'] = $curlOptions;
            $this->guzzle = new GuzzleClient(['base_url' => $this->config['url'], 'defaults' => $defaults]);
            $this->client->setBaseUri($this->config['url']);
        }

        $this->client->setRefreshMaxInterval($this->config['refresh_max_interval']);
        $this->client->setClient($this->guzzle);
    }

    public function _backupSession()
    {
        return [
            'client' => $this->client,
            'guzzle' => $this->guzzle,
            'crawler' => $this->crawler,
            'headers' => $this->headers,
        ];
    }

    /**
     * @return void
     */
    public function _loadSession($session)
    {
        foreach ($session as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * @return void
     */
    public function _closeSession($session = null)
    {
        unset($session);
    }

}
