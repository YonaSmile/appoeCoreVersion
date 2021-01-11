/*
src="lib/js/pagination/pagination.js"

jQuery(".articlesContainer").pagination({
    items: 6,
    contents: 'articlesContainer',
    previous: '<i class="fa fa-angle-left"></i>',
    next: '<i class="fa fa-angle-right"></i>',
    position: 'bottom',
    addClassUL: 'justify-content-center xs-pagination'
});
 */
!function (e) {
    e.fn.pagination = function (a) {
        if (e("." + a.contents).length) {
            function t(t) {
                var s = e("." + r.contents + ".active").children().length, l = Math.ceil(s / r.items),
                    o = '<ul id="page-navi" class="pagination ' + r.addClassUL + '">\t<li class="page-item"><a href="#" class="previos page-link">' + r.previous + "</a></li>";
                for (i = 0; i < l; i++) o += '\t<li class="page-item"><a class="page-link" href="#">' + (i + 1) + "</a></li>";
                o += '\t<li class="page-item"><a href="#" class="next page-link">' + r.next + "</a></li></ul>";
                var c = t;
                0 == t ? (c = parseInt(e("#page-navi.pagination li.page-item a.active").html())) - 1 != 0 && c-- : t == l + 1 && (c = parseInt(e("#page-navi.pagination li.page-item a.active").html())) + 1 != l + 1 && c++, t = c, 0 == s && (o = ""), e("#page-navi.pagination").remove(), "top" == r.position ? e("." + r.contents + ".active").before(o) : e("." + r.contents + ".active").after(o), e("#page-navi.pagination li.page-item a").removeClass("active"), e("#page-navi.pagination li.page-item a").eq(t).addClass("active"), e("#page-navi li a").removeClass("disable"), c = parseInt(e("#page-navi.pagination.pagination li a.active").html()), c - 1 == 0 && e("#page-navi.pagination li.page-item a.previos").addClass("disable"), c == l && e("#page-navi.pagination li.page-item a.next").addClass("disable");
                var u = a.items * (t - 1), d = a.items * t;
                t == l && (d = s), e("." + r.contents + ".active").children().hide(), e("." + r.contents + ".active").children().slice(u, d).fadeIn(a.time), 1 == r.scroll && e("html,body").animate({scrollTop: n}, 0)

                if (r.hasOwnProperty('putInElement') && e(r.putInElement).length) {
                    e(r.putInElement).html(e("#page-navi"));
                }
            }
            var r = {
                items: 5,
                contents: "contents",
                previous: "Previous&raquo;",
                next: "&laquo;Next",
                time: 800,
                start: 1,
                position: "bottom",
                addClassUL: '',
                scroll: 1
            }, r = e.extend(r, a);
            e(this).addClass("jquery-tab-pager-tabbar"), $tab = e(this).find("li");
            var n = 0;
            !function () {
                var a = r.start - 1;
                $tab.eq(a).addClass("active"), e("." + r.contents).hide().eq(a).show().addClass("active"), t(1)
            }(), $tab.click(function () {
                var a = $tab.index(this);
                $tab.removeClass("active"), e(this).addClass("active"), e("." + r.contents).removeClass("active").hide().eq(a).addClass("active").fadeIn(r.time), t(1);
            }), e(document).on("click", "#page-navi.pagination li.page-item a", function () {
                n = e("." + r.contents).offset().top - 100;
                return !e(this).hasClass("disable") && (t(e("#page-navi.pagination li.page-item a").index(this)), !1);
            });
        }
    }
}(jQuery);