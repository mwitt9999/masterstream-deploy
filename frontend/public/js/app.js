$('#btn-deploy').on('click', function() {
    $("#btn-deploy").text("Deploying...");
    $('#btn-deploy').attr('disabled', 'disabled');

    $('#form-deployment').submit();
});
