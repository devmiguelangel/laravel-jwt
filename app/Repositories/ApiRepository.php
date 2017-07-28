<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiRepository
{
    protected $host = '172.20.17.16';

    protected $uri = 'http://:host/test/public/';

    protected $token = null;

    /**
     * Get Authentication token
     *
     * @return bool
     */
    public function getAuthToken()
    {
        try {
            $uri = $this->getUri();

            $client = new Client([
                'base_uri' => $uri,
            ]);

            $response = $client->post('api/authenticate', [
                'headers'     => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
                'form_params' => [
                    'email'    => 'holly@coboser.com',
                    'password' => 'secret',
                ],
            ]);

            $data        = $response->getBody()->getContents();
            $data        = json_decode($data, true);
            $this->token = $data[ 'token' ];

            return true;
        } catch (RequestException $e) {
            $status  = 404;
            $request = $e->getRequest();
            $request->getBody()->getContents();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $status   = $response->getStatusCode();
            }
        }

        return false;
    }


    /**
     * Verify Authentication token
     *
     * @param string $token
     *
     * @return bool
     */
    public function verifyAuthToken($token)
    {
        try {
            $uri = $this->getUri();

            $client = new Client([
                'base_uri' => $uri,
            ]);

            $response = $client->get('api/authenticate', [
                'query' => [
                    'token' => $token,
                ],
            ]);

            $data = $response->getBody()->getContents();

            return true;
        } catch (RequestException $e) {
            $status  = 404;
            $request = $e->getRequest();
            $request->getBody()->getContents();

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $status   = $response->getStatusCode();
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * @return bool
     */
    public function connection()
    {
        exec('ping -c4 ' . $this->host, $output, $status);

        return $status == 0 ? true : false;
    }


    /**
     * @return string
     */
    protected function getUri()
    {
        return str_replace(':host', $this->host, $this->uri);
    }
}