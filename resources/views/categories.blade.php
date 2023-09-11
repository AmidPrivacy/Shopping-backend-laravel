@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="/assets/css/categories.css">
@endsection
@section('content') 
 
    <!-- Category name and bredcrumb  -->
    <section class="home-page">
        <div class="custom-container d-flex" style="justify-content: space-around; width: 100%">
            @if($category !==null)
            <div class="align-items-center">
                <h1>{{ $category[0]->name }}</h1>
            </div>
            @endif
            <div class="home-shop"> 
                <nav>
                    <ul class="home-shop-icon">
                        <li><i class="fa-solid fa-house" id="home"></i></li>
                        <li><a href="/">Əsas səhifə</a></li> 
                        @if($category !==null)<li class="slash"><span>{{ $category[0]->name }}</span></li>@endif
                    </ul>
                </nav> 
            </div>
        </div> 
    </section>

    <!--------------------- Filter and products ---------------------->
    <section class="section-three">
        <div class="custom-container">

            <div class="row">
                <div class="col-sm-md-12">

                    <div class="section-three-content">
                        @if($isParent)
                        <div class="categories">

                            <div class="reset-button">
                                <button type="button" class="bg-transparent p-0 text-dark btn btn-secondary">
                                    Reset
                                </button>
                            </div>


                            <h4 id="categories">Kateqoriyalar</h4>

                            @foreach($subcategories as $key => $category)
                            <div class="form-check">
                                <input id="Women" type="checkbox" class="form-check-input" value="{{ $category->id }}">
                                <label class="form-check-label" for="Woman">
                                    {{ $category->name }}
                                </label> 
                            </div>
                            @endforeach


                            <!-- PRICE FILTER ///////////////////////////////////////////////////// -->
                            <div class="price-filter">
                                <h4>Qiymət</h4>
                                <button class="price-filter-clear">clear</button>

                                <div class="price-slider">
                                    <div class="price-content">
                                        <div>
                                            <!-- <label>Min</label> -->
                                            <p id="min-value" class="min-value">0</p>
                                        </div>

                                        <div>
                                            <!-- <label>Max</label> -->
                                            <p id="max-value" class="max-value">10000</p>
                                        </div>
                                    </div>

                                    <div class="range-slider">
                                        <input type="range" class="max-price" value="10000" min="0" max="10000" step="1">
                                    </div>
                                </div>

                                <hr class="hr-line" />
                            </div>
  
                            <!-- SIZE FILTER ////////////////////////-->
                            @foreach($types as $key => $type)
                            <div class="size-filter">
                                <div class="size-content">
                                    <h4>{{ $type->name }}</h4>
                                </div>
 
                                <div class="size">
                                    @foreach($type->values as $key => $value)
                                    <button class="button-size" data-id="{{ $value->id }}">{{ $value->name }}</button>
                                    @endforeach 
                                </div>

                            </div>
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Products /////////////// -->
                        <div class="categories-content">
                            @if($isParent)
                            <div class="filter-row">
                                <div class="pagination-column">
                                    Məhsul: {{ $currentRange }} <b>({{ $totalCount }})</b> 
                                </div>
 
                                <select class="filter-sorting" aria-label="Default select example">
                                    <option selected value="">Sıralama</option> 
                                    <option value="0">Ən yenilər</option>
                                    <option value="1">Bahadan ucuza</option>
                                    <option value="2">Ucuzdan bahaya</option>
                                </select>
                            </div>
                            @endif
                             
                            <div class="product-box">
                            @foreach($products as $key => $product)
                                @if(count($product->images)>0)
                                <div class="product-item">
                                    <img src="/uploads/products/{{ $product->images[0]->name }}" alt=""> 
                                    <div class="card-body">
                                        <div class="product-info">
                                            <a href="/product/{{ $product->uuid }}">{{ $product->name }}</a>
                                        </div>
                                        <div class="intro">
                                            <span class="last-price"> 
                                                {{ $product->price }} AZN
                                            </span>
                                            <div class="reyting">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i> 
                                            </div> 
                                        </div>
                                    </div> 
                                </div>
                                @endif
                            @endforeach
                            </div>
                            @if($totalCount>10 && $isParent)
                            <div class="pagination-box">
                                <button>Prevision</button> 
                                <div class="pagination">
                                    @for ($i = 1; $i < ceil($totalCount/10)+1; $i++)
                                        <a href="#" class="{{ $currentPage === $i ? 'is-selected' : '' }}" >{{ $i }}</a>
                                    @endfor 
                                </div> 
                                <button>Next</button> 
                            </div>
                            @endif
                        </div>
 
                    </div>
                </div>

            </div>
    </section>
@endsection

@section('js')
    <script src="/assets/js/jquery-3.js"></script> 
    <script>
        $( document ).ready(function() {

            $(".size-filter .size .button-size").click(function(){
                $(this).toggleClass("checked-btn");
                filter();
            });

            $(".price-filter button").click(function(){
                $(".price-content #max-value").text("10000");
                $(".range-slider .max-price").val("10000");
                filter();
            });

  
            $(".max-price").change(function() { 
                $("#max-value").text($(this).val()); 
                setTimeout(() => {  filter() }, 1000);
            }); 

            $(".form-check .form-check-input, .filter-row .filter-sorting").change(function() { 
                filter();
            }); 

            $(".reset-button button").click(function() { 
                $(".size-filter .size .button-size").removeClass("checked-btn");
                $(".price-content #max-value").text("10000");
                $(".range-slider .max-price").val("10000");

                $( ".form-check .form-check-input" ).prop( "checked", false );
                $(".filter-row .filter-sorting").val("")

                setTimeout(() => {  filter() }, 1000);
            }); 

            


            function filter() {

                var categories = [];
                $('.form-check .form-check-input:checked').each(function(i){
                    categories[i] = $(this).val();
                });

                const price = $(".range-slider .max-price").val();

                var values = [];
                $('.size-filter .button-size.checked-btn').each(function(i){
                    values[i] = $(this).attr("data-id");
                });

                const order = $(".filter-row .filter-sorting").val();

                const uuid = location.pathname.split("/")[location.pathname.split("/").length-1];

                $.post( "/product-filter", 
                    { 
                        id: uuid, 
                        valueIds: values.join(","),
                        categoryIds: categories.join(","),
                        order,
                        endPrice: price,
                }).done(function( data ) { 

                    const str = data.products.map(res=> {
                    
                        if(res.images.length>0){ 
                            return `<div class="product-item">
                                    <img src="uploads/products/${res.images[0].name}" alt=""> 
                                    <div class="card-body">
                                        <div class="product-info">
                                            <a href="/product/${res.uuid}">${res.name}</a>
                                        </div>
                                        <div class="intro">
                                            <span class="last-price"> 
                                                ${res.price} AZN
                                            </span>
                                            <div class="reyting">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i> 
                                            </div> 
                                        </div>
                                    </div> 
                                </div>
                            `
                        } else {
                            return '';
                        }
                    });

                    $(".section-three-content .product-box").html(str.join(" "))

                
                }); 
            }

        });
    </script> 
@endsection