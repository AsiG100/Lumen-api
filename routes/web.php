<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('catalogs', 'CatalogController@showAllCatalogs');
$router->get('catalogs/{id}', 'CatalogController@showOneCatalog');
$router->get('catalogs/{id}/products', 'CatalogController@showAllProductsInCatalog');
$router->post('catalogs', 'CatalogController@create');
$router->put('catalogs/{id}', 'CatalogController@update');
$router->delete('catalogs/{id}', 'CatalogController@delete');

$router->get('products', 'ProductController@showAllProducts');
$router->get('products/{item}', 'ProductController@showOneProduct');
$router->post('products', 'ProductController@create');
$router->put('products/{id}', 'ProductController@update');
$router->delete('products/{id}', 'ProductController@delete');

$router->get('cart', 'CartController@get');
$router->post('cart', 'CartController@add');
$router->post('cart/{id}', 'CartController@update');
$router->delete('cart', 'CartController@delete');
