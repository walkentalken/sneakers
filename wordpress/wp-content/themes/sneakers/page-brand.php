<?php
/*
Template Name: Brand Landing Page
*/
get_header(); ?>

  <section id="primary" class="content-area span8">
    <div id="content" class="site-content" role="main">

      <div class="sorting">
            
        <fieldset>
          <label for="brand">Brand</label>
          <select id="brand" name="brand" class="sneakerSort">
            <option value="all">Show All</option>
            <?php $brand_section = get_categories('taxonomy=brand&type=sneakers');
              foreach ($brand_section as $section) { ?>
              <option value="<?php echo slugify($section->slug); ?>"><?php echo $section->name; ?></option>
            <?php } ?>
          </select> 
        </fieldset>

        <fieldset>
          <label for="type">Type</label>
          <select id="type" name="type" class="sneakerSort">
            <option value="all">Show All</option>
            <?php $type_section = get_categories('taxonomy=type_tax&type=sneakers');
              foreach ($type_section as $section) { ?>
              <option value="<?php echo slugify($section->slug); ?>"><?php echo $section->name; ?></option>
            <?php } ?>
          </select> 
        </fieldset>

        <fieldset>
          <label for="price">Price</label>
          <select id="price" name="price" class="sneakerSort">
            
            <option value="all">Show All</option>    
              <?php $price_section = get_categories('taxonomy=price_tax&type=sneakers');
                foreach ($price_section as $section) { ?>
                <option value="<?php echo slugify($section->slug); ?>"><?php echo $section->name; ?></option>
              <?php } ?>             
          </select>         
        </fieldset>

        <fieldset>
          <label for="demo">Demo</label>
          <select id="demo" name="demo" class="sneakerSort">
            <option value="all">Show All</option>    
            <?php $sneaker_demo = get_categories('taxonomy=demo_tax&type=sneakers');
              foreach ($sneaker_demo as $demo) { ?>
              <option value="<?php echo slugify($demo->slug); ?>"><?php echo $demo->name; ?></option>
            <?php } ?>
          </select>
        </fieldset>

        <fieldset>
          <label for="clear">Clear All</label>
          <button id="clearAll">Clear</button>
        </fieldset>
      </div>

      <div id="daySorting">

      </div>

      <div id="results">

      </div>

      <div id="noResults" style="display:none;">No Results!</div>


      <script type="text/javascript">

      var schedJson = jQuery.noConflict();

      function populateSchedule(){

        var sneakerBrand = schedJson( "select#brand option:selected").val();
        var sneakerType = schedJson( "select#type option:selected").val();
        var sneakerPrice = schedJson( "select#price option:selected").val();
        var sneakerDemo = schedJson( "select#demo option:selected").val();

          var specificObject = jsonAvail;

          var allShoes = [];
            schedJson.each( specificObject.posts, function( i, item ) {

              var allBrands = item.taxonomy_brand;
              var brandLength = allBrands.length;
              var betterBrands = [];
              for (var i = 0; i < brandLength; i++) {
                  betterBrands.push(allBrands[i].slug);
              }

              var allTypes = item.taxonomy_type_tax;
              var typeLength = allTypes.length;
              var betterTypes = [];
              for (var i = 0; i < typeLength; i++) {
                  betterTypes.push(allTypes[i].slug);
              }

              var allPrices = item.taxonomy_price_tax;
              var priceLength = allPrices.length;
              var betterPrices = [];
              for (var i = 0; i < priceLength; i++) {
                  betterPrices.push(allPrices[i].slug);
              }

              var allDemos = item.taxonomy_demo_tax;
              var demoLength = allDemos.length;
              var betterDemos = [];
              for (var i = 0; i < demoLength; i++) {
                  betterDemos.push(allDemos[i].slug);
              }

              var itemThumb = item.thumbnail_images;

              if(itemThumb != undefined){
                var fullURL = itemThumb.full.url;
                thumbURL = '<img src="'+ fullURL + '" alt="'+item.title+'" />';
              } else {
                thumbURL = '';
              }

              if( (schedJson.inArray(sneakerBrand, betterBrands)!==-1 || sneakerBrand == 'all') && (schedJson.inArray(sneakerType, betterTypes)!==-1 || sneakerType == 'all') && ( schedJson.inArray(sneakerPrice, betterPrices)!==-1 || sneakerPrice == 'all') && ( schedJson.inArray(sneakerDemo, betterDemos)!==-1 || sneakerDemo == 'all' ) ){

                allShoes.push('<div class="singleSneakerSort">');
                  allShoes.push('<a href="'+ item.url +'">');
                  allShoes.push('<div class="singleSneakerTitle">'+ item.title +'</div>');
                  allShoes.push('<div class="singleSneakerContent">'+ item.content +'</div>');
                  allShoes.push('<div class="singleSneakerPrice">'+ item.taxonomy_price_tax[0].title +'</div>');
                  allShoes.push('<div class="singleSneakerThumb">'+ thumbURL +'</div>');
                  allShoes.push('<div style="clear:both;"></div>');
                  allShoes.push('</a>');
                allShoes.push('</div>');
              } 
                     
          });


            schedJson('#results').empty();

            if(0 < allShoes.length){

              schedJson('<div />', {
               html: allShoes.join('')
            }).appendTo('#results');


            } else {
              schedJson('#results').html('<div class="noResults">Your search returned no results, please try again!</div>');
            } 

        }   

        var jsonAvail = {};
        schedJson.ajax({
          url: "http://local.sneakers.com/api/get_recent_posts?post_type=sneakers",
          async: false,
          dataType: 'json',
          success: function(data) {
            jsonAvail = data;
            populateSchedule();
          }
        });

      schedJson( "select" ).change( populateSchedule );

      function clearResults(){
          schedJson( "select#brand").val('all');
          schedJson( "select#type").val('all');
          schedJson( "select#price").val('all');
          schedJson( "select#demo").val('all');
          populateSchedule(); 
      }

      schedJson("#clearAll").click( clearResults );

      schedJson(".singleDaySort").click(function(){

        var clickedClass = schedJson(this).attr('id');

        schedJson(".singleSneakerSort").hide();
        schedJson("#noResults").hide();
        if (schedJson('.' + clickedClass + '')[0]){
          schedJson('.' + clickedClass + '').show();
        } else {
          schedJson("#noResults").show();
        }

        
      });

      </script>


    </div><!-- #content -->
  </section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>