/* ==========================================================================
 Scripts voor de frontend
 ========================================================================== */
require(['jquery'], function ($) {
    $(function () {
        $('h4 .expand').on('click', function () {
            $(this).removeClass('expand').addClass('expanded');
            $(this).parents('.root').find('.o-list:first').toggleClass('active');
        });
        $('h4 .expanded').on('click', function () {
            $(this).removeClass('expand').addClass('expanded');
            $(this).parents('.root').find('.o-list').removeClass('active');
        });
        $('.o-list li .expand').on('click', function () {
            $(this).removeClass('expand').addClass('expanded');
            $(this).parents('li').next('.o-list').toggleClass('active');
        });
        $('.o-list li .expanded').on('click', function () {
            $(this).removeClass('expanded').addClass('expand');
            $(this).parents('li').next('.o-list').toggleClass('active');
        });
    });
});