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

Breadcrumbs::register('categories', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Categorie', route('panel.categories.home'));
});

Breadcrumbs::register('testOrders', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Ordini di lavoro', route('panel.testOrders.home'));
});

Breadcrumbs::register('testers', function ($breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Testers', route('panel.testers.home'));
});

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

Breadcrumbs::register('stores.products', function ($breadcrumbs, $store) {
    $breadcrumbs->parent('stores.view', $store);
    $breadcrumbs->push('Prodotti', route('panel.stores.products', $store->id));
});

Breadcrumbs::register('products.create', function ($breadcrumbs) {
    $breadcrumbs->parent('products');
    $breadcrumbs->push('Nuovo prodotto', route('panel.products.create'));
});

Breadcrumbs::register('products.view', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('products');
    $breadcrumbs->push('Prodotto #'.$product->id, route('panel.products.view', $product->id));
});

Breadcrumbs::register('products.edit', function ($breadcrumbs, $product) {
    $breadcrumbs->parent('products.view', $product);
    $breadcrumbs->push('Modifica', route('panel.products.edit', $product->id));
});

Breadcrumbs::register('categories.create', function ($breadcrumbs) {
    $breadcrumbs->parent('categories');
    $breadcrumbs->push('Nuova categoria', route('panel.categories.create'));
});

Breadcrumbs::register('categories.edit', function ($breadcrumbs, $category) {
    $breadcrumbs->parent('categories');
    $breadcrumbs->push('Modifica categoria #'.$category->id, route('panel.categories.edit', $category->id));
});
