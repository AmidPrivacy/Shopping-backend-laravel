@extends('layouts.app')

@section('content')
    <!-- SECTION  -->
    <section class="home-page product-name-and-breadcrumb">
        <div class="custom-container d-flex filter-row">
            <div class="align-items-center"><h1>{{ $data->name }}</h1></div>
            <div class="home-shop">

                <nav>
                    <ul class="home-shop-icon"> 
                        <li><i class="fa-solid fa-house" id="home"></i></li>
                        <li><a href="/">Əsas səhifə</a></li>
                        @if($data->categoryName !==null)
                            <li class="slash"><a href="/public/sub-categories/{{ $data->categoryId }}">{{ $data->categoryName }}</a></li>
                        @endif
                        <li class="slash"><a href="#" id="product-image-color">{{ $data->name }}</a></li>
                    </ul>
                </nav>

            </div> 
        </div>

    </section>


    <!-- SECTION 2 -->
    <section class="section-two product-image-details">
        <div class="custom-container">
            <div class="detail-custom-container">

                <div class="product-img">
                    <img src="/uploads/products/{{ count($data->images)>0 ? $data->images[0]->name: ''}}" alt="">
                    <div class="image-tab-box">
                        @foreach($data->images as $key => $image)
                        <img src="/uploads/products/{{ $image->name}}" class="{{ $key===0 ? 'active-tab' : '' }}" alt="" >
                        @endforeach
                    </div>
                </div>

                <div>
                    <div class="product-details">

                        <h3>{{ $data->name }}</h3>

                        <div class="rating">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half"></i>
                        </div>



                        <div class="pr">

                            <span class="product-price">

                                <h4>
                                  {{ $data->price }}AZN
                                    <!-- <del class="text-muted-h4" id="sale">2600$</del> -->

                                </h4>

                            </span>

                            <ul>
                                <!-- <li>Availibility: <span>In Stock</span> </li> -->
                                <li>Kateqoriya: <span>{{ $data->categoryName }}</span> </li>

                            </ul>

                        </div>

                        <p id="prod-det-p">
                          {{ $data->description }}
                        </p>

                    </div>

                    <div class="align-items card-actions">
                        <div class="product-num">
                            <i class="fa-solid fa-minus iconplus"></i>
                            @csrf
                            <input type="hidden" class="product-id" value="{{ $data->uuid }}">
                            <input type="number" id="product-count" name="product" value="1">
                            <i class="fa-solid fa-plus iconminus"></i>
                        </div>
                        <div class="cart-action-box">
                            <button><i class="fa-solid fa-cart-shopping"></i>Səbətə at</button>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- SECTION TABLE -->
    <section class="section-table">
        <div class="custom-container">

            <ul class="nav nav-tabs" id="product-details-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false">
                        Ətraflı məlumat
                    </button>
                </li>
                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                        Description
                    </button>
                </li>  -->
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="product-table">
                        <table>
                          @foreach($data->specifications as $sp)
                            <tr>
                              <td>{{ $sp->name }}</td>
                              <td>{{ $sp->value }}</td>
                            </tr>
                          @endforeach

                          @foreach($data->values as $value)
                            <tr>
                              <td>{{ $value->type }}</td>
                              <td>{{ $value->name }}</td>
                            </tr>
                          @endforeach
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"></div>
            </div>
        </div>
    </section>


    <!-- Suggested products -->
    @if(count($data->others)>0)
    <section class="product-slider">
        <div class="custom-container">

            <div class="less-title">
                <h6>Təklif olunan</h6>
                <h2>Oxşar məhsullar</h2>
            </div>


            <!-- Slider -->
            <div class="addition-offers owl-carousel owl-theme">
                @foreach($data->others as $other)
                    @if(count($other->images)>0)
                    <div class="item">
                        <a href="">
                            <img src="/uploads/products/{{ $other->images[0]->name }}" alt="" >
                            <h3>{{ $other->name }}</h3>
                            <p>{{ $other->price }} AZN</p>
                        </a>
                    </div>
                    @endif
                @endforeach
            </div>

        </div>
    </section>
    @endif



@endsection

@section('js')
    <script src="../assets/js/owl.carousel.min.js"></script>
    <script>
        $( document ).ready(function() {
            $(".image-tab-box img").click(function(){
                let path = $(this).attr("src");
                $(".product-img>img").attr("src", path);

                $(".image-tab-box img").attr("class", "");
                $(this).attr("class", "active-tab");
            });

            $(".product-num .fa-minus").click(function(){
                let currentValue = $(".product-num #product-count").val();
                if(Number(currentValue)>1) {
                    $(".product-num #product-count").val(Number(currentValue)-1);
                }
            });

            $(".product-num .fa-plus").click(function(){

                let currentValue = $(".product-num #product-count").val();
                $(".product-num #product-count").val(Number(currentValue)+1);

            });

            $('.cart-action-box button').click(function (e) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var product_id = $('.product-id').val();
                var quantity = $(".product-num #product-count").val();

                $.ajax({
                    url: "/add-to-cart",
                    method: "POST",
                    data: {
                        'quantity': quantity,
                        'product_id': product_id,
                    },
                    success: function (response) {
                        loadCartCount();
                    },
                });
            });

            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                dots: false,
                autoplay:true,
                nav:true,
                navText: ['<i class="fa-solid fa-light fa-arrow-left"></i>',"<i class='fa-solid fa-light fa-arrow-right'></i>"],
                responsive:{
                    0:{
                        items:1,
                        nav: true,
                    },
                    600:{
                        items:3,
                        nav:true,
                    },
                    1000:{
                        items: 3,
                        nav: true,
                        // navText: [&#x27;next&#x27;,&#x27;prev&#x27;]
                    }
                }
            })
        });


    </script>
@endsection
