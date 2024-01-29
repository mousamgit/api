$(document).ready(function(){



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
        $('#search-form').animate({right: '100px'});
        $('.header-search').hide();
        $('.header-search-close').show();
    });
    $('.header-search-close').click(function(){
        $('#search-form').animate({right: '-400px'});
        $('.header-search').show();
        $('.header-search-close').hide();
    });
});