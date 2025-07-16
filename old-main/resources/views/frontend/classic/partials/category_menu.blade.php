<div class="aiz-category-menu bg-white rounded-0 border-top" id="category-sidebar" style="width:270px;">
    <ul class="list-unstyled categories no-scrollbar mb-0 text-left">
        @foreach (get_categories() as $key => $category)
            <li class="category-nav-element border border-top-0" data-categoryid="{{ $category['CategoryId'] }}" data-id="{{ $category['ExternalId'] }}">
                <a href="{{ route('category_wise_products', $category['ExternalId']) }}?name={{ urlencode($category['Name']) }}" class="text-truncate text-dark px-4 fs-14 d-block hov-column-gap-1">
                    <span class="cat-name has-transition">{{ $category['Name'] }}</span>
                </a>
                <div class="sub-cat-menu c-scrollbar-light border p-4 shadow-none">
                    <div class="c-preloader text-center absolute-center">
                        <i class="las la-spinner la-spin la-3x opacity-70"></i>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>