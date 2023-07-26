<!--header-->    
<header class="index-heading">
    <section class="head11 flex">
        <div class="head11-left">
            <img src="{{ asset('images/flag.gif') }}" alt="" width="35" height="20">
            <h3>Gift Basket and Hampers Delivery in Germanyy</h3>
        </div>
        <div class="head11-right">
            <a href="" alt="Corporate Orders">Corporate Orders</a>
        </div>
    </section>

    <section class="head2 flex">
        <div class="logo">
            <div class="logo-img" onclick="window.location.href='{{ url('/') }}'">
                <img src="{{ asset('images/sitelogo_new.webp') }}" alt="Logo" width="312" height="82">
            </div>
            <p>Send Gift Baskets & Hampers to Germany</p>
        </div>

        <div class="search-bar2">
            <div class="search-bar2-main">
            <input type="text" name="searchKey" id="searchKey" placeholder="Search flowers, cakes etc." pattern=".{3,}" required="" title="3 characters minimum">
            <button type="submit" id="searchbtnn" aria-label="searchbtnn" class="search-btn serbtnnew"><i class="fa fa-search"></i></button>
            </div>
            <label id="searchAlert"></label>
        </div>

        <div class="head2-right flex">
            <a href="" class="head2-options" aria-label="contact us">
                <p>Contact Us</p>
            </a>
            @if(Auth::user())
                <a href="{{ route('users.logout') }}" class="head2-options" title="Logout" aria-label="login">
                <i class="fa-solid fa-right-from-bracket"></i>
                </a>
                <a href="{{ route('users.dashboard') }}" class="head2-options" title="Dashboard" aria-label="login">
                    <i class="fa-solid fa-user" title="Dashboard"></i>
                    <!-- <i class="fa-solid fa-briefcase"></i> -->
                </a>
                <a href="" class="head2-options">
                    <i class="fa-solid fa-gift" alt="Track Order" aria-label="Track Order" title="Track Order"></i>
                    
                </a>
            @else
                <a href="{{ route('users.login') }}" class="head2-options" title="Login" aria-label="login">
                    <i class="fa-solid fa-user"></i>
                </a>
                <a href="" class="head2-options">
                    <i class="fa-solid fa-gift" alt="Track Order" aria-label="Track Order" title="Track Order"></i>
                </a>
            @endif    
            <a href="" class="head2-options">
                <i class="fa-solid fa-cart-shopping" alt="Shopping Cart" aria-label="cart" title="cart"></i>
            </a>
            
        </div>
    </section>
</header>

<!--Navbar-->
<nav class="categories flex">
    <div class="view-cat" onClick="pc_menu()"><i class="fa-solid fa-bars"></i><i class="fa-solid fa-xmark" style="display:none;"></i>VIEW ALL CATEGORIES</div>
    <ul class="flex PC-menu">
    <li class="cat-menu "><a onClick="cat_menu_show()" class="category-btn">All Categories<span class="fa fa-caret-down"></span></a>
    <div id="cat-mega-menu">
    <ul class="cat-menu-ul">
    <li><a  href="gifts_usa.asp" title="Exclusive Gift Baskets">Gourmet Gift Baskets</a></li>
    <li><a href="winegifthampers.asp" title="Wine Gift Baskets">Wine Gift Baskets</a></li>
    <li><a href="chocolates_usa.asp" title="chocolate">Chocolates</a></li>
    <li><a href="hampers.asp" title="Hampers">Hampers</a></li>
    <li><a href="luxury-gift.asp" title="Luxury Gift Baskets">Luxury Gift Baskets</a></li>					
        </ul>
    <ul class="cat-menu-ul">
    <li><a href="fruits_usa.asp" title="Fruit Gift Baskets">Fruit Gift Baskets</a></li>
    <li><a href="plants_usa.asp" title="Plants">Plants</a></li>
    <li><a href="flowers_usa.asp" title="Fresh Flowers">Fresh Flowers</a></li>
    <li><a href="christmas-hampers.asp" title="Christmas Hampers">Christmas Hampers</a></li>
    <li><a href="non-alcohol-gift.asp" title="No-Alcohol Gift Baskets">No-Alcohol Baskets</a></li>
    </ul>
    <ul class="cat-menu-ul">
    <li><a href="sameday_usa.asp" title="Same Day Delivery">Same Day Delivery</a></li>
    <li><a href="gift-basket.asp" title="Gift Baskets">Gift Baskets</a></li>
    <li><a href="corporate-gift.asp" title="Corporate Gifts">Corporate Gifts</a></li>
    <li><a href="kosher-gift.asp" title="Kosher Gift Baskets">Kosher Gift Baskets</a></li>
    </ul>
    <ul class="cat-menu-ul">
    <li><a href="hampers_scottish.asp" title="Scottish Hamper">Scottish Hamper</a></li>
        <li><a href="hampers_british.asp" title="British Hamper">British Hamper</a></li>
    <li><a href="hampers_irish.asp" title="Irish Hamper">Irish Hamper</a></li>
    <li><a href="hampers_glutenfree.asp" title="Gluten Free Hamper">Gluten-Free Hamper</a></li>
    </ul>
    </div>
    </li>
    <li class="oc-menu" onClick="abc()"><a href="#" class="occation-btn">All Occasions<span class="fa fa-caret-down"></span></a>
    <div id="oc-mega-menu">
    <ul class="oc-menu-ul">
    <li><a href="anniversary_usa.asp" title="Anniversary">Anniversary</a></li>
    <li><a href="birthday_usa.asp" title="Birthday">Birthday</a></li>
    <li><a href="congratulation_usa.asp" title="Congratulation">Congratulation</a></li>
    <li><a href="condolence_usa.asp" title="Condolence">Condolence</a></li>
    </ul>
    <ul class="oc-menu-ul">
    <li><a href="newborn_usa.asp" title="New Born">New Born</a></li>
    <li><a href="wedding_usa.asp" title="Wedding">Wedding</a></li>
    <li><a href="halloween_usa.asp" title="Halloween Gifts ">Halloween Gifts</a></li>
    </ul>
    <ul class="oc-menu-ul">
    <li><a href="fathersday_usa.asp" title="Father's Day ">Father' Day</a></li>
    <li><a href="christmas_usa.asp" title="Christmas Gifts">Christmas Gifts</a></li>
    <li><a href="mothersday_usa.asp" title="Mother's Day">Mother's Day</a></li>
    </ul>
    <ul class="oc-menu-ul">
    <li><a href="newyear_usa.asp" title="New Year">New Year Gifts</a></li>
    <li><a href="thanksgivingday.asp" title="Thanks Giving Day">Thanksgiving Gifts</a></li>
    <li><a href="valentine_usa.asp" title="Valentine Day">Valentine Day</a></li>
    </ul>
    </div>
    </li>
    <li class=""><a class=""href="mothersday_usa.asp" title="Father's Day">Father's Day</a></li>
    <li><a class="birthday-btn" href="birthday_usa.asp" title="Birthday">Birthday</a></li>
    <li><a class="wine-btn"href="winegifthampers.asp" title="Wine">Wine</a></li>
    <li><a class="location-btn" href="location.asp" title="Location">Where we deliver</a></li>
    <li  class="location-li"><a class="location-li-btn"href="sell-products.asp" title="Sale">Sale</a></li>
    </ul>
</nav>