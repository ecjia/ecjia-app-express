// JavaScript Document
;(function (app, $) {
    app.merchant_list = {
        init: function () {
            $("form[name='searchForm'] .search_merchant").on('click', function (e) {
                e.preventDefault();
                var url = $("form[name='searchForm']").attr('action');
                var keyword = $("input[name='keyword']").val();
                if (keyword != '') {
                	url += '&keyword=' + keyword;
                }
                ecjia.pjax(url);
            });
        },
    }
})(ecjia.admin, jQuery);
 
// end