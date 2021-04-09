<?php
namespace controllers;
use core\BaseController;
use services\ApiServices;

class CatsController extends BaseController
{
    public function actionCategories()
    {
        $cats_categories = new ApiServices('ccccb235-d5ea-492f-b19f-bfccaa74b720');
        $result = $cats_categories->categories()->get();
        var_dump($result);
    }

    public function actionImages()
    {
        $cats_images = new ApiServices('ccccb235-d5ea-492f-b19f-bfccaa74b720');
        $result = $cats_images->images()->get([
            'category_ids' => [5],
            'limit' => 20,
            'page' => 0
        ]);
        var_dump($result);
    }

    public function actionBreads()
    {
        $cats_breads = new ApiServices('ccccb235-d5ea-492f-b19f-bfccaa74b720');
        $result = $cats_breads->breads()->get([
            'limit' => 20,
            'page' => 0
        ]);
        // $result = $cats_breads->breads()->search([
        //     'q' => 'sib'
        // ]);
        var_dump($result);
    }
}