<?php
namespace services;

class ApiServices
{
    private $api_key;

    public function __constract($api_key)
    {
        $this->api_key = $api_key;
    }

    public function categories()
    {
        return new CategoriesApi($this->api_key);
    }

    public function images()
    {
        return new ImagesApi($this->api_key);
    }

    public function breads()
    {
        return new BreedsApi($this->api_key);
    }
}
