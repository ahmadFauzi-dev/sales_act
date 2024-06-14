</div> <!-- ion-auth -->
<script>
	$(document).ready(function() {
    var ion = $("#ion-auth");

    var cssTable ='table compact table-bordered table-striped dataTable no-footer dtr-inline';
    var obj = ion.find($('table'));
	  obj.removeClass(cssTable);
    obj.addClass(cssTable);

    var cssError = 'koje-error no-padding';
    var obj = ion.find($('#infoMessage'));
    obj.removeClass(cssError);
    obj.addClass(cssError);

    var cssGroup = 'form-group is-empty';
    var obj = ion.find($('p'));
    obj.removeClass(cssGroup);
    obj.addClass(cssGroup);

    var cssLabel = 'control-label no-padding';
    var obj = ion.find($('label'));
    obj.removeClass(cssLabel);
    obj.removeClass('checkbox');
    obj.addClass(cssLabel);

    var cssInput = 'form-control';
    var obj = ion.find($('input'));
    obj.removeClass(cssInput);
    obj.addClass(cssInput);

    var cssCheckbox = 'check-control';
    var obj = ion.find($('input[type=checkbox]'));
    obj.removeClass(cssInput);
    obj.removeClass(cssCheckbox);
    obj.addClass(cssCheckbox);

    var cssRadio = 'check-control';
    var obj = ion.find($('input[type=radio]'));
    obj.removeClass(cssInput);
    obj.removeClass(cssRadio);
    obj.addClass(cssRadio);

    var cssButton = 'btn btn-raised btn-primary btn-sm btn-round';
    var obj = ion.find($('input[type=submit'));
    obj.removeClass(cssInput);
    obj.removeClass(cssButton);
    obj.addClass(cssButton);
    $('<a class="btn btn-raised btn-primary btn-sm btn-round" href="'+baseURL+'_system_/auth">Cancel</a>').insertAfter(obj);
    $('<span>&nbsp;</span>').insertAfter(obj);
	});
</script>
