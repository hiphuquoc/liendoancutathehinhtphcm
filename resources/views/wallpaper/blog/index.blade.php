@extends('layouts.wallpaper')
@push('cssFirstView')
    <!-- trường hợp là local thì dùng vite để chạy npm run dev lúc code -->
    @if(env('APP_ENV')=='local')
        @vite('resources/sources/main/category-blog-first-view.scss')
    @else
        @php
            $manifest           = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $cssFirstView       = $manifest['resources/sources/main/category-blog-first-view.scss']['file'];
        @endphp
        <style type="text/css">
            {!! file_get_contents(asset('build/' . $cssFirstView)) !!}
        </style>
    @endif
@endpush
@push('headCustom')
<!-- ===== START:: SCHEMA ===== -->
    <!-- STRAT:: Organization Schema -->
    @include('wallpaper.schema.organization')
    <!-- END:: Organization Schema -->

    <!-- STRAT:: Article Schema -->
    @include('wallpaper.schema.article', compact('item'))
    <!-- END:: Article Schema -->

    <!-- STRAT:: Article Schema -->
    @include('wallpaper.schema.creativeworkseries', compact('item'))
    <!-- END:: Article Schema -->
    
    {{-- <!-- STRAT:: FAQ Schema -->
    @include('wallpaper.schema.itemlist', ['data' => $categories])
    <!-- END:: FAQ Schema --> --}}

    <!-- STRAT:: Title - Description - Social -->
    @include('wallpaper.schema.social', ['item' => $item, 'lowPrice' => 1, 'highPrice' => 5])
    <!-- END:: Title - Description - Social -->

    <!-- STRAT:: FAQ Schema -->
    @include('wallpaper.schema.faq', ['data' => $item->faqs])
    <!-- END:: FAQ Schema -->
<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')
    <!-- share social -->
    @include('wallpaper.template.shareSocial')
    <!-- content -->
    <div class="breadcrumbMobileBox"><!-- dùng để chống nhảy padding - margin so với các trang có breadcrumb --></div>
    <!-- Item Category Grid Box -->
    @include('wallpaper.snippets.banner', [
        'urlImage' => 'https://liendoancutathehinhhcm.storage.googleapis.com/storage/images/blog-bg-img.webp',
    ])
    
    <!-- body -->
    <div class="blog">
        <div class="container">
            <div class="row">
                <!-- sidebar -->
                <div class="col-lg-4">
                    <!-- Search -->
                    @include('wallpaper.categoryBlog.search')
                    <!-- blog news -->
                    @include('wallpaper.categoryBlog.blogFeatured')
                    <!-- categories -->
                    @include('wallpaper.categoryBlog.categoryList')
                    <!-- fanpage facebook -->
                    @include('wallpaper.categoryBlog.fanpageFacebook')
                </div>
                <!-- main content -->
                <div class="col-lg-8">
        
                    <!-- content blog -->
                    @include('wallpaper.blog.content')
                    {{-- <!-- comment of gust -->
                    @include('wallpaper.blog.comment')
                    <!-- comment form -->
                    @include('wallpaper.blog.commentForm') --}}

                    <!-- related -->
                    {{-- @include('wallpaper.blog.related') --}}

                </div>
            </div>
        </div>
    </div>
@endsection
{{-- @push('scriptCustom')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            
        });
    </script>
@endpush --}}
