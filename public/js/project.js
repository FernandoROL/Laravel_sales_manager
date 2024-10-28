async function deletePagingRegistry(routeUrl, registryID) {
    if (confirm('Confirm deletion')) {
        $.ajax({
            url: routeUrl,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                id: registryID,
            },
            beforeSend: function () {
                $.blockUI({
                    message: 'Loading...',
                    timeout: 4000,
                });
            },
        }).done(function (data) {
            $.unblockUI();
            if (data.success) {
                window.location.reload();
            } else {
                alert('Could not delete data')
            }
        }).fail(function (data) {
            $.unblockUI();
            alert('Could not start the search');
        });
    }
}

$('#money').mask('#.##0,00', { reverse: true });

$("#zip").blur(function () {
    var zip = $(this).val().replace(/\D/g, '');
    if (zip != "") {
        var validadezip = /^[0-9]{8}$/;
        if (validadezip.test(zip)) {
            $("#road").val("");
            $("#neighborhood").val(" ");
            $("#adress").val(" ");
            $.getJSON("https://viacep.com.br/ws/" + zip + "/json/?callback=?", function (data) {
                if (!("erro" in data)) {
                    $("#road").val(data.logradouro.toUpperCase());
                    $("#neighborhood").val(data.bairro.toUpperCase());
                    $("#adress").val(data.localidade.toUpperCase());
                }
                else {
                    alert("Could not find zip code. Try again or ender data manually.");
                }
            });
        }
    }
});
