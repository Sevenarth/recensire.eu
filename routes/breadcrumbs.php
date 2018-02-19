<?php

Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('panel.home'));
});

Breadcrumbs::register('sellers', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Venditori', route('panel.sellers.home'));
});

Breadcrumbs::register('stores', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Negozi', route('panel.stores.home'));
});

Breadcrumbs::register('products', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Prodotti', route('panel.products.home'));
});

Breadcrumbs::register('testOrders', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Ordini di lavoro', route('panel.testOrders.home'));
});

Breadcrumbs::register('testers', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Testers', route('panel.testers.home'));
});

/*
Breadcrumbs::register('stores.create', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($category->title, route('category', $category->id));
});

Breadcrumbs::register('sellers.create', function ($breadcrumbs, $post) {
    $breadcrumbs->parent('category', $post->category);
    $breadcrumbs->push($post->title, route('post', $post));
});*/

Breadcrumbs::register('sellers.create', function ($breadcrumbs) {
    $breadcrumbs->parent('sellers');
    $breadcrumbs->push('Nuovo venditore', route('panel.sellers.create'));
});

Breadcrumbs::register('sellers.view', function ($breadcrumbs, $seller) {
    $breadcrumbs->parent('sellers');
    $breadcrumbs->push('Venditore #'.$seller->id, route('panel.sellers.view', $seller->id));
});

Breadcrumbs::register('sellers.edit', function ($breadcrumbs, $seller) {
    $breadcrumbs->parent('sellers.view', $seller);
    $breadcrumbs->push('Modifica', route('panel.sellers.edit', $seller->id));
});
Breadcrumbs::register('stores.create', function ($breadcrumbs) {
    $breadcrumbs->parent('stores');
    $breadcrumbs->push('Nuovo negozio', route('panel.stores.create'));
});

Breadcrumbs::register('stores.view', function ($breadcrumbs, $store) {
    $breadcrumbs->parent('stores');
    $breadcrumbs->push('Negozio #'.$store->id, route('panel.stores.view', $store->id));
});

Breadcrumbs::register('stores.edit', function ($breadcrumbs, $store) {
    $breadcrumbs->parent('stores.view', $store);
    $breadcrumbs->push('Modifica', route('panel.stores.edit', $store->id));
});

Breadcrumbs::register('products.create', function ($breadcrumbs) {
    $breadcrumbs->parent('products');
    $breadcrumbs->push('Nuovo prodotto', route('panel.products.create'));
});
