@extends('layouts.front')

@section('navbar')
<div>
<a href="/posts" class="btn btn-info">Apply</a>
<a href="/contactus" class="btn btn-info">Contact us</a>
</div>
@endsection

@section('content')
<div class="container" id="cont" data-open="true">
    @if($header)
    <div class="markdown">{{ $header }}</div>
    <hr>@endif
    <div class="my-2">
        @if($category_slug)
        <a href="{{ config('app.url') }}" class="btn btn-primary">Most recent</a>
        @else
        <button disabled class="btn btn-outline-primary">Most recent</button>
        @endif
        or
        <select class="custom-select w-auto" id="updateCategory">
            <option value="">All the categories</option>
            @foreach($categories as $category)
            <option value="{{ $category->slug }}" @if($category->slug == $category_slug) selected @endif>{{ $category->title }}</option>
            @endforeach
        </select>
    </div>
    <p>Showing {{ $products->count() }} of {{ $products->total() }} @if($category_slug) products in the selected category @else of the most recent products @endif</p>
    @if($products->count() > 0)
    <div class="card-columns">
        @foreach($products as $product)
        @if(!empty($products_links[$product->id]))
        <a rel="nofollow noreferrer" target="_blank" href="{{ $product->URL }}"><div class="card mobile-adapt linked">
        @else
        <div class="card mobile-adapt">
        @endif
            <div class="card-img-container">
                <img class="card-img-top mobile-adapt" src="{{ $product->images[0] ?: '/images/package.svg' }}" alt="{{ $product->title }}">
            </div>
            <div class="card-body mobile-adapt">
                <h4 class="card-text">{{ $product->title }} @if(!empty($products_links[$product->id])) <i class="fa fa-external-link-alt"></i> @endif </h4>
            </div>
        </div>
        @if(!empty($products_links[$product->id])) </a> @endif
        @endforeach
    </div>
    {{ $products->appends(request()->query())->links() }}
    @else
    <i>There are no products to show.</i>
    @endif
    @if($footer)<hr>
    <div class="markdown">{{ $footer }}</div>@endif
</div>
@endsection