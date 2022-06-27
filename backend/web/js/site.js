(function($) {

    $(function () {
        $(document.body).on("click", ".js-modal-load", function () {

            const url = $(this).data("modal-url");
            const target = $(this).data("target");

            $(target + " .modal-body").load(url, function (response, status, xhr) {
                console.log(response);
                if (status == "error") {
                    console.log("Somthing went wrong..");
                    return false;
                }
            });
        });
    });
})(jQuery);