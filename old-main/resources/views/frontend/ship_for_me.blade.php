@extends('frontend.layouts.app')
@section('content')
    <section class="mb-4 pt-3">
            <div class="container">
                <div class="row py-5">
                    <div class="col-12">
                        <h2 class="text-center fw-600">You will buy it, We will ship to your doorstep.</h2>
                        <h6 class="text-center fw-300 mb-4">Products are ready to ship? Send the product to our warehouse for
                            hassle-free door-to-door service.</h6>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form id="shipProductForm" class="form-default" role="form"
                            action="{{ route('ship_product_request.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="product_link" class="fs-12 fw-700 text-dark">Product Link</label>
                                <input type="text" class="form-control rounded-0" value="{{ old('product_link') }}"
                                    placeholder="Enter Product Link" name="product_link">
                                @error('product_link')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="product_title" class="fs-12 fw-700 text-dark">Product Title<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-0" required placeholder="Enter Product Title"
                                    name="product_title" value="{{ old('product_title') }}">
                                @error('product_title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category_name" class="fs-12 fw-700 text-dark">Category<span
                                        class="text-danger">*</span></label>
                                <select class="form-control" name="category_name">
                                    <option value="">Select One</option>
                                    @foreach ($categories as $category)
                                        @php
                                            // Fetch children of the current category
                                            $children = $categories->where('parent_id', $category->id);
                                        @endphp

                                        @if ($children->isNotEmpty())
                                            <!-- Show parent with children as a group -->
                                            <optgroup label="{{ ucfirst($category->name) }}">
                                                @foreach ($children as $child)
                                                    <option value="{{ $child->name }}">{{ ucfirst($child->name) }}</option>
                                                @endforeach
                                            </optgroup>
                                        @elseif ($category->parent_id == 0 && $categories->where('parent_id', $category->id)->isEmpty())
                                            <!-- Show parent categories without children as individual options -->
                                            <option value="{{ $category->name }}">{{ ucfirst($category->name) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('category_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label for="description" class="fs-12 fw-700 text-dark">Description<span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control rounded-0" name="description" rows="2"
                                    placeholder="Enter product details like Color: Red, Size: XXL">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="images" class="fs-12 fw-700 text-dark">Images<span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="file" name="images[]" class="form-control" id="inputGroupFile04" multiple
                                        required>
                                </div>
                                @error('images.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="price" class="fs-12 fw-700 text-dark">Price<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-0" required placeholder="0.00"
                                            name="price" value="{{ old('price') }}">
                                        @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="quantity" class="fs-12 fw-700 text-dark">Quantity<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-0" required placeholder="Min: 1"
                                            name="quantity" value="{{ old('quantity') }}">
                                        @error('quantity')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="ship_to" class="fs-12 fw-700 text-dark">Ship to<span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control rounded-0" required name="ship_to"
                                            value="{{ old('ship_to', date('Y-m-d')) }}">
                                        @error('ship_to')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="valid_to" class="fs-12 fw-700 text-dark">Valid to<span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control rounded-0" required name="valid_to"
                                            value="{{ old('valid_to', date('Y-m-d')) }}">
                                        @error('valid_to')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="weight" class="fs-12 fw-700 text-dark">Product Weight<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-0" required placeholder="0.00 KG"
                                            name="weight" value="{{ old('weight') }}">
                                        @error('weight')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="shipping_type" class="fs-12 fw-700 text-dark">Shipping Type<span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="shipping_type">
                                            <option value="">Select One</option>
                                            <option value="air" {{ old('shipping_type') == 'air' ? 'selected' : '' }}>Air
                                            </option>
                                            <option value="ship" {{ old('shipping_type') == 'ship' ? 'selected' : '' }}>Ship
                                            </option>
                                        </select>
                                        @error('shipping_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="length" class="fs-12 fw-700 text-dark">Length (CM)</label>
                                        <input type="number" class="form-control rounded-0" placeholder="" name="length"
                                            value="{{ old('length') }}">
                                        @error('length')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="width" class="fs-12 fw-700 text-dark">Width (CM)</label>
                                        <input type="number" class="form-control rounded-0" placeholder="" name="width"
                                            value="{{ old('width') }}">
                                        @error('width')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="height" class="fs-12 fw-700 text-dark">Height (CM)</label>
                                        <input type="number" class="form-control rounded-0" placeholder="" name="height"
                                            value="{{ old('height') }}">
                                        @error('height')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-4 mt-4">
                                <button type="submit" class="btn btn-danger btn-block fw-700 fs-14 rounded-0" id="addToList">Add To List</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6" id="container_ship_for_me">
                        
                    </div>
                </div>
            </div>
            
            <div class="container" style="padding-top:100px">
                <!-- Top Section -->
                <div class="text-center">
                    <h3 class="fw-700 mb-sm-0">
                        <span class="">{{ translate('How CPL Express Works') }}</span>
                    </h3>
                </div>

                <div class="bg-white py-3 d-flex justify-content-center flex-wrap">
                    <!-- Flowchart for mobile -->
                    <div class="flowchart-box">
                        <img src="{{ static_asset('assets/img/Flowchart.gif') }}" class="lazyload w-100 h-auto"
                            alt="Flowchart for Mobile">
                    </div>

                    <!-- Original images with text for larger screens -->
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Order Placement.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Order Placement">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Order Placement</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Buying Goods.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Buying Goods">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Buying Goods</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Goods Received in China warehouse.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Goods Received in China warehouse">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Goods Received in China Warehouse</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Shipment Done.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Shipment Done">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Shipment Done</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Goods Received in Bangladesh.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Goods Received in Bangladesh">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Goods Received in Bangladesh</span>
                        </div>
                    </div>
                    <div class="icon-box text-center mb-3 mx-2">
                        <img src="{{ static_asset('assets/img/Ready to deliver.png') }}"
                            class="lazyload w-100px h-auto mx-auto has-transition" alt="Ready to deliver">
                        <div class="fs-16 mt-2 text-wrap">
                            <span class="text-dark fw-700">Ready to Deliver</span>
                        </div>
                    </div>

                </div>


            </div>

            <div class="container py-5">
                <h2 class="text-center mb-4">FAQ Section</h2>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="faq-item">
                            <div class="faq-header" data-toggle="faq-body" data-target="#faq1">
                                What Is CPL Express - Ship For Me?
                                <span class="faq-toggle-icon">+</span>
                            </div>
                            <div class="faq-body" id="faq1">
                                CPL Express-Buy & Ship for me is one of the key services provided by CPL Express. It provides
                                you hassle free purchase and shipping service on your desired product directly from the
                                worldwide top ranked marketplaces. This service also provides the overall tracking facility of
                                your shipped product. Your order will proceed with two parties connected with CPL Express; one
                                is Buying Agent and the other one Freight Forwarding Agent.

                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-header" data-toggle="faq-body" data-target="#faq2">
                                What’s your store(1688,Alibaba etc) conversion rate?
                                <span class="faq-toggle-icon">+</span>
                            </div>
                            <div class="faq-body" id="faq2">
                                There’s no fixed conversion rate. When you purchase a product the price shown will include all
                                the charges. But if we update any charges, we will notify you before processing the order
                                through SMS or Email.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-header" data-toggle="faq-body" data-target="#faq3">
                                What is CPL Express Buying Agent?
                                <span class="faq-toggle-icon">+</span>
                            </div>
                            <div class="faq-body" id="faq3">
                                When you purchase a product from a particular Market-place, a CPL Express Buying Agent will be
                                assigned. He will Purchase the product and forward to the CPL Express Forwarding Agent. This CPL
                                Express Buying agent will also communicate with the seller directly about anything related to
                                purchase and send the product to the warehouse.

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="faq-item">
                            <div class="faq-header" data-toggle="faq-body" data-target="#faq4">
                                What is CPL Express Freight Forwarding Agent?
                                <span class="faq-toggle-icon">+</span>
                            </div>
                            <div class="faq-body" id="faq4">
                                CPL Express Freight Forwarding Agent is directly connected with CPL Express. Selecting them, you
                                can enjoy the Freight(Shipping) service from multiple Freight agents. The Freight Agents have
                                their own warehouse (China,USA etc). MovedOn Freight Agent is responsible for receiving products
                                from the warehouse, clearing them from customs then sending products to CPL Express warehouse.

                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-header" data-toggle="faq-body" data-target="#faq5">
                                How Do I contact If I face a problem?
                                <span class="faq-toggle-icon">+</span>
                            </div>
                            <div class="faq-body" id="faq5">
                                When you submit your first order you will be connected with a Dedicated Order Handler within 24
                                to 48 hours. This person will be handling your order and assist you with all the queries
                                regarding your purchase. Besides, you can connect with the CPL Express Facebook page, Email or
                                the helpline number given.

                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-header" data-toggle="faq-body" data-target="#faq6">
                                What payment methods does CPL Express accept?
                                <span class="faq-toggle-icon">+</span>
                            </div>
                            <div class="faq-body" id="faq6">
                                We accept payment through Bkash, Rocket, Debit & Credit cards, Bank deposit etc. We also accept
                                office payments, you can clear the payment coming to our office.

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection


@section('script')
    <script>
        $(document).ready(function() {

            // // Fetch drafted items on page load
            fetchDraftedItems();

            $('.faq-header').click(function() {
                var target = $(this).data('target');
                $(target).slideToggle();
                $(this).toggleClass('active');
                $(this).find('.faq-toggle-icon').text($(this).hasClass('active') ? '-' : '+');
            });

            $('.clearAll').on('click', function() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('clear_all') }}', // Ensure this route is correct
                    success: function(response) {
                        // Show success notification
                        AIZ.plugins.notify('success', response.message);

                        // Reload the page after a slight delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000); // Adjust delay as needed
                    },
                    error: function(xhr, status, error) {
                        console.error('Error executing clear-all:', error);
                        AIZ.plugins.notify('danger', 'An error occurred while clearing cache.');
                    }
                });
            });

            // Function to fetch drafted items
            function fetchDraftedItems() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('ship_product_request.drafted.items') }}',
                    success: function(response) {
                        appendDraftedData(response);
                    },
                    error: function(xhr) {
                        console.error('Error fetching drafted items:', xhr);
                        AIZ.plugins.notify('danger', 'An error occurred while fetching drafted items.');
                    }
                });
            }

            // Function to append drafted data to the accordion container
            function appendDraftedData(data) {
                const $container = $('#container_ship_for_me');
                $container.empty(); // Clear the existing items

                if (Array.isArray(data) && data.length) {
                    let accordionContent = '';

                    // Add a static header above the accordion
                    const headerHtml = `
                        <div class="mb-3">
                            <h5 class="font-weight-bold">Drafted Products</h5>
                        </div>
                    `;
                    $container.append(headerHtml);

                    data.forEach(function(item, index) {
                        accordionContent += `
                         <div class="card mb-2" style="border: 1px solid rgba(0, 0, 0, .125);">
                                <div class="card-header d-flex justify-content-between align-items-center" id="heading${index}">
                                    <a type="button" data-toggle="collapse" data-target="#collapse${index}" aria-expanded="${index === 0 ? 'true' : 'false'}" aria-controls="collapse${index}" class="w-100 d-flex justify-content-between">
                                        <span><strong>${index + 1}</strong> - ${item.product_title}</span>
                                        <div class="d-flex align-items-center">
                                            <span class="ml-2">Category: ${item.category_name}</span>
                                            <span class="ml-2">&#x25BC;</span> <!-- Arrow down icon -->
                                        </div>
                                    </a>
                                </div>

                                <div id="collapse${index}" class="collapse ${index === 0 ? 'show' : ''}" aria-labelledby="heading${index}" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row justify-content-between">
                                            <div class="col-6">
                                                <p class="mb-1"><strong>Product:</strong> ${item.product_title}</p>
                                                <p class="mb-1"><strong>Category:</strong> ${item.category_name}</p>
                                                <p class="mb-1"><strong>Description:</strong> ${item.description}</p>
                                                <p class="mb-1"><strong>Price:</strong> ${item.price}</p>
                                                <p class="mb-1"><strong>Quantity:</strong> ${item.quantity}</p>
                                            </div>

                                            <div class="col-6">
                                                <p class="mb-1"><strong>Weight:</strong> ${item.weight} KG</p>
                                                <p class="mb-1"><strong>Shipping Type:</strong> ${item.shipping_type}</p>
                                                <p class="mb-1"><strong>Ship to:</strong> ${item.ship_to}</p>
                                                <p class="mb-1"><strong>Valid to:</strong> ${item.valid_to}</p>
                                            </div>
                                        </div>
                                        

                                        <!-- Action Buttons placed in the footer -->
                                        <div class="mt-3">
                                            <div class="text-right">
                                                <button class="btn btn-sm btn-secondary duplicate-button" data-id="${item.id}">Duplicate</button>
                                                <button class="btn btn-sm btn-danger delete-button" data-id="${item.id}">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                    });

                    $container.append(`
                        <div class="accordion" id="accordionExample">
                            ${accordionContent}
                        </div>
                    `);

                // Add a card with a button at the bottom of the accordion
                    const footerCardHtml = `
                       <div class="card mt-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <p class="fs-16 mb-0 text-center">Request Rate & Warehouse Address</p>
                                <button class="btn btn-primary" id="requestButton">Request</button>
                            </div>
                        </div>
                    `;
                    $container.append(footerCardHtml);

                    // Attach delete functionality to delete buttons
                    $container.find('.delete-button').on('click', function () {
                        const itemId = $(this).data('id'); 

                        // Send AJAX request to delete the item
                        $.ajax({
                            url: '{{ route("ship_product_request.drafted.item.delete") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', 
                                id: itemId
                            },
                            success: function(response) {
                                AIZ.plugins.notify('success', response.message);
                                // Remove the item from data array and re-render
                                data = data.filter(item => item.id !== itemId);
                                appendDraftedData(data);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error deleting item:', error);
                                AIZ.plugins.notify('danger', 'An error occurred while fetching drafted items.');
                            }
                        });
                    });

                    // Attach duplicate functionality to duplicate buttons
                    $container.find('.duplicate-button').on('click', function () {
                        const itemId = $(this).data('id');
                        const originalItem = data.find(item => item.id === itemId);

                        if (originalItem) {
                            // Create a duplicate of the item
                            const duplicatedItem = { ...originalItem, id: Date.now() }; // Use Date.now() to simulate a unique ID

                            // Optionally, send the duplicated item to the backend
                            $.ajax({
                                url: '{{ route("ship_product_request.drafted.item.duplicate") }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    item: duplicatedItem
                                },
                                success: function(response) {
                                    AIZ.plugins.notify('success', response.message);
                                    // Append duplicated item to data array and re-render
                                    data.push(response.item); // Assume response contains the new item data
                                    appendDraftedData(data);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error duplicating item:', error);
                                    alert('Failed to duplicate item. Please try again.');
                                }
                            });
                        }
                    });

                    // Add event listener for the Request button
                    $('#requestButton').on('click', function () {
                
                        const requestButton = $(this);

                        // Check if spinner already exists, if not, add it
                        if (requestButton.find('.spinner-border').length === 0) {
                            requestButton.prop('disabled', true).append(
                                '<div class="spinner-border" role="status" style="width: 1rem; height: 1rem; margin-left: 0.5rem;">' +
                                '<span class="sr-only">Loading...</span>' +
                                '</div>'
                            );
                        }

                        // Extract only the IDs of drafted items
                        const ids = data.map(item => item.id);

                        // Send the IDs to the backend
                        $.ajax({
                            url: '{{ route("ship_product_request.drafted.items.request") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: ids 
                            },
                            success: function(response) {
                                AIZ.plugins.notify('success', response.message);
                                window.location.href = response.redirect_url;
                            },
                            error: function(xhr, status, error) {
                                console.error('Error requesting drafted items:', error);
                                alert('Failed to request drafted items. Please try again.');
                            },
                            complete: function() {
                                requestButton.prop('disabled', false).find('.spinner-border').remove();
                            }
                        });
                    });

                } else {
                    // If there are no drafted items, show the SVG image
                    $container.append(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="412.24" viewBox="0 0 442 412.24">
                            <g transform="translate(-1144 -575.76)">
                                <ellipse cx="72" cy="16" rx="72" ry="16" transform="translate(1442 956)" fill="#f2f3f1" />
                                <path d="M415.644,220.33V325.342a9.431,9.431,0,0,1-9.4,9.4H9.9a9.431,9.431,0,0,1-9.4-9.4V9.9A9.431,9.431,0,0,1,9.9.5H406.246a9.431,9.431,0,0,1,9.4,9.4V220.33" transform="translate(1144.5 576.26)" fill="#fff" stroke="#ff0000" stroke-miterlimit="10" stroke-width="2" />
                                <path d="M10.066.5h394.1c5.283,0,9.536,3.123,9.566,9.358v20.88H.679A.177.177,0,0,1,.5,30.562V9.874c0-2.485.614-5.229,2.409-6.988S7.526.5,10.066.5Z" transform="translate(1145.492 577.077)" fill="#f2f3f1" />
                                <rect width="61" height="10" rx="5" transform="translate(1324 835)" fill="#fff" />
                                <rect width="351.422" height="65.484" rx="11.54" transform="translate(1176.764 738.998)" fill="#f2f3f1" />
                                <rect width="61" height="10" rx="5" transform="translate(1445 835)" fill="#fff" />
                                <rect width="351.422" height="65.484" rx="11.54" transform="translate(1168.678 634.094)" stroke-dasharray="6" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" fill="none" />
                                <rect width="351.422" height="65.484" rx="11.54" transform="translate(1168.678 733.094)" stroke-dasharray="6" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" fill="none" />
                                <circle cx="7.5" cy="7.5" r="7.5" transform="translate(1161 585)" fill="#fff" />
                                <circle cx="4.5" cy="4.5" r="4.5" transform="translate(1164 588)" fill="#f38a6f" />
                                <circle cx="7.5" cy="7.5" r="7.5" transform="translate(1180 585)" fill="#fff" />
                                <circle cx="4.5" cy="4.5" r="4.5" transform="translate(1183 588)" fill="#f3c707" />
                                <circle cx="7.5" cy="7.5" r="7.5" transform="translate(1199 585)" fill="#fff" />
                                <circle cx="4.5" cy="4.5" r="4.5" transform="translate(1202 588)" fill="#96e297" />
                                <rect width="351.422" height="65.484" rx="11.54" transform="translate(1168.678 823.094)" fill="#dff1cd" />
                                <g transform="translate(14 -356.894)">
                                    <circle cx="21.572" cy="21.572" r="21.572" transform="translate(1176.939 1190.894)" fill="#bfd8a6" />
                                    <path d="M59.541,249,58,261.327a12.327,12.327,0,0,0,5.393,2.311,12.026,12.026,0,0,0,3.852,0l.77-13.867Z" transform="translate(1135.888 963.465)" fill="#f8f8f8" />
                                    <path d="M58.535,232.707c-1.425,1.171-1.464,3.606-1.541,8.475-.054,3.4-.062,5.116.77,6.163,1.849,2.311,6.371,2.773,9.245.77,4.214-2.943,2.473-9.453,2.311-10.015a8.368,8.368,0,0,0-3.852-5.393C63.519,231.643,60.415,231.166,58.535,232.707Z" transform="translate(1136.124 967.432)" fill="#fff" />
                                    <path d="M51.99,230.519c-.316,1.194,1.672,3.143,3.852,3.852,3.852,1.256,7.4-1.972,8.475-.77s-2,4.661-.77,6.163c1.032,1.271,3.975-.247,4.623.77.9,1.425-4.623,4.831-3.852,8.475.385,1.9,2.373,3.405,3.082,3.082s-.162-2,0-4.622c.324-5.27,4.16-6.787,3.852-11.556a6.541,6.541,0,0,0-.77-3.082c-1.934-3.374-6.934-3.667-10.015-3.852C59.509,228.925,52.522,228.509,51.99,230.519Z" transform="translate(1137.276 968.078)" fill="#ff0000" />
                                    <path d="M47.974,268.392c-.847-2.088,4.522-7.28,10.786-8.475,6.872-1.31,14.029,2.311,13.867,4.622C72.35,268.546,49.514,272.159,47.974,268.392Z" transform="translate(1138.21 961.023)" fill="#ff0000" />
                                </g>
                                <rect width="48.984" height="6.756" rx="3.378" transform="translate(1326.134 854.718)" fill="#dff1cd" />
                                <g transform="matrix(0.998, 0.07, -0.07, 0.998, 93.727, -129.494)">
                                    <rect width="351.422" height="65.484" rx="11.54" transform="translate(1166.978 683.925) rotate(7)" fill="#dff1cd" />
                                    <circle cx="21.572" cy="21.572" r="21.572" transform="translate(1185.721 694.89) rotate(16)" fill="#bfd8a6" />
                                    <path d="M70.915,129.858c2.061.952,3.72,3.638,2.976,5.208-1.384,2.909-11.264,2.329-11.9,0C61.452,133.124,66.808,127.961,70.915,129.858Z" transform="translate(1136.491 596.276)" fill="#ff0000" />
                                    <path d="M64.927,134.318c-1.262.664-2.111,1.642-2.271,3.1-.26,2.059.42,4.1,1.236,5.41-1.49-2.132-2.71-4.318-3.467-6.375-.24-.645-.493-1.29-.688-1.965Z" transform="translate(1137.87 594.648)" fill="#ff0000" />
                                </g>
                            </g>
                        </svg>
                `);
                }
            }

            // $(document).on('submit', 'form', function(e) {
            //     e.preventDefault();

            //     var submitButton = $(this).find('button[type="submit"]');

            //     if (submitButton.find('.spinner-border').length === 0) {
            //         submitButton.prop('disabled', true).append(
            //             '<div class="spinner-border" role="status" style="width: 1rem; height: 1rem; margin-left: 0.5rem;">' +
            //             '<span class="sr-only">Loading...</span>' +
            //             '</div>'
            //         );
            //     }

            //     var formData = new FormData(this);

            //     // Perform AJAX request
            //     $.ajax({
            //         type: $(this).attr('method'),
            //         url: $(this).attr('action'),
            //         data: formData,
            //         contentType: false,
            //         processData: false,
            //         success: function(response) {
            //             // Reset the form fields
            //             form.trigger('reset');

            //             AIZ.plugins.notify('success', 'Request submitted successfully!');
            //             // Call function to append drafted data
            //             appendDraftedData(response.data);
            //         },
            //         error: function(xhr) {
            //             AIZ.plugins.notify('danger',
            //                 'An error occurred while submitting the request.');
            //         },
            //         complete: function() {
            //             submitButton.prop('disabled', false).find('.spinner-border').remove();
            //         }
            //     });
            // });

             $(document).on('submit', '#shipProductForm', function(e) {
                e.preventDefault();

                var submitButton = $(this).find('button[type="submit"]');

                // Add spinner to the submit button
                if (submitButton.find('.spinner-border').length === 0) {
                    submitButton.prop('disabled', true).append(
                        '<div class="spinner-border" role="status" style="width: 1rem; height: 1rem; margin-left: 0.5rem;">' +
                        '<span class="sr-only">Loading...</span>' +
                        '</div>'
                    );
                }

                var formData = new FormData(this);
                var form = $(this); // Reference to the form

                // Clear previous error messages
                $('.text-danger').remove(); // Remove previous error messages

                // Perform AJAX request
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Reset the form fields
                        form.trigger('reset');

                        // Display success notification
                        AIZ.plugins.notify('success', response.message);
                        
                        // Call function to append drafted data if needed
                        appendDraftedData(response.data);
                    },
                    error: function(xhr) {
                        // If validation errors are returned
                        if (xhr.status === 422) { // Unprocessable Entity
                            var errors = xhr.responseJSON.errors; // Assuming your server returns errors in this format
                            var errorMessages = []; // Array to hold error messages

                            // Loop through each error and append it to the corresponding field
                            $.each(errors, function(field, messages) {
                                // Find the input field by name
                                var inputField = $('[name="' + field + '"]');
                                // Display the error messages
                                inputField.after('<div class="text-danger">' + messages.join(', ') + '</div>');

                                // Collect the error messages for the notification
                                errorMessages.push(...messages);
                            });

                            // Notify with all validation error messages
                            AIZ.plugins.notify('danger', errorMessages.join('<br>'));
                        } else {
                            // Display a general error notification for other types of errors
                            AIZ.plugins.notify('danger', 'An error occurred while submitting the request.');
                        }
                    },
                    complete: function() {
                        // Re-enable the submit button and remove spinner
                        submitButton.prop('disabled', false).find('.spinner-border').remove();
                    }
                });
            });

        });
    </script>
@endsection
