<?php

namespace App\Http\Controllers;

abstract class Controller
{

    protected array $routes;
    protected array $slugs;
    protected array $views;
    protected array $breadcrumbs;
    protected string|null $menu = null;
    protected string|null $route = null;
    protected string|null $slug = null;
    protected string|null $view = null;

    protected mixed $baseUrl = null;

    //
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->routes = [
            'web' => 'web::',
            'api' => 'api::'
        ];
        $this->slugs = [
            'web' => 'web/',
            'api' => 'api/'
        ];
        $this->views = [
            'web' => 'web::',
        ];

        //Base URL
        $this->baseUrl = config('app.url');

        $this->breadcrumbs = [
            ['label' => 'Dashboard', 'slug' => 'dashboard', 'url' => '/dashboard']
        ];
    }

    protected function share()
    {
        view()->share([
            'menu' => $this->menu,
            'route' => $this->route,
            'slug' => $this->slug,
            'view' => $this->view,
            'url' => $this->baseUrl,
            'breadcrumb' => $this->breadcrumbs
        ]);
    }
}
