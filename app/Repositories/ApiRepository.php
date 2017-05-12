<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiRepository
{

    protected $uri = 'http://172.20.10.10/test/public/';

    protected $token = null;

    /**
     * Get Authentication token
     *
     * @return bool
     */
    public function getAuthToken()
    {
        try {
            $client = new Client([
                'base_uri' => $this->uri,
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
            $client = new Client([
                'base_uri' => $this->uri,
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
}