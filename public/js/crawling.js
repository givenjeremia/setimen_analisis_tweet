$('#loader').hide();

var spinner = $('#loader');
function loader_on() {
    // Set Loading
    $("#overlay").show();

    spinner.addClass('d-flex');
    spinner.show();
}
function loader_off() {
    spinner.removeClass('d-flex');
    spinner.hide();
    $("#overlay").hide();
}

$("#btnsubmit").on('click', function () {
    $('#hasil-crawling').html("");
    loader_on();
    // Get Data
    var query = $('#query').val();
    var count = $('#count').val();
    var sebelum = $('#sebelum').val();
    var sesudah = $('#sesudah').val();

    if (query != "") {
        $.ajax({
            type: 'POST',
            url: 'crawling',
            data: {
                '_token': $('meta[name="crawling"]').attr('content'),
                'query': query,
                'count': count,
                'sebelum': sebelum,
                'sesudah': sesudah,
            },
            success: function (data) {
                loader_off();
                // alert(data.msg);
                $('#hasil-crawling').html(data.msg);
                $('#hasil-data').DataTable();
            }
        });
    }
    else {
        loader_off();
        alert("Query Tidak Boleh Kosong");
    }
})