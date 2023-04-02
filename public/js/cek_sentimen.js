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

$("#btnsubmitcek").on('click', function () {
    $('#hasil-crawling').html("");
    loader_on();
    // Get Data
    var kalimat = $('#kalimat').val();
    if (kalimat != "") {
        $.ajax({
            type: 'POST',
            url: 'cek_sentimen',
            data: {
                '_token': $('meta[name="crawling"]').attr('content'),
                'kalimat':kalimat
            },
            success: function (data) {
                loader_off();
                // alert(data.msg);
                $('#hasil-sentimen').html(data.msg);
            }
        });
    }
    else {
        loader_off();
        alert("Query Tidak Boleh Kosong");
    }
})