$(document).ready( function () {
    $('#mainview').DataTable({
        "ordering": true,
        "searching":true,
        "paging":true,
        "aLengthMenu": [ 5, 10, 25, 50, 100 ],
        "iDisplayLength": 10,
        "scrollCollapse": true,
        "scrollX":"100%",
        "scrollY":"70vh",
        //"stripeClasses" = ["odd", "even"],
    });
} );
