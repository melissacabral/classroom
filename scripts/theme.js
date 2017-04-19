jQuery.noConflict();

jQuery( document ).ready(function( $ ) {
    checkSize();
    $(window).resize(checkSize);

    var $menubutton = $( '<div class="menu-toggle"><span class="button">Menu</span></div>' );
    var $navigation = $( ".main-navigation" );

    //display menu button
    $navigation.prepend( $menubutton );

    $menubutton.click(function(){
        $navigation.toggleClass('menu-closed');
    });


    //sticky sidebar menu
    var $sticky = $('.main-navigation .menu');


    // measure how far down the sticky element is from the top
    var stickyTop = $sticky.offset().top;
    console.log(stickyTop);


    //whenever the user scrolls, measure how far we have scrolled
    $(window).scroll(function() {
        var windowTop = $(window).scrollTop();


    //check to see if we have scrolled past the original location of the sticky element
    if (windowTop > stickyTop ) {

    //if so, change the sticky element to fised positioning
    $sticky.addClass('sticky-menu');
    } else {
        $sticky.removeClass('sticky-menu');
    }
});

//View Fullscreen button
$('.fullscreen-button').click(function(){
    var el = $(this);
    if( el.text() == 'View Fullscreen' ){
        el.text('Close Fullscreen');
    }else{
        el.text('View Fullscreen');
    }
    $('body').toggleClass('fullscreen');
    el.toggleClass('close');
});

});

function checkSize(){
    //if the screen is really small (if description is hidden with CSS)
    if (jQuery(".site-header h2").css("display") == "none" ){
        console.log('small');
        jQuery('body').addClass('small-screen');

        jQuery( ".main-navigation" ).addClass('menu-closed');
    }
    else{
        console.log('large');
        jQuery('body').removeClass('small-screen');
        jQuery( ".main-navigation" ).removeClass('menu-closed');
    }
}