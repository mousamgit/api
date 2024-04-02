$(document).ready(function(){

    spinsrc = '';

    var $document = $(document),
        $header = {
            drawerToggle : $('.header-drawer-toggle')
        },
        $drawer = {
            this : $('.layout-drawer'),
            dropdownToggle : $('.drawer-dropdown-toggle')
        }
    // Button which opens the drawer on click.
    $header.drawerToggle.click(function() {
        $drawer.this.toggleClass('is-open');
    });
    // When the user press the 'escape' key (the back
    // button in Android) will close the drawer.
    $document.keyup(function(e) {
        if (e.keyCode == 27) {
            if ($drawer.this.hasClass('is-open')) {
                $drawer.this.removeClass('is-open');
            }
        }
    });
    // When the user clicks or touches area outside
    // the drawer will close it as well.
    $document.mouseup(function(e) {
        if(!$drawer.this.is(e.target) &&
           $drawer.this.has(e.target).length === 0)
        {
            if ($drawer.this.hasClass('is-open')) {
                $drawer.this.removeClass('is-open');
            }
        }
    });
    // Using jQuery slideToggle() method as a dropdown
    // approach to show and hide sub-navigations.
    $drawer.dropdownToggle.each(function() {
        var target = $(this).data('target');
        $(this).click(function() {
            $(target).slideToggle(300);
        });
    });

    //search form slide in
    $('.header-search').click(function(){
        $('#search-form').show();
        $('.header-search').hide();
        $('.header-search-close').show();
    });
    $('.header-search-close').click(function(){
        $('#search-form').hide();
        $('.header-search').show();
        $('.header-search-close').hide();
    });

    //show 360 spin
    if($('#sirv360').length>0){
        brand = $('#sirv360').attr('brand').toLowerCase();
        sku = $('#sirv360').attr('sku');
    
        if(brand == 'pink kimberley' || brand == 'pink kimberley diamonds' || brand == 'argyle pink diamonds' ){
            spinsrc = "https://samsgroup.sirv.com/products/" + sku + "/" + sku + ".spin";
          }
          if(brand == 'sapphire dreams' || brand == 'loose sapphires'){
            spinsrc = "https://samsgroup.sirv.com/SD-Product/Sapphire%20Dreams%20Products/" + sku + "/" + sku + ".spin";
          }       
          $.ajax( spinsrc, {
            statusCode: {
              404: function() {
    
                },
              200: function() {
                  $('.sirv-container').append('<div class="Sirv" id="sirv-spin" data-src="'+spinsrc+'"></div>');
                  $('.showing-noimg').hide();
                  $('#sirv360').removeClass('d-none');
              }
            }
          });
    }
    
      if($('.home-table-container').length>0){
        var tableheight = window.innerHeight - 270;
        // var containerTop = $('.home-table-container').getBoundingClientRect().top;
        console.log('tableheight'+tableheight);
        $('.home-table-container').css('max-height',tableheight+'px');
      }
      
      //tabs
      $('.tab').on('click', function() {
        var tabId = $(this).attr('nav');
        // Remove active class from all tabs and tab panes
        $(this).parent().children('.tab').removeClass('active');
        $(this).parent().next().children('.tab-pane').removeClass('active');
        // $('.tab-pane').removeClass('active');
        
        // Add active class to the clicked tab and corresponding tab pane
        $(this).addClass('active');
        $(this).parent().next().children('.tab-pane[tab="'+tabId+'"]').addClass('active');
        // $('#' + tabId).addClass('active');
      });


});