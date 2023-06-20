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
		
			        if(selectedValue) {
			            $.ajax({
							url: '/FXmanager/admin//dependantcurrency',
							type: "POST",
							dataType: 'Json',
			                data: {'selectedValue':selectedValue},
			                success: function(data) {
								 var response = JSON.stringify(data);
								console.log(response);
								var parsedResponse = JSON.parse(response);
								var currencyValue = parsedResponse.Currency;
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