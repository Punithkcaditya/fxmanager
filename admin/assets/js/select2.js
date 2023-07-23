$(function(e) {
	'use strict';
	$('.select2').select2()
	$("#e2").select2({
		placeholder: "Select a State",
		allowClear: true
	});
	$(document).on("select2:select", ".select2", function(e) {
		var selectedValue = e.params.data.id;
		var datacurrencyid = $(this).data("currencytype");
			        if(selectedValue && datacurrencyid) {
			            $.ajax({
							url: '/FXmanager/admin/dependantcurrency',
							type: "POST",
							dataType: 'Json',
			                data: {'selectedValue':selectedValue},
			                success: function(data) {
								$('#bank' + datacurrencyid + ' option:selected').removeAttr('selected');
								$('#currencysold' + datacurrencyid).val('');
								$('#currencybought' + datacurrencyid).val('');
								 var response = JSON.stringify(data);
								var parsedResponse = JSON.parse(response);
								var currencyValue = parsedResponse.Currency;
								var bankId = Number(parsedResponse.bank_id);
								var currencyBought, currencySold;
								if (parsedResponse.exposureType === "1") {
									currencyBought = currencyValue.substring(currencyValue.length / 2);
									currencySold = currencyValue.substring(0, currencyValue.length / 2);
								  } else {
									currencyBought = currencyValue.substring(0, currencyValue.length / 2);
									currencySold = currencyValue.substring(currencyValue.length / 2);
								  }
								  $('#currencybought'+datacurrencyid).val(currencyBought);
								  $('#currencysold'+datacurrencyid).val(currencySold);
								  $('#bank' + datacurrencyid + ' option[value='+bankId+']').attr('selected', 'selected');
								  console.log(datacurrencyid, bankId);
			                },
						error: function(xhr, status, error) {
							console.log('Error:', error);
						}
					});
			        }else{
						$('#currencybought'+datacurrencyid).val('');
						$('#currencysold'+datacurrencyid).val('');
			        }
	  });
});