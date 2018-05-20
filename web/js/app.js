$(document).ready(function () {
    $('#sync_affiliates_grid').click(function () {
        showLoader();
        $.get( "create", function( data ) {
            $.pjax.reload({container:'#p0'});
            hideLoader();
        });
    });
    
    $('.select-on-check-all').change(function () {
        addContactToCrm();
    });

    $('.affiliate-checkbox').change(function () {
        addContactToCrm();
    });
    
    function addContactToCrm() {
        var selectedCheckbox = $('.affiliate-checkbox:checkbox:checked');
        if(selectedCheckbox.length > 0){
            $('#update_crm').prop("disabled", false);
        } else {
            $('#update_crm').prop("disabled", true);
        }
    }

    $('#update_crm').click(function () {
        showLoader();
        var selectedCheckbox = $('.affiliate-checkbox:checkbox:checked');
        $.post('add-to-crm', selectedCheckbox, function (data, status) {
            $.pjax.reload({container:'#p0'});
        });
        hideLoader();
    })

    function showLoader(){
        $.LoadingOverlay("show");
    }
    
    function hideLoader() {
        $.LoadingOverlay("hide");
    }
});