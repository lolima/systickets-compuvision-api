<?php

namespace App\Loggers;

class Logger extends \Silalahi\Slim\Logger
{
    /**
     * Logger Middleware for Slim framework
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        // Begin of time
        $start = microtime(1);
        // URL accessed
        $path = $request->getUri()->getPath();
        // Call next middleware
        $response = $next($request, $response);
        // End of time
        $end = microtime(1);
        // Latency
        $latency = $end - $start;
        // Client IP address
        $clientIP = $this->getIpAddress();
        // Method access
        $method = $request->getMethod();
        // Write to log
        $this->write(sprintf("|%d|%f s|%s|%s %s", $response->getStatusCode(), $latency, $clientIP, $method, $path), self::INFO);
        // Return response
        return $response;
    }

    /**
     * Helper function to get client IP Address
     * NOTE: There is security implications
     * @source http://roshanbh.com.np/2007/12/getting-real-ip-address-in-php.html
     *
     * @return string $ip IP Address
     */
    private function getIpAddress()
    {
        // Check ip from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // To check ip is pass from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

class FcardLogger extends \Silalahi\Slim\Logger
{
    /**
     * Logger Middleware for Slim framework
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        // Begin of time
        $start = microtime(1);
        // URL accessed
        $path = $request->getUri()->getPath();
        // Call next middleware
        $response = $next($request, $response);
        // End of time
        $end = microtime(1);
        // Latency
        $latency = $end - $start;
        // Client IP address
        $clientIP = $this->getIpAddress();
        // Method access
        $method = $request->getMethod();
        // Write to log
        $this->write(sprintf("|%d|%f s|%s|%s %s", $response->getStatusCode(), $latency, $clientIP, $method, $path), self::INFO);
        // Return response
        return $response;
    }

    /**
     * Helper function to get client IP Address
     * NOTE: There is security implications
     * @source http://roshanbh.com.np/2007/12/getting-real-ip-address-in-php.html
     *
     * @return string $ip IP Address
     */
    private function getIpAddress()
    {
        // Check ip from share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        // To check ip is pass from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}