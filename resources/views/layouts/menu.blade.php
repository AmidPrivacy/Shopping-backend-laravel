<ul>

    <li> <a href="/">Əsas səhifə</a> </li>

    @foreach($menus as $key => $menu)
    <li>
        <a href="{{ $menu->is_product===0 ? '#' : '/special-products/'.$menu->uuid }}">{{ $menu->name }}</a> 
        @if(count($menu->categories)>0)
        <i class="fa-solid fa-chevron-down icon-down"></i>
        <div class="under-menu-box">
            @foreach($menu->categories as  $category)
            <div class="main-category-item">
                <a href="/categories/{{ $category->uuid }}">
                    {{ $category->name }}
                    @if(count($category->subs)>0)
                        <i class="fa-solid fa-chevron-right icon-down"></i>
                    @endif
                </a>
                @if(count($category->subs)>0)
                <i class="fa-solid fa-chevron-down icon-down"></i>
                <div class="sub-category-box">
                    @foreach($category->subs as  $sub)
                        <a href="/sub-categories/{{ $sub->uuid }}">{{ $sub->name }}</a>
                    @endforeach
                </div>
                @endif
            </div> 
            
            @endforeach
        </div>
        @endif
    </li>
    @endforeach
    
</ul> 