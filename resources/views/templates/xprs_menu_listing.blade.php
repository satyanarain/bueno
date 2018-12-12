<script type="text/html" id='xprs-menu-listing'>
  <% _.each(items.items ,function(item,key,list){ %>
    <div class="listing_padding col-xs-12 col-sm-6 col-md-4">
      <div class="listing_item">
        <div class="listing_image">
            <% if(item.status == 2){ %>
                <div class="side_ribbon"><span>{{ config('bueno.item_status')[2] }}</span></div>
            <% } %>
            <% if(item.today_special && item.status != 2 && item.stock != 0){ %>
            <div class="side_ribbon special_ribbon"><span>Today's Special</span></div>
            <% } %>
            <% if(item.stock == 0){ %>
            <div class="side_ribbon"><span>Out Of Stock</span></div>
            <% } %>
            <% if(item.original_price - item.discount_price > 0 && item.status == 1 && item.stock != 0 && !item.today_special){ %>
            <div class="side_ribbon"><span>Offer</span></div>
            <% } %>
          <a href="<%= item.item_url %>">
            <span class="item-image" style="background-image:url(<%= item.image_url %>)"></span>
          </a>
        </div> <!-- listing_image ends -->
        <div class="listing_body">
          <div class="details">
            <a href="<%= item.item_url %>"><h4 class="title"><%= item.name %></h4></a>
            <p class="desc text-muted"><%= item.description %></p>
            <p class="extras text-muted">Serves: <%= item.serves %> <% if(item.spiceLevel && item.spiceLevelName != "") {  %> | Spice: <%= item.spiceLevelName %> <% } %> </p>
          </div> <!-- details ends -->
          <div class="price">
            <p><span class="original_price"><% if(item.original_price > item.discount_price) { %>&#8377; <span class="strike-through"><%= item.original_price %></span><% } %> </span> &#8377; <%= item.discount_price %>/-</p>
          </div> <!-- price ends -->
        </div> <!-- listing_body ends -->
        <div class="listing_category <% if(item.type != 1){ %> non-veg <% }else{ %> veg <% } %>"></div> <!-- listing_category ends -->
        <div class="listing_footer">
          <div class="sec_table">
            <div class="sec_table_row">
                <div class="social">
                    <a class="<%= item.is_favorite %>" data-id="<%= item.id %>" data-url="{{ route('users.saved_items.post') }}" data-token="{{ csrf_token() }}" href=""><i class="ion-android-favorite"></i></a>
                    <div class="social_dropdown dropdown">
                        <a class="share_popup" type="button" id="sharePopup" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="ion-android-share-alt"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="sharePopup">
                            <li class=""><a class="social_facebook share-popover" href="https://www.facebook.com/sharer/sharer.php?u=<%= item.item_url %>"><i class="ion-social-facebook"></i></a></li>
                            <li class=""><a class="social_twitter share-popover" href="https://twitter.com/home?status=<%= item.item_url %>"><i class="ion-social-twitter"></i></a></li>
                            <li class=""><a class="social_instagram share-popover" href="https://pinterest.com/pin/create/button/?url=<%= item.item_url %>&media=<%= item.image_url %>&description=<%= item.description %>"><i class="ion-social-pinterest"></i></a></li>
                            <li class=""><a class="social_instagram share-popover" href="https://www.linkedin.com/shareArticle?mini=true&url=<%= item.item_url %>=<%= item.name %>&summary=<%= item.description %>"><i class="ion-social-linkedin"></i></a></li>
                        </ul>
                    </div> <!-- social_dropdown ends -->
                    <span class="text-muted">Quantity</span>
                    <div class="quantity_select sec_table_cell">
                        <select id="itemQuantity" class="form-control item_quantity_select">
                            @foreach(range(1, 10) as $value)
                                <option value="{{ $value }}" @if($value == 1) selected @endif>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div> <!-- sec_table_cell -->
                </div> <!-- social ends -->
                <a href="" class="btn btn-primary action add_to_cart <% if(item.status == 2 || item.stock == 0){ %> disabled <% } %>" data-token="{{ csrf_token() }}" data-id="<%= item.id %>" data-url="{{ route('users.cart.post') }}">Add to Cart</a>
            </div> <!-- sec_table_row ends -->
          </div> <!-- sec_table ends -->
        </div> <!-- listing_footer ends -->
      </div> <!-- listing_item ends -->
    </div> <!-- listing_padding ends -->
  <% }) %>

  <% if(items.items.length == 0) { %>
  <div class="col-xs-12 placeholder_message">
      <h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Sorry, there are no results to show.</h4>
  </div> <!-- placeholder_message ends -->
<% }%>

  </script>