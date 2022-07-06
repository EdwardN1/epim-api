jQuery(document).ready(function ($) {
    $('.toggle-menu').click(function(e) {
        e.preventDefault();

        var $this = $(this);

        if ($this.next().hasClass('show')) {
            $this.next().removeClass('show');
            $this.next().slideUp(350);
        } else {
            $this.parent().parent().find('li .inner').removeClass('show');
            $this.parent().parent().find('li .inner').slideUp(350);
            $this.next().toggleClass('show');
            $this.next().slideToggle(350);
        }
    });

    $("input[name=et_pb_searchform_submit]").remove();
    $("input[name=et_pb_include_posts]").remove();
    $("input[name=et_pb_include_pages]").remove();
});