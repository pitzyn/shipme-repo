
var closeNav = function()
{
        jQuery("body").removeClass("shp-opened-sidebar"), jQuery("body").removeClass("shp-body-fixed")
    },
    openNav = function() {
        jQuery(window).width() < 760 && jQuery("body").addClass("shp-body-fixed"), jQuery("body").addClass("shp-opened-sidebar")
    },
    addWaveEffect = function(e, a) {
        var s = ".shp-wave-effect",
            n = e.outerWidth(),
            t = a.offsetX,
            i = a.offsetY;
        e.prepend('<span class="shp-wave-effect"></span>'), jQuery(s).css({
            top: i,
            left: t
        }).animate({
            opacity: "0",
            width: 2 * n,
            height: 2 * n
        }, 500, function() {
            e.find(s).remove()
        })
    };

jQuery(document).ready(function()
{
    jQuery(".shp-header-submenu").show(), jQuery(".shp-overlay, .shp-sidebar-toggle-button").on("click", function() {
        closeNav()
    }), jQuery(".shp-toggle-sidebar").on("click", function() {
        jQuery("body").hasClass("shp-opened-sidebar") ? closeNav() : openNav()
    }), jQuery(".shp-sidebar-pin-button").on("click", function() {
        jQuery("body").toggleClass("shp-pinned-sidebar")
    }), jQuery(".shp-search-toggle").on("click", function(e) {
        e.preventDefault(), jQuery(".shp-search-bar").toggleClass("active")
    }), jQuery(".shp-header-submenu").parent().find("a:first").on("click", function(e) {
        e.stopPropagation(), e.preventDefault(), jQuery(this).parents(".shp-header-navigation").find(".shp-header-submenu").not(jQuery(this).parents("li").find(".shp-header-submenu")).removeClass("active"), jQuery(this).parents("li").find(".shp-header-submenu").show().toggleClass("active")
    }), jQuery(".shp-sidebar-navi li.show > ul").slideDown(200), jQuery(".shp-sidebar-navi a").on("click", function(e) {
        var a = jQuery(this);
        jQuery(this).next().is("ul") ? (e.preventDefault(), a.parent().hasClass("show") ? (a.parent().removeClass("show"), a.next().slideUp(200)) : (a.parent().parent().find(".show ul").slideUp(200), a.parent().parent().find("li").removeClass("show"), a.parent().toggleClass("show"), a.next().slideToggle(200))) : (a.parent().parent().find(".show ul").slideUp(200), a.parent().parent().find("li").removeClass("show"), a.parent().addClass("show"))
    }), jQuery(".shp-material-button").on("click", function(e) {
        addWaveEffect(jQuery(this), e)
    }), jQuery(document).on("click", function(e) {
        var a = jQuery(e.target);
        !0 === jQuery(".shp-header-submenu").hasClass("active") && !a.hasClass("shp-submenu-toggle") && a.parents(".shp-header-submenu").length < 1 && jQuery(".shp-header-submenu").removeClass("active"), a.parents(".shp-search-bar").length < 1 && !a.hasClass("shp-search-bar") && !a.parent().hasClass("shp-search-toggle") && !a.hasClass("shp-search-toggle") && jQuery(".shp-search-bar").removeClass("active")
    })
});
