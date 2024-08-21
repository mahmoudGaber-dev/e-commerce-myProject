$(function () {
    'use strict';



    //switch between login and signup

    $('.login-page  h1 span').click(function () {

        $(this).addClass('selected').siblings().removeClass('selected');

        $('.login-page form').hide();

        $( '.' + $(this).data('class')).fadeIn(100) ;

    });


    //Trigger The Selectboxit

    $("select").selectBoxIt({

        autoWidth: false


    });

    //hide placeholder on form focus

    $('[placeholder]').focus(function () {

        $(this).attr('data-text' ,$(this).attr('placeholder'));

        $(this).attr('placeholder', '');
        
    }).blur(function () {
        
        $(this).attr('placeholder',$(this).attr('data-text'));


    });

    //add asterik on required field
    $('input').each(function () {
        if ($(this).attr('required') === 'required'){
            $(this).after('<span class="asterisk">*</span>');
        }
    } );

    
    //convert password field to text field on hover

    var passfield = $('.password');

    $('.show-pass').hover(function () {

        passfield.attr('type' , 'text');

    }, function() {

        passfield.attr('type' , 'password');

    });

    //confirmation message on button

    $('.confirm').click(function () {

        return confirm('Are you sure ?');
    });


    //write text in newad page in any class = live of ad
/*
    $('.live-').keyup(function ()  {

        $($(this).data('class')).text($(this).val());

    });

*/
$('.live-name').keyup(function () {
    $('.live-preview .caption h3').text($(this).val());
});

$('.live-desc').keyup(function () {
    $('.live-preview .caption p').text($(this).val());
});

$('.live-price').keyup(function () {
    $('.live-preview .price-tag').text('$' + $(this).val());
});



});