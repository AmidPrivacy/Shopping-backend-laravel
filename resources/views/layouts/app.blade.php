<!doctype html>
<html lang="az">
<head>  
    <title>Məhsul haqqında</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
 
    <!-- linkler -->
    <link rel="stylesheet" href="/assets/css/ekocart.css">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/assets/css/all.min.css"> 
    @yield('css') 
    <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
    <header>
        <div class="header-top">
            <div class="custom-container">

                <div class="header-top-item">

                    <i class="fa-solid fa-store"></i>
                    <span>Əlçatan mağazasına xoş gəlmisiniz</span>
                </div>

                <div class="header-top-item">

                    <i class="fa-solid fa-truck"></i>
                    <span>İstənilən məhsulu çatdırılma xidməti</span>

                </div>

                <div class="sosial-icons">
                    <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://twitter.com/?lang=ru"><i class="fa-brands fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="https://www.instagram.com/"><i class="fa-brands fa-instagram"></i></a>
                </div>

            </div>

        </div>

        <!-- header 2 -->
        <div class="header-brand">
            <div class="custom-container">

                <div class="brand">
                    <a href="/">
                        <img src="/assets/img/brand.svg" alt="photo">
                    </a>
                </div>

                <div class="call-us">
                    <div>
                        <i class="fa-solid fa-mobile-screen"></i>
                    </div>
                    <span> Bizimlə əlaqə </span>
                    <a href="#">+994-77-717-77-17</a>
                </div>

                <!-- search bootstrap -->
                <form class="search" method="get" action="/search"> 
                    <input type="search" placeholder="Axtar..." name="name" required>
                    <button class="btn" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <div class="auto-complete"><ul class="list-group"></ul></div>
                </form>

                <!-- navbar -->
                <div class="navbar-layer"> 
                    <nav>
                        @include('layouts.menu')
                    </nav>
 
                    <div class="navbar-icons">  
                        <a href="#" class="card-shopping-details" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <span class="notification" notification="0"></span>
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>
                    </div> 
                    <div class="mobile-toggle">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                </div>
                
            </div>
        </div>

    </header>

    <section class="mobile-menu">
        <div class="mobile-toggle">
            <i class="fa-solid fa-close"></i>
        </div>
        @include('layouts.menu')
    </section>
  
    @yield('content') 

    <!-- FOOTER -->
    <footer class="footer-menu">
        <div class="custom-container">
            <div class="row">
                <div class="col-md-3 footer-top"> 
                    <img src="/assets/img/brand.svg" alt="photo">
                    <h5> Cehizin çeşidliyi, seçimin rahatlığı </h5>
                    <div class="footer-icons">
                        <ul>
                            
                            <li>
                                <a href="https://www.facebook.com/">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            </li>
                            
                            <li>
                                <a href="https://www.instagram.com/">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://twitter.com">
                                    <i class="fa-brands fa-twitter"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://www.linkedin.com">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                            </li> 
                            
                        </ul>
                    </div>
                </div> 
                
                <div class="col-md-4 offset-md-2 footer-top">
                    <div class="second-info" id="info">
                        <h2>Özəlliklər</h2>
                        <ul class="li-style"> 
                            <li><a href="#">Haqqımızda</a> </li>
                            <li><a href="#">Üstünlüklərimiz</a> </li>
                            <li><a href="#">Müraciət və Geri dönüş</a> </li>
                            <li><a href="#">Karyeranızı bizimlə qurun</a></li> 
                        </ul>

                    </div>
                </div>

                <div class="col-md-3 footer-top">
                   
                    <div class="contact">
                        <div class="icon"> <i class="fa-solid fa-map"></i> </div>
                        <h6>Bizim ünvanımız</h6>
                        <p>Sədərək T/M, Bakı şəhəri</p>
                    </div>

                   
                    <div class="contact">
                        <div class="icon"> <i class="fa-solid fa-envelope"></i> </div>
                        <h6>Email us</h6>
                        <p>info@mayakfamily.az</p>
                    </div>
  
                    <div class="contact">
                        <div class="icon"> <i class="fa-solid fa-mobile-screen-button contact-icon"></i> </div>
                        <h6>Phone number</h6>
                        <p>+994 123</p>
                    </div>
 
                    <div class="contact">
                        <div class="icon"> <i class="fa-regular fa-clock"></i> </div>
                        <h6>İş saatları</h6>
                        <p>Bazar ertəsi - Cümə: 10:00 - 22:00</p>
                    </div>
                </div>
            </div>
 
        </div>
        </div>
    </footer>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Məhsul sayı - <span>0</span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="basket-product-box">
                <div class="basket-product-item">
                    <div class="basket-product-description"> 
                        <button type="submit" class="btn btn-secondary" id=""><i class="fa fa-times"></i></button>
                        <a href="/product-checkout"><img class="img-fluid" src="/uploads/products/16851033305.jpg" alt="..."></a>
                    </div>
                    <div class="basket-product-info">
                        <h6>Women Sweater</h6>
                        <div class="product-meta">
                            <span class="mr-2 text-primary">$1800.00</span> x <span class="text-muted">1</span>
                        </div>
                    </div> 
                </div>
            </div>
        
            <hr class="my-5">
            <div class="d-flex justify-content-between basket-total-price">
                <span class="text-muted">Cəmi:</span><span class="text-dark">$1800.00</span>
            </div>
            <a class="btn btn-primary view-cart-details" href="/cart"><i class="las la-shopping-cart mr-1"></i>İndi al</a>
            <a class="btn btn-dark continue-checkout" href="/"><i class="las la-money-check mr-1"></i>Sifarişə davam et</a>
        </div>
    </div>
    <script src="../assets/js/jquery-3.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script> 
    
    <script> 

        function loadCartCount() {
            $.ajax({
                url: '/load-cart-data',
                method: "GET",
                success: function (response) { 
                    var parsed = jQuery.parseJSON(response)
                    var value = parsed; //Single Data Viewing
                    $(".navbar-icons .notification").attr("notification", value['totalcart']); 
                    $("#offcanvasRight .offcanvas-header .offcanvas-title span").text(value['totalcart']); 
                }
            });
        }

        function loadCartProducts() {
            $.ajax({
                url: '/load-basket',
                method: "GET",
                success: function (response) { 
                    var value = jQuery.parseJSON(response);  

                    let str = "";
                    let price = 0;

                    if(value['cart_data']) {
                        value['cart_data'].forEach(item => {
                            price += item.item_price*item.item_quantity;
                            str += `<div class="basket-product-item">
                                        <div class="basket-product-description"> 
                                            <button type="button" class="btn btn-secondary" id="${item.item_id}"><i class="fa fa-times"></i></button>
                                            <img class="img-fluid" src="/uploads/products/${item.item_image}" alt="...">
                                        </div>
                                        <div class="basket-product-info">
                                            <h6><a href="/product/${item.item_id}" target="_blank">${item.item_name}</a></h6>
                                            <div class="product-meta">
                                                <span class="mr-2 text-primary">${item.item_price}AZN</span> x <span class="text-muted">${item.item_quantity}</span>
                                            </div>
                                        </div> 
                                    </div>`;
                        });
                    }

                    $(".basket-product-box").html(str); 
                    $(".basket-total-price .text-dark").text(price+"AZN"); 
                }
            });
        }

        function deleteCartItem(product_id, isCartPage) {
            var data = {
                '_token': $('input[name=_token]').val(),
                "product_id": product_id,
            };

            $.ajax({
                url: '/delete-from-cart',
                type: 'DELETE',
                data: data,
                success: function (response) {
                    if(isCartPage) {
                        window.location.reload();
                    } else {
                        loadCartProducts();
                        loadCartCount()
                    }
                    
                }
            });
        }

         
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            loadCartCount();

            $(".card-shopping-details").click(function (e) { loadCartProducts() });

            $(document).on("click", ".basket-product-description button", function (e) { 
                
                deleteCartItem($(this).attr("id"), false); 
            });

            
            document.addEventListener("click", function (e) {
                if(!document.querySelector('.header-brand .search').contains(e.target)){
                    document.querySelector(".header-brand .search .auto-complete ul").innerHTML = '';
                }
            }, false); 

            $(document).on("keyup focus", ".header-brand .search input", function (e) { 
                
                let val = $(this).val();
                res = document.querySelector(".header-brand .search .auto-complete ul");
                res.innerHTML = '';
                if (val !== '') {
                    $.ajax({
                        url: '/search-autocomplete',
                        type: 'POST',
                        data: {
                            search: val,
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {

                            let str ="";
                            response.data.forEach(item => {

                                str += `<a href="/product/${item.uuid}"><li class="list-group-item">${item.name}(<span>${item.category??'Kateqoriya seçilməyib'}</span>)</li></a>`;
                            
                            });

                            res.innerHTML = str;
                            
                        }, 
                        error: function (err) {
                            console.log(err)
                        }
                    });
                } 

             

 
            });


            $(".header-brand .mobile-toggle").click(function(){

                $(".mobile-menu").addClass("active-menu")
              
            });

            $(".mobile-menu .mobile-toggle").click(function(){
                $(".mobile-menu").removeClass("active-menu")
            })


            $(".mobile-menu ul>li").click(function() {
                const icon = $(this).children("i");

                if(icon.hasClass("fa-chevron-down")) {
                    icon.removeClass("fa-chevron-down");
                    icon.addClass("fa-chevron-up");
                    $(this).find(".under-menu-box").fadeIn();
                } else { 
                    icon.removeClass("fa-chevron-up");
                    icon.addClass("fa-chevron-down");
                    $(this).find(".under-menu-box").fadeOut();
                }
            });

            $(".mobile-menu ul li .under-menu-box").click(function(e) {
                e.stopPropagation(); 
            })

            $(".mobile-menu ul li .main-category-item").click(function() {

                const icon = $(this).children("i");

                if(icon.hasClass("fa-chevron-down")) {
                    icon.removeClass("fa-chevron-down");
                    icon.addClass("fa-chevron-up");
                    $(this).find(".sub-category-box").fadeIn();
                } else { 
                    icon.removeClass("fa-chevron-up");
                    icon.addClass("fa-chevron-down");
                    $(this).find(".sub-category-box").fadeOut();
                }
            })

        });
    </script>
    @yield('js') 
</body>
</html>