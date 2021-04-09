<?php
namespace services;
use GuzzleHttp\Client;

class BreedsApi
{
    private $api_key;
    private $url = 'https://api.thecatapi.com/v1/breeds';

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
        $this->client = new Client();
    }

    public function get($options = [])
    {
        $query = [];
        if ($options) {
            foreach ($options as $key => $value) {
                $query[$key] = $value;
            }
        }
        $res = $this->client->request('GET', $this->url, [
            'x-api-key' => $this->api_key,
            'query' => $query
        ]);
        if ($res->getStatusCode() != 200) {
            return false;
        }
        return json_decode($res->getBody(), true);
    }

    public function search($options = [])
    {
        $this->url = $this->url . '/search';
        $query = [];

        if ($options) {
            foreach ($options as $key => $value) {
                $query[$key] = $value;
            }
        }
        $res = $this->client->request('GET', $this->url, [
            'x-api-key' => $this->api_key,
            'query' => $query
        ]);
        if ($res->getStatusCode() != 200) {
            return false;
        }
        return json_decode($res->getBody(), true);
    }
}