@extends('layouts.app')

@section('content')

    <section class="breadcrumb-layer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mt-2 py-3">
                    <h5><a href="/" class="text-dark">Əsas səhifə</a> › Sifarişlər</h5>
                </div>
            </div>
        </div>
    </section>

    @if (isset($cart_data) && count($cart_data) > 0)
        <section class="order-form">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <h2>SİFARİŞ FORMU</h2>
                        <form id=order-form action="" method="POST">
                            @csrf
                            @foreach ($cart_data as $key => $data)
                            <input type="hidden" name="items[{{$key}}][id]" value="{{ $data['item_id'] }}">
                            <input type="hidden" name="items[{{$key}}][quantity]" value="{{ $data['item_quantity'] }}">
                            @endforeach
                            <div class="form-floating mb-3">
                                <input type="text" name="fullname" class="form-control" id="fullName" placeholder="" maxlength="35"
                                    required>
                                <label for="fullName">Ad Soyad</label>
                                <div class="invalid-feedback">
                                    Zəhmət olmasa ad soyad daxil edin.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number" name="phone" class="form-control" id="number" placeholder="" maxlength="25"
                                    required>
                                <label for="number">Əlaqə nömrəsi</label>
                                <div class="invalid-feedback">
                                    Zəhmət olmasa əlaqə nömrəsi daxil edin.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" name="mail" class="form-control" id="mail" placeholder="" maxlength="45"
                                    required>
                                <label for="mail">Email</label>
                                <div class="invalid-feedback">
                                    Zəhmət olmasa email daxil edin.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="address" class="form-control" id="address" placeholder="" maxlength="120"
                                    required>
                                <label for="address">Ünvan</label>
                                <div class="invalid-feedback">
                                    Zəhmət olmasa ünvan daxil edin.
                                </div>
                            </div>
                            <div class="form-floating">
                                <textarea name="note" class="form-control" placeholder="Qeyd əlavə edin" id="note" style="height: 100px" maxlength="335"
                                    required></textarea>
                                <label for="note">Qeyd</label>
                                <div class="invalid-feedback">
                                    Zəhmət olmasa qeyd daxil edin.
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if (isset($cart_data) && count($cart_data) > 0)
                        @if (Cookie::get('shopping_cart'))
                            @php $total="0" @endphp
                            <div class="shopping-cart">
                                <div class="shopping-cart-table">
                                    <div class="table-responsive">
                                        <div class="col-md-12 text-right mb-3">
                                            <a href="javascript:void(0)" class="clear_cart font-weight-bold">Səbəti
                                                sıfırla</a>
                                        </div>
                                        <table class="table table-bordered my-auto  text-center">
                                            <thead>
                                                <tr>
                                                    <th class="cart-description">Şəkil</th>
                                                    <th class="cart-product-name">Məhsul adı</th>
                                                    <th class="cart-price">Qiymət</th>
                                                    <th class="cart-qty">Say</th>
                                                    <th class="cart-total">Cəmi qiymət</th>
                                                    <th class="cart-romove">Sil</th>
                                                </tr>
                                            </thead>
                                            <tbody class="my-auto">
                                                @foreach ($cart_data as $data)
                                                    <tr class="cartpage">
                                                        <td class="cart-image">
                                                            <a class="entry-thumbnail" href="javascript:void(0)">
                                                                <img src="{{ asset('/uploads/products/' . $data['item_image']) }}"
                                                                    width="70px" alt="">
                                                            </a>
                                                        </td>
                                                        <td class="cart-product-name-info">
                                                            <h4 class='cart-product-description'>
                                                                <a href="/product/{{ $data['item_id'] }}" target="_blank">{{ $data['item_name'] }}</a>
                                                            </h4>
                                                        </td>
                                                        <td class="cart-product-sub-total">
                                                            <span
                                                                class="cart-sub-total-price">{{ number_format($data['item_price'], 2) }}</span>
                                                        </td>
                                                        <td class="cart-product-quantity" width="130px">
                                                            <span
                                                                class="cart-sub-total-price">{{ $data['item_quantity'] }}</span>
                                                        </td>
                                                        <td class="cart-product-grand-total">
                                                            <span
                                                                class="cart-grand-total-price">{{ number_format($data['item_quantity'] * $data['item_price'], 2) }}</span>
                                                        </td>
                                                        <td style="font-size: 20px;">
                                                            <i class="fa-solid fa-trash delete-cart-data"></i>
                                                            <input type="hidden" value="{{ $data['item_id'] }}">
                                                        </td>
                                                        @php $total = $total + ($data["item_quantity"] * $data["item_price"]) @endphp
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table><!-- /table -->
                                    </div>
                                </div><!-- /.shopping-cart-table -->
                                <div class="row">

                                    <div class="col-md-4 offset-md-8 col-sm-12">
                                        <div class="cart-shopping-total">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <h6 class="cart-subtotal-name">Ümumi ödəniləcək məbləğ: </h6>
                                                </div>
                                                <div class="col-md-5">
                                                    <h6 class="cart-subtotal-price">
                                                        <span
                                                            class="cart-grand-price-viewajax">{{ number_format($total, 2) }}AZN</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="button"
                                                        class="btn btn-danger order-selected-products">Sifarişi
                                                        tamamla</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /.shopping-cart -->
                        @endif
                    @else
                        <div class="row">
                            <div class="col-md-12 mycard py-5 text-center">
                                <div class="mycards">
                                    <h4>Sizin səbətiniz boşdur</h4>
                                    <a href="/">Alış-verişə davam edin</a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div> <!-- /.row -->
        </div><!-- /.container -->
    </section>

@endsection

@section('js')
    <script>
        $(document).ready(function() {

            $('.order-selected-products').click(function(e) {

                var data = {
                    fullName: "",
                    number: "",
                    mail: "",
                    address: "",
                    note: ""
                }

                var isValid = true;

                $(".form-floating").each(function() {
                    const inpTag = $(this).find(".form-control");

                    if (inpTag.val().length > 0) {
                        data[inpTag.attr("id")] = inpTag.val();
                        $(this).find(".invalid-feedback").removeClass("error-warning");
                    } else {
                        isValid = false;
                        $(this).find(".invalid-feedback").addClass("error-warning");
                    }
                })

                // console.log(data)

                if (isValid) {
                    //call api with ajax
                    $.ajax({
                        url: '/order',
                        type: 'POST',
                        data: $('#order-form').serialize(),
                        success: function(response) { 
                            if(response.data.error == null) {
                                window.location.href = "/success-order";
                            }
                        }
                    });
                }

            });

            $('.cartpage .delete-cart-data').click(function(e) {

                var product_id = $(this).parent().find('input').val();

                deleteCartItem(product_id, true);

            });

            $('.clear_cart').click(function(e) {

                $.ajax({
                    url: '/clear-cart',
                    type: 'GET',
                    success: function(response) {
                        window.location.reload();
                        alertify.set('notifier', 'position', 'top-right');
                        alertify.success(response.status);
                    }
                });

            });

        });
    </script>
@endsection
