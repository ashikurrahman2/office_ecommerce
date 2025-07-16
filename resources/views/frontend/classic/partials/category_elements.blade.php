<div class="card-columns">
    @foreach ($categories as $key => $category)
        <div class="card shadow-none border-0">
            <ul class="list-unstyled mb-3">
                <li class="fs-14 fw-700 mb-3">
                    <a class="text-reset hov-text-danger" href="{{ route('category_wise_products', $category['ExternalId']) }}?name={{ urlencode($category['Name']) }}"  
                    onclick="showGlobalLoader();">
                        {{ $category['Name'] }}
                    </a>
                </li>
            </ul>
        </div>
    @endforeach
</div>
