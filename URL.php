<?php
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Richard Heyes <richard@phpguru.org>                          |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Net_URL Class
//

class Net_URL {

    /**
    * Full url
    * @var string
    */
    var $url;
    
    /**
    * Protocol
    * @var string
    */
    var $protocol;

    /**
    * Username
    * @var string
    */
    var $username;

    /**
    * Password
    * @var string
    */
    var $password;

    /**
    * Host
    * @var string
    */
    var $host;
    
    /**
    * Port
    * @var integer
    */
    var $port;
    
    /**
    * Path
    * @var string
    */
    var $path;
    
    /**
    * Query string
    * @var array
    */
    var $querystring;

    /**
    * Anchor
    * @var string
    */
    var $anchor;

    /**
    * Constructor
    *
    * Parses the given url and stores the various parts
    * Defaults are used in certain cases
    *
    * @param $url The url
    */
    function Net_URL($url = null)
    {
        global $HTTP_SERVER_VARS;

        $this->url         = $url;
        $this->protocol    = 'http' . (@$HTTP_SERVER_VARS['HTTPS'] == 'on' ? 's' : '');
        $this->user        = '';
        $this->pass        = '';
        $this->host        = isset($HTTP_SERVER_VARS['SERVER_NAME']) ? $HTTP_SERVER_VARS['SERVER_NAME'] : 'localhost';
        $this->port        = isset($HTTP_SERVER_VARS['SERVER_PORT']) ? $HTTP_SERVER_VARS['SERVER_PORT'] : 80;
        $this->path        = $HTTP_SERVER_VARS['PHP_SELF'];
        $this->querystring = $this->_parseRawQuerystring($HTTP_SERVER_VARS['QUERY_STRING']);
        $this->anchor      = '';

        // Parse the uri and store the various parts
        if (!empty($url)) {
            $urlinfo = parse_url($url);
    
            // Protocol
            if (!empty($urlinfo['scheme'])) {
                $this->protocol = $urlinfo['scheme'];
            }
    
            // Username
            if (!empty($urlinfo['user'])) {
                $this->user = $urlinfo['user'];
            }
    
            // Password
            if (!empty($urlinfo['pass'])) {
                $this->pass = $urlinfo['pass'];
            }
    
            // Host
            if (!empty($urlinfo['host'])) {
                $this->host = $urlinfo['host'];
            }
    
            // Port
            if (!empty($urlinfo['port'])) {
                $this->port = $urlinfo['port'];
            }
    
            // Path
            if (!empty($urlinfo['path'])) {
                if ($urlinfo['path'][0] == '/') {
                    $this->path = $urlinfo['path'];
                } else {
                    $path = dirname($this->path) == '/' ? '' : dirname($this->path);
                    $this->path = sprintf('%s/%s', $path, $urlinfo['path']);
                }
            } else {
				$this->path = '';
			}
    
            // Querystring
            $this->querystring = !empty($urlinfo['query']) ? $this->_parseRawQueryString($urlinfo['query']) : array();
    
            // Anchor
            if (!empty($urlinfo['fragment'])) {
                $this->anchor = $urlinfo['fragment'];
            }
        }
    }

    /**
    * Returns full url
    *
    * @return string Full url
    * @access public
    */
    function getURL()
    {
        $querystring = $this->getQueryString();

        $this->url = $this->protocol . '://'
                   . $this->user . (!empty($this->pass) ? ':' : '')
                   . $this->pass . (!empty($this->user) ? '@' : '')
                   . $this->host . ($this->port == '80' ? '' : ':' . $this->port)
                   . $this->path
                   . (!empty($querystring) ? '?' . $querystring : '')
                   . (!empty($this->anchor) ? '#' . $this->anchor : '');

        return $this->url;
    }

    /**
    * Adds a querystring item
    *
    * @param $name Name of item
    * @param $value Value of item
    * @param $preencoded Whether value is urlencoded or not, default = not
    * @access public<>
    */
    function addQueryString($name, $value, $preencoded = false)
    {
        $this->querystring[$name] = $preencoded ? $value : urlencode($value);
    }    

    /**
    * Removes a querystring item
    *
    * @param $name Name of item
    * @access public<>
    */
    function removeQueryString($name)
    {
        if (isset($this->querystring[$name])) {
            unset($this->querystring[$name]);
        }
    }    
    
    /**
    * Sets the querystring to literally what you supply
    *
    * @param $querystring The querystring data. Should be of the format foo=bar&x=y etc
    * @access public
    */
    function addRawQueryString($querystring)
    {
        $this->querystring = $this->_parseRawQueryString($querystring);
    }
    
    /**
    * Returns flat querystring
    *
    * @return string Querystring
    * @access public
    */
    function getQueryString()
    {
        if (!empty($this->querystring)) {
            foreach ($this->querystring as $name => $value) {
                $querystring[] = $name . '=' . $value;
            }
            $querystring = implode('&', $querystring);
        } else {
            $querystring = '';
        }

        return $querystring;
    }

    /**
    * Parses raw querystring and returns an array of it
    *
    * @param  string  $querystring The querystring to parse
    * @return array                An array of the querystring data
    * @access private
    */
    function _parseRawQuerystring($querystring)
    {
        parse_str($querystring, $qs);

        foreach ($qs as $key => $value) {
            $qs[$key] = rawurlencode($value);
        }        

        return $qs;
    }
}

$u = new Net_URL('../www.example.com');
echo $u->getUrl();
?>
