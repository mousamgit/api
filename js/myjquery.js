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

    //select all boxes
    $('.selectall').click(function(){
        if($(this).prop("checked")){
            $(this).closest('.checkboxes-container').find('input[type="checkbox"]').each(function(){
                $(this).prop("checked", true);
            });
        }
        else{
            $(this).closest('.checkboxes-container').find('input[type="checkbox"]').each(function(){
                $(this).prop("checked", false);
            });
        }
 
    });



    
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
$(window).on("load", function() {
    $('.no-spin-container').each(function(){
        $(this).closest('.imgcontainer').find('.spinicon').hide();
    });
    //fix table width
    if($('.pimtable').length>0){
        $('.pimtable th').each(function(index) {
            var maxColumnWidth = 0;
        
            $('.pimtable tbody tr').each(function() {
              var cellContentWidth = $(this).find('td').eq(index).text().length * 10; // Adjust the multiplier as needed
              maxColumnWidth = Math.max(maxColumnWidth, cellContentWidth);
              console.log(maxColumnWidth+','+cellContentWidth);
            });
        
            $('.pimtable td').filter(function() {
              return $(this).index() === index;
            }).css('width', maxColumnWidth + 'px');
          });
    }
});