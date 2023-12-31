$(document).ready(function(){
	$('body a, body button').attr('tabindex', -1);
	check_add_item_val();
	$(document).on('keypress', '.rquantity', function (e) {
	    if (e.keyCode == 13) {
	        $('#add_item').focus();
	    }
	});
	$('#toogle-customer-read-attr').click(function () {
		var nst = $('#poscustomer').is('[readonly]') ? false : true;
		$('#poscustomer').select2("readonly", nst);
		return false;
	});
	$(".open-brands").click(function () {
		$('#brands-slider').toggle('slide', { direction: 'right' }, 700);
	});
	$(".open-category").click(function () {
		$('#category-slider').toggle('slide', { direction: 'right' }, 700);
	});
	$(".open-subcategory").click(function () {
		$('#subcategory-slider').toggle('slide', { direction: 'right' }, 700);
	});
	$(document).on('click', function(e){
		if (!$(e.target).is(".open-brands, .cat-child") && !$(e.target).parents("#brands-slider").size() && $('#brands-slider').is(':visible')) {
			$('#brands-slider').toggle('slide', { direction: 'right' }, 700);
		}
		if (!$(e.target).is(".open-category, .cat-child") && !$(e.target).parents("#category-slider").size() && $('#category-slider').is(':visible')) {
			$('#category-slider').toggle('slide', { direction: 'right' }, 700);
		}
		if (!$(e.target).is(".open-subcategory, .cat-child") && !$(e.target).parents("#subcategory-slider").size() && $('#subcategory-slider').is(':visible')) {
			$('#subcategory-slider').toggle('slide', { direction: 'right' }, 700);
		}
	});
	$('.po').popover({html: true, placement: 'right', trigger: 'click'}).popover();
	$('#inlineCalc').calculator({layout: ['_%+-CABS','_7_8_9_/','_4_5_6_*','_1_2_3_-','_0_._=_+'], showFormula:true});
	$('.calc').click(function(e) { e.stopPropagation();});
	$(document).on('click', '[data-toggle="ajax"]', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        $.get(href, function( data ) {
            $("#myModal").html(data).modal();
        });
    });
    $(document).on('click', '.sname', function(e) {
        var row = $(this).closest('tr');
        var itemid = row.find('.rid').val();
        $('#myModal').modal({remote: site.base_url + 'products/modal_view/' + itemid});
        $('#myModal').modal('show');
    });
    
    $('#active_tips').click(function () {
        
        var active = $('#active_tips').prop("checked");
        $.get('pos/ajax/update_tip/' + active);
    });
    
    $('#toogle-tables').tooltip();
    
    $('#postable').on('change', function () {
        
        var text = $('#postable option:selected').text();
        var table = $('#postable').val();
        
        if(text.indexOf('(') != -1){
            $.ajax({
                type: "get",
                url: 'pos/ajax/load_table_info/'+table,
                success: function (data) {
                    if (data){
                        window.location.href = data;
                    }
                }
            });
        } else{
            
            $.ajax({
                type: "get",
                url: 'pos/ajax/charge_info_localstorage/'+table,
                dataType: "json",
                success: function (data) {
                    //clear storage without table
                    if (localStorage.getItem('positems')) {
                            localStorage.removeItem('positems');
                    }
                    if (localStorage.getItem('posdiscount')) {
                            localStorage.removeItem('posdiscount');
                    }
                    if (localStorage.getItem('postax2')) {
                            localStorage.removeItem('postax2');
                    }
                    if (localStorage.getItem('order_tip')) {
                            localStorage.removeItem('order_tip');
                    }
                    if (localStorage.getItem('posshipping')) {
                            localStorage.removeItem('posshipping');
                    }
                    if (localStorage.getItem('posref')) {
                            localStorage.removeItem('posref');
                    }
                    if (localStorage.getItem('posnote')) {
                            localStorage.removeItem('posnote');
                    }
                    if (localStorage.getItem('posinnote')) {
                            localStorage.removeItem('posinnote');
                    }
                    if (localStorage.getItem('poscurrency')) {
                            localStorage.removeItem('poscurrency');
                    }
                    if (localStorage.getItem('posdate')) {
                            localStorage.removeItem('posdate');
                    }
                    if (localStorage.getItem('posstatus')) {
                            localStorage.removeItem('posstatus');
                    }
            
                    if (data){
                        localStorage.setItem('poswarehouse', data.warehouse );
                        localStorage.setItem('poscustomer', data.customer );
                        localStorage.setItem('poslangcustomer', data.lang_customer );
                        localStorage.setItem('posbiller', data.biller_id );
                        localStorage.setItem('postable', table );
                        localStorage.setItem('postablename', data.table_name );
                        localStorage.setItem('postablelang', data.table_lang );
                        localStorage.setItem('posbilltitle', data.bill_title );
                        localStorage.setItem('posmessagebill', data.message_bill );
                        localStorage.setItem('poswaiter', data.waiter_name );
                        localStorage.setItem('poslangwaiter', data.lang_waiter_name );
                        localStorage.setItem('posinfobiller', data.biller );
                        localStorage.setItem('posbillertel', data.biller_tel );
                        localStorage.setItem('poslogo', data.biller_logo );
                        
                        $('#modal-loading').show();

                        window.location.href = site.base_url + 'pos';
                    }
                }
            });
        }
    });
    
    $('#toogle-tables').click(function () {
        
        var status = false;
        
        if($('#toogle-tables').hasClass( "fa-toggle-on" )){
            $( "#toogle-tables" ).removeClass( "fa-toggle-on" ).addClass( "fa-toggle-off" );
        }else{
            status = true;
            $( "#toogle-tables" ).removeClass( "fa-toggle-off" ).addClass( "fa-toggle-on" );
        }
        
        $.ajax({
            type: "get",
            url: 'pos/ajax/change_table_info/'+status,
            dataType: "json",
            success: function (data) {
                if (data){
                    $('#postable option').remove();
                    $.each(data, function(key,value) {
                        $('#postable').append($("<option></option>")
                           .attr("value", key).text(value));
                    });
                }
            }
        });
    });
});
$(document).ready(function () {

// Order level shipping and discoutn localStorage
if (posdiscount = localStorage.getItem('posdiscount')) {
	$('#posdiscount').val(posdiscount);
}
$(document).on('change', '#ppostax2', function () {
	localStorage.setItem('postax2', $(this).val());
	$('#postax2').val($(this).val());
});

if (postax2 = localStorage.getItem('postax2')) {
	$('#postax2').val(postax2);
}

if (postip = localStorage.getItem('order_tip')) {
	$('#postip').val(postip);
}

$(document).on('blur', '#sale_note', function () {
	localStorage.setItem('posnote', $(this).val());
	$('#sale_note').val($(this).val());
});

if (posnote = localStorage.getItem('posnote')) {
	$('#sale_note').val(posnote);
}

$(document).on('blur', '#staffnote', function () {
	localStorage.setItem('staffnote', $(this).val());
	$('#staffnote').val($(this).val());
});

if (staffnote = localStorage.getItem('staffnote')) {
	$('#staffnote').val(staffnote);
}


/* ----------------------
	 * Order Discount Handler
	 * ---------------------- */
	 $("#ppdiscount").click(function(e) {
	 	e.preventDefault();
	 	var dval = $('#posdiscount').val() ? $('#posdiscount').val() : '0';
	 	$('#order_discount_input').val(dval);
	 	$('#dsModal').modal();
	 });
	 $('#dsModal').on('shown.bs.modal', function() {
	 	$(this).find('#order_discount_input').select().focus();
	 	$('#order_discount_input').bind('keypress', function(e) {
	 		if (e.keyCode == 13) {
	 			e.preventDefault();
	 			var ds = $('#order_discount_input').val();
	 			if (is_valid_discount(ds)) {
	 				$('#posdiscount').val(ds);
	 				localStorage.removeItem('posdiscount');
	 				localStorage.setItem('posdiscount', ds);
	 				loadItems();
	 			} else {
	 				bootbox.alert(lang.unexpected_value);
	 			}
	 			$('#dsModal').modal('hide');
	 		}
	 	});
	 });
	 $(document).on('click', '#updateOrderDiscount', function() {
	 	var ds = $('#order_discount_input').val() ? $('#order_discount_input').val() : '0';
	 	if (is_valid_discount(ds)) {
	 		$('#posdiscount').val(ds);
	 		localStorage.removeItem('posdiscount');
	 		localStorage.setItem('posdiscount', ds);
	 		loadItems();
	 	} else {
	 		bootbox.alert(lang.unexpected_value);
	 	}
	 	$('#dsModal').modal('hide');
	 });
/* ----------------------
	 * Order Tax Handler
	 * ---------------------- */
	$('#order_tax_input').change(function () {
		var tax_selected = $('#order_tax_input option:selected').text();

		if (tax_selected == "Fijo"){
			$("#container-fixed-tax").fadeIn('slow').removeClass('hide');
		} else {
			if(!$('#container-fixed-tax').hasClass('hide')){
				$('#container-fixed-tax').addClass('hide');
			}
		}
	});
	 $("#pptax2").click(function(e) {
	 	e.preventDefault();
	 	var postax2 = localStorage.getItem('postax2');
	 	$('#order_tax_input').select2('val', postax2);

		 var tax_selected = $('#order_tax_input option:selected').text();

		 if (tax_selected == "Fijo"){
			 $("#container-fixed-tax").fadeIn('slow').removeClass('hide');
		 } else {
			 if(!$('#container-fixed-tax').hasClass('hide')){
				 $('#container-fixed-tax').addClass('hide');
			 }
		 }

	 	$('#txModal').modal();
	 });
	 $('#txModal').on('shown.bs.modal', function() {
	 	$(this).find('#order_tax_input').select2('focus');
	 });
	 $('#txModal').on('hidden.bs.modal', function() {
	 	var ts = $('#order_tax_input').val();
	 	$('#postax2').val(ts);
	 	localStorage.setItem('postax2', ts);
	 	loadItems();
	 });
	$(document).on('click', '#updateOrderTax', function () {

		var tax_selected = $('#order_tax_input option:selected').text();
		var fixed_value = $('#fixed_tax').val();

		if (tax_selected == "Fijo") {
			$.ajax({
				type: "get",
				url: 'pos/ajax/update_tax/' + fixed_value,
				dataType: "json",
				success: function (data) {
					if (data) {
						var ts = $('#order_tax_input').val();

						$('#postax2').val(ts);
						localStorage.setItem('postax2', ts);
						loadItems();
						$('#txModal').modal('hide');
					}
				}
			});
		} else {

			var ts = $('#order_tax_input').val();
			$('#postax2').val(ts);
			localStorage.setItem('postax2', ts);
			loadItems();
			$('#txModal').modal('hide');
		}

	});
        
        
        /* ----------------------
	 * Order Tip Handler
	 * ---------------------- */
	$('#order_tip_input').change(function () {
		var tip_selected = $('#order_tip_input option:selected').text();

		if (tip_selected == "Fijo"){
			$("#container-fixed-tip").fadeIn('slow').removeClass('hide');
		} else {
			if(!$('#container-fixed-tip').hasClass('hide')){
				$('#container-fixed-tip').addClass('hide');
			}
		}
	});
	 $("#order_tip").click(function(e) {
	 	e.preventDefault();
	 	var postip = localStorage.getItem('order_tip');
	 	$('#order_tip_input').select2('val', postip);

		 var tip_selected = $('#order_tip_input option:selected').text();

		 if (tip_selected == "Fijo"){
			 $("#container-fixed-tip").fadeIn('slow').removeClass('hide');
		 } else {
			 if(!$('#container-fixed-tip').hasClass('hide')){
				 $('#container-fixed-tip').addClass('hide');
			 }
		 }

	 	$('#tipModal').modal();
	 });
	 $('#tipModal').on('shown.bs.modal', function() {
	 	$(this).find('#order_tip_input').select2('focus');
	 });
	 $('#tipModal').on('hidden.bs.modal', function() {
	 	var ts = $('#order_tip_input').val();
	 	$('#postip').val(ts);
	 	localStorage.setItem('order_tip', ts);
	 	loadItems();
	 });
	$(document).on('click', '#updateOrderTip', function () {

		var tip_selected = $('#order_tip_input option:selected').text();
		var fixed_value = $('#fixed_tip').val();

		if (tip_selected == "Fijo") {
			$.ajax({
				type: "get",
				url: 'pos/ajax/update_order_tip/' + fixed_value,
				dataType: "json",
				success: function (data) {
					if (data) {
						var ts = $('#order_tip_input').val();

						$('#order_tip').val(ts);
						localStorage.setItem('order_tip', ts);
						loadItems();
						$('#tipModal').modal('hide');
					}
				}
			});
		} else {

			var ts = $('#order_tip_input').val();
			$('#order_tip').val(ts);
			localStorage.setItem('order_tip', ts);
			loadItems();
			$('#tipModal').modal('hide');
		}

	});


	 $(document).on('change', '.rserial', function () {
	 	var item_id = $(this).closest('tr').attr('data-item-id');
	 	positems[item_id].row.serial = $(this).val();
	 	localStorage.setItem('positems', JSON.stringify(positems));
	 });

// If there is any item in localStorage
if (localStorage.getItem('positems')) {
	loadItems();
}

	// clear localStorage and reload
	$('#reset').click(function (e) {
		bootbox.confirm(lang.r_u_sure, function (result) {
			if (result) {
				if (localStorage.getItem('positems')) {
					localStorage.removeItem('positems');
				}
				if (localStorage.getItem('posdiscount')) {
					localStorage.removeItem('posdiscount');
				}
				if (localStorage.getItem('postax2')) {
					localStorage.removeItem('postax2');
				}
                                if (localStorage.getItem('order_tip')) {
					localStorage.removeItem('order_tip');
				}
				if (localStorage.getItem('posshipping')) {
					localStorage.removeItem('posshipping');
				}
				if (localStorage.getItem('posref')) {
					localStorage.removeItem('posref');
				}
				if (localStorage.getItem('poswarehouse')) {
					localStorage.removeItem('poswarehouse');
				}
                                if (localStorage.getItem('postable')) {
					localStorage.removeItem('postable');
				}
				if (localStorage.getItem('posnote')) {
					localStorage.removeItem('posnote');
				}
				if (localStorage.getItem('posinnote')) {
					localStorage.removeItem('posinnote');
				}
				if (localStorage.getItem('poscustomer')) {
					localStorage.removeItem('poscustomer');
				}
				if (localStorage.getItem('poscurrency')) {
					localStorage.removeItem('poscurrency');
				}
				if (localStorage.getItem('posdate')) {
					localStorage.removeItem('posdate');
				}
				if (localStorage.getItem('posstatus')) {
					localStorage.removeItem('posstatus');
				}
				if (localStorage.getItem('posbiller')) {
					localStorage.removeItem('posbiller');
				}

				$('#modal-loading').show();
				//location.reload();
				window.location.href = site.base_url+"pos";
			}
		});
});

// save and load the fields in and/or from localStorage

$('#poswarehouse').change(function (e) {
	localStorage.setItem('poswarehouse', $(this).val());
});
if (poswarehouse = localStorage.getItem('poswarehouse')) {
	$('#poswarehouse').select2('val', poswarehouse);
}

	//$(document).on('change', '#posnote', function (e) {
		$('#posnote').redactor('destroy');
		$('#posnote').redactor({
			buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
			formattingTags: ['p', 'pre', 'h3', 'h4'],
			minHeight: 100,
			changeCallback: function (e) {
				var v = this.get();
				localStorage.setItem('posnote', v);
			}
		});
		if (posnote = localStorage.getItem('posnote')) {
			$('#posnote').redactor('set', posnote);
		}

		$('#poscustomer').change(function (e) {
			localStorage.setItem('poscustomer', $(this).val());
		});


// prevent default action upon enter
$('body').not('textarea').bind('keypress', function (e) {
	if (e.keyCode == 13) {
		e.preventDefault();
		return false;
	}
});

// Order tax calculation
if (site.settings.tax2 != 0) {
	$('#postax2').change(function () {
		localStorage.setItem('postax2', $(this).val());
		loadItems();
		return;
	});
}

// Order tip calculation
if (site.settings.tip != 0) {
	$('#postip').change(function () {
		localStorage.setItem('order_tip', $(this).val());
		loadItems();
		return;
	});
}

// Order discount calculation
var old_posdiscount;
$('#posdiscount').focus(function () {
	old_posdiscount = $(this).val();
}).change(function () {
	var new_discount = $(this).val() ? $(this).val() : '0';
	if (is_valid_discount(new_discount)) {
		localStorage.removeItem('posdiscount');
		localStorage.setItem('posdiscount', new_discount);
		loadItems();
		return;
	} else {
		$(this).val(old_posdiscount);
		bootbox.alert(lang.unexpected_value);
		return;
	}

});

	/* ----------------------
	 * Delete Row Method
	 * ---------------------- */
	 var pwacc = false;
	 $(document).on('click', '.posdel', function () {
	 	var row = $(this).closest('tr');
	 	var item_id = row.attr('data-item-id');
	 	if(protect_delete == 1) {
	 		var boxd = bootbox.dialog({
	 			title: "<i class='fa fa-key'></i> Pin Code",
	 			message: '<input id="pos_pin" name="pos_pin" type="password" placeholder="Pin Code" class="form-control"> ',
	 			buttons: {
	 				success: {
	 					label: "<i class='fa fa-tick'></i> OK",
	 					className: "btn-success verify_pin",
	 					callback: function () {
	 						var pos_pin = md5($('#pos_pin').val());
	 						if(pos_pin == pos_settings.pin_code) {
	 							delete positems[item_id];
	 							row.remove();
	 							if(positems.hasOwnProperty(item_id)) { } else {
	 								localStorage.setItem('positems', JSON.stringify(positems));
	 								loadItems();
	 							}
	 						} else {
	 							bootbox.alert('Wrong Pin Code');
	 						}
	 					}
	 				}
	 			}
	 		});
	 		boxd.on("shown.bs.modal", function() {
	 			$( "#pos_pin" ).focus().keypress(function(e) {
	 				if (e.keyCode == 13) {
	 					e.preventDefault();
	 					$('.verify_pin').trigger('click');
	 					return false;
	 				}
	 			});
	 		});
	 	} else {
	 		delete positems[item_id];
	 		row.remove();
	 		if(positems.hasOwnProperty(item_id)) { } else {
	 			localStorage.setItem('positems', JSON.stringify(positems));
	 			loadItems();
	 		}
	 	}
	 	return false;
	 });

	/* -----------------------
	 * Edit Row Modal Hanlder
	 ----------------------- */
	 $(document).on('click', '.edit', function () {
		var row = $(this).closest('tr');
		var row_id = row.attr('id');
		item_id = row.attr('data-item-id');
		item = positems[item_id];
		var qty = row.children().children('.rquantity').val(),
		product_option = row.children().children('.roption').val(),
		unit_price = formatDecimal(row.children().children('.ruprice').val()),
		discount = row.children().children('.rdiscount').val();
		if(item.options !== false) {
			$.each(item.options, function () {
				if(this.id == item.row.option && this.price != 0 && this.price != '' && this.price != null) {
					unit_price = parseFloat(item.row.real_unit_price)+parseFloat(this.price);
				}
			});
		}
		var real_unit_price = item.row.real_unit_price;
		var net_price = real_unit_price;
		$('#prModalLabel').text(item.row.name + ' (' + item.row.code + ')');
		if (site.settings.tax1) {
			$('#ptax').select2('val', item.row.tax_rate);
	 		$('#old_tax').val(item.row.tax_rate);
	 		var item_discount = 0, ds = discount ? discount : '0';
	 		if (ds.indexOf("%") !== -1) {
	 			var pds = ds.split("%");
	 			if (!isNaN(pds[0])) {
	 				item_discount = formatDecimal(parseFloat(((real_unit_price) * parseFloat(pds[0])) / 100), 4);
	 			} else {
	 				item_discount = parseFloat(ds);
	 			}
	 		} else {
	 			item_discount = parseFloat(ds);
	 		}
	 		net_price -= item_discount;
	 		var pr_tax = item.row.tax_rate, pr_tax_val = 0;
 		    if (pr_tax !== null && pr_tax != 0) {
 		        $.each(tax_rates, function () {
 		        	if(this.id == pr_tax){
 			        	if (this.type == 1) {

 			        		if (positems[item_id].row.tax_method == 0) {
 			        			pr_tax_val = formatDecimal((((real_unit_price-item_discount) * parseFloat(this.rate)) / (100 + parseFloat(this.rate))), 4);
 			        			pr_tax_rate = formatDecimal(this.rate) + '%';
 			        			net_price -= pr_tax_val;
 			        		} else {
 			        			pr_tax_val = formatDecimal((((real_unit_price-item_discount) * parseFloat(this.rate)) / 100), 4);
 			        			pr_tax_rate = formatDecimal(this.rate) + '%';
 			        		}

 			        	} else if (this.type == 2) {

 			        		pr_tax_val = parseFloat(this.rate);
 			        		pr_tax_rate = this.rate;

 			        	}
 			        }
 			    });
 		    }
		}
		if (site.settings.product_serial !== 0) {
			$('#pserial').val(row.children().children('.rserial').val());
		}
		var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
		if(item.options !== false) {
			var o = 1;
			opt = $("<select id=\"poption\" name=\"poption\" class=\"form-control select\" />");
			$.each(item.options, function () {
				if(o == 1) {
					if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
				}
				$("<option />", {value: this.id, text: this.name}).appendTo(opt);
				o++;
			});
		} else {
            product_variant = 0;
        }
        if (item.units !== false) {
        	uopt = $("<select id=\"punit\" name=\"punit\" class=\"form-control select\" />");
        	$.each(item.units, function () {
        		if(this.id == item.row.unit) {
        			$("<option />", {value: this.id, text: this.name, selected:true}).appendTo(uopt);
        		} else {
        			$("<option />", {value: this.id, text: this.name}).appendTo(uopt);
        		}
        	});
        } else {
        	uopt = '<p style="margin: 12px 0 0 0;">n/a</p>';
        }

		$('#poptions-div').html(opt);
		$('#punits-div').html(uopt);
		$('select.select').select2({minimumResultsForSearch: 7});
		$('#pquantity').val(qty);
		$('#old_qty').val(qty);
		$('#pprice').val(unit_price);
		$('#punit_price').val(formatDecimal(parseFloat(unit_price)+parseFloat(pr_tax_val)));
		$('#poption').select2('val', item.row.option);
		$('#old_price').val(unit_price);
		$('#row_id').val(row_id);
		$('#item_id').val(item_id);
		$('#pserial').val(row.children().children('.rserial').val());
		$('#pdiscount').val(discount);
		$('#net_price').text(formatMoney(net_price));
	    $('#pro_tax').text(formatMoney(pr_tax_val));
		$('#prModal').appendTo("body").modal('show');

	});

	$('#prModal').on('shown.bs.modal', function (e) {
		if($('#poption').select2('val') != '') {
			$('#poption').select2('val', product_variant);
			product_variant = 0;
		}
	});

	$(document).on('change', '#pprice, #ptax, #pdiscount', function () {
	    var row = $('#' + $('#row_id').val());
	    var item_id = row.attr('data-item-id');
	    var unit_price = parseFloat($('#pprice').val());
	    var item = positems[item_id];
	    var ds = $('#pdiscount').val() ? $('#pdiscount').val() : '0';
	    if (ds.indexOf("%") !== -1) {
	        var pds = ds.split("%");
	        if (!isNaN(pds[0])) {
	            item_discount = parseFloat(((unit_price) * parseFloat(pds[0])) / 100);
	        } else {
	            item_discount = parseFloat(ds);
	        }
	    } else {
	        item_discount = parseFloat(ds);
	    }
	    unit_price -= item_discount;
	    var pr_tax = $('#ptax').val(), item_tax_method = item.row.tax_method;
	    var pr_tax_val = 0, pr_tax_rate = 0;
	    if (pr_tax !== null && pr_tax != 0) {
	        $.each(tax_rates, function () {
	        	if(this.id == pr_tax){
		        	if (this.type == 1) {

		        		if (item_tax_method == 0) {
		        			pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
		        			pr_tax_rate = formatDecimal(this.rate) + '%';
		        			unit_price -= pr_tax_val;
		        		} else {
		        			pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / 100);
		        			pr_tax_rate = formatDecimal(this.rate) + '%';
		        		}

		        	} else if (this.type == 2) {

		        		pr_tax_val = parseFloat(this.rate);
		        		pr_tax_rate = this.rate;

		        	}
		        }
		    });
	    }

	    $('#net_price').text(formatMoney(unit_price));
	    $('#pro_tax').text(formatMoney(pr_tax_val));
	});

	$(document).on('change', '#punit', function () {
	    var row = $('#' + $('#row_id').val());
	    var item_id = row.attr('data-item-id');
	    var item = positems[item_id];
	    if (!is_numeric($('#pquantity').val()) || parseFloat($('#pquantity').val()) < 0) {
	        $(this).val(old_row_qty);
	        bootbox.alert(lang.unexpected_value);
	        return;
	    }
	    var opt = $('#poption').val(), unit = $('#punit').val(), base_quantity = $('#pquantity').val(), aprice = 0;
	    if(item.options !== false) {
	    	$.each(item.options, function () {
	    		if(this.id == opt && this.price != 0 && this.price != '' && this.price != null) {
	    			aprice = parseFloat(this.price);
	    		}
	    	});
	    }
	    if(unit != positems[item_id].row.base_unit) {
	    	$.each(item.units, function(){
	    		if (this.id == unit) {
	    			base_quantity = unitToBaseQty($('#pquantity').val(), this);
	    			$('#pprice').val(formatDecimal(((parseFloat(item.row.base_unit_price)*(unitToBaseQty(1, this)))+(aprice*base_quantity)), 4)).change();
	    		}
	    	});
	    } else {
	    	$('#pprice').val(formatDecimal(item.row.base_unit_price+(aprice*base_quantity))).change();
	    }
	});

	/* -----------------------
	 * Edit Row Method
	 ----------------------- */
	$(document).on('click', '#editItem', function () {
		var row = $('#' + $('#row_id').val());
		var item_id = row.attr('data-item-id'), new_pr_tax = $('#ptax').val(), new_pr_tax_rate = false;
		if (new_pr_tax) {
			$.each(tax_rates, function () {
				if (this.id == new_pr_tax) {
					new_pr_tax_rate = this;
				}
			});
		}
		var price = parseFloat($('#pprice').val());
		if(item.options !== false) {
			var opt = $('#poption').val();
			$.each(item.options, function () {
				if(this.id == opt && this.price != 0 && this.price != '' && this.price != null) {
					price = price-parseFloat(this.price);
				}
			});
		}
		if (site.settings.product_discount == 1 && $('#pdiscount').val()) {
			if(!is_valid_discount($('#pdiscount').val()) || $('#pdiscount').val() > price) {
				bootbox.alert(lang.unexpected_value);
				return false;
			}
		}
		if (!is_numeric($('#pquantity').val()) || parseFloat($('#pquantity').val()) < 0) {
		    $(this).val(old_row_qty);
		    bootbox.alert(lang.unexpected_value);
		    return;
		}
		var unit = $('#punit').val();
		var base_quantity = parseFloat($('#pquantity').val());
		if(unit != positems[item_id].row.base_unit) {
			$.each(positems[item_id].units, function(){
				if (this.id == unit) {
					base_quantity = unitToBaseQty($('#pquantity').val(), this);
				}
			});
		}
		positems[item_id].row.fup = 1,
		positems[item_id].row.qty = parseFloat($('#pquantity').val()),
		positems[item_id].row.base_quantity = parseFloat(base_quantity),
		positems[item_id].row.real_unit_price = price,
		positems[item_id].row.unit = unit,
		positems[item_id].row.tax_rate = new_pr_tax,
	 	positems[item_id].tax_rate = new_pr_tax_rate,
		positems[item_id].row.discount = $('#pdiscount').val() ? $('#pdiscount').val() : '',
		positems[item_id].row.option = $('#poption').val() ? $('#poption').val() : '',
		positems[item_id].row.serial = $('#pserial').val();
		localStorage.setItem('positems', JSON.stringify(positems));
		$('#prModal').modal('hide');

		loadItems();
		return;
	});

	/* -----------------------
	 * Product option change
	 ----------------------- */
	$(document).on('change', '#poption', function () {
		var row = $('#' + $('#row_id').val()), opt = $(this).val();
		var item_id = row.attr('data-item-id');
		var item = positems[item_id];
		var unit = $('#punit').val(), base_quantity = parseFloat($('#pquantity').val()), base_unit_price = item.row.base_unit_price;
		if(unit != positems[item_id].row.base_unit) {
			$.each(positems[item_id].units, function(){
				if (this.id == unit) {
					base_unit_price = formatDecimal((parseFloat(item.row.base_unit_price)*(unitToBaseQty(1, this))), 4)
					base_quantity = unitToBaseQty($('#pquantity').val(), this);
				}
			});
		}
		$('#pprice').val(parseFloat(base_unit_price)).trigger('change');
		if(item.options !== false) {
			$.each(item.options, function () {
				if(this.id == opt && this.price != 0 && this.price != '' && this.price != null) {
					$('#pprice').val(parseFloat(base_unit_price)+(parseFloat(this.price)*parseFloat(base_quantity))).trigger('change');
				}
			});
		}
	});

	 /* ------------------------------
	 * Sell Gift Card modal
	 ------------------------------- */
	 $(document).on('click', '#sellGiftCard', function (e) {
	 	if (count == 1) {
	 		positems = {};
	 		if ($('#poswarehouse').val() && $('#poscustomer').val()) {
	 			$('#poscustomer').select2("readonly", true);
	 			$('#poswarehouse').select2("readonly", true);
	 		} else {
	 			bootbox.alert(lang.select_above);
	 			item = null;
	 			return false;
	 		}
	 	}
		$('.gcerror-con').hide();
	 	$('#gcModal').appendTo("body").modal('show');
	 	return false;
	 });

	 $('#gccustomer').select2({
	 	minimumInputLength: 1,
	 	ajax: {
	 		url: site.base_url+"customers/suggestions",
	 		dataType: 'json',
	 		quietMillis: 15,
	 		data: function (term, page) {
	 			return {
	 				term: term,
	 				limit: 10
	 			};
	 		},
	 		results: function (data, page) {
	 			if(data.results != null) {
	 				return { results: data.results };
	 			} else {
	 				return { results: [{id: '', text: 'No Match Found'}]};
	 			}
	 		}
	 	}
	 });

	 $('#genNo').click(function(){
	 	var no = generateCardNo();
	 	$(this).parent().parent('.input-group').children('input').val(no);
	 	return false;
	 });
	 $('.date').datetimepicker({format: site.dateFormats.js_sdate, fontAwesome: true, language: 'sma', todayBtn: 1, autoclose: 1, minView: 2 });
	 $(document).on('click', '#addGiftCard', function (e) {
	 	var mid = (new Date).getTime(),
	 	gccode = $('#gccard_no').val(),
	 	gcname = $('#gcname').val(),
	 	gcvalue = $('#gcvalue').val(),
	 	gccustomer = $('#gccustomer').val(),
	 	gcexpiry = $('#gcexpiry').val() ? $('#gcexpiry').val() : '',
	 	gcprice = formatMoney($('#gcprice').val());
	 	if(gccode == '' || gcvalue == '' || gcprice == '' || gcvalue == 0 || gcprice == 0) {
	 		$('#gcerror').text('Please fill the required fields');
	 		$('.gcerror-con').show();
	 		return false;
	 	}

	 	var gc_data = new Array();
	 	gc_data[0] = gccode;
	 	gc_data[1] = gcvalue;
	 	gc_data[2] = gccustomer;
	 	gc_data[3] = gcexpiry;
		//if (typeof positems === "undefined") {
		//    var positems = {};
		//}

		$.ajax({
			type: 'get',
			url: site.base_url+'sales/sell_gift_card',
			dataType: "json",
			data: { gcdata: gc_data },
			success: function (data) {
				if(data.result === 'success') {
					positems[mid] = {"id": mid, "item_id": mid, "label": gcname + ' (' + gccode + ')', "row": {"id": mid, "code": gccode, "name": gcname, "quantity": 1, "price": gcprice, "real_unit_price": gcprice, "tax_rate": 0, "qty": 1, "type": "manual", "discount": "0", "serial": "", "option":""}, "tax_rate": false, "options":false};
					localStorage.setItem('positems', JSON.stringify(positems));
					loadItems();
					$('#gcModal').modal('hide');
					$('#gccard_no').val('');
					$('#gcvalue').val('');
					$('#gcexpiry').val('');
					$('#gcprice').val('');
				} else {
					$('#gcerror').text(data.message);
					$('.gcerror-con').show();
				}
			}
		});
		return false;
	});

	/* ------------------------------
	 * Show manual item addition modal
	 ------------------------------- */
	 $(document).on('click', '#addManually', function (e) {
		if (count == 1) {
			positems = {};
			if ($('#poswarehouse').val() && $('#poscustomer').val()) {
				$('#poscustomer').select2("readonly", true);
				$('#poswarehouse').select2("readonly", true);
			} else {
				bootbox.alert(lang.select_above);
				item = null;
				return false;
			}
		}
		$('#mnet_price').text('0.00');
		$('#mpro_tax').text('0.00');
		$('#mModal').appendTo("body").modal('show');
		return false;
	});

	 $(document).on('click', '#addItemManually', function (e) {
		var mid = (new Date).getTime(),
		mcode = $('#mcode').val(),
		mname = $('#mname').val(),
		mtax = parseInt($('#mtax').val()),
		mqty = parseFloat($('#mquantity').val()),
		mdiscount = $('#mdiscount').val() ? $('#mdiscount').val() : '0',
		unit_price = parseFloat($('#mprice').val()),
		mtax_rate = {};
		if (mcode && mname && mqty && unit_price) {
			$.each(tax_rates, function () {
				if (this.id == mtax) {
					mtax_rate = this;
				}
			});

			positems[mid] = {"id": mid, "item_id": mid, "label": mname + ' (' + mcode + ')', "row": {"id": mid, "code": mcode, "name": mname, "quantity": mqty, "price": unit_price, "unit_price": unit_price, "real_unit_price": unit_price, "tax_rate": mtax, "tax_method": 0, "qty": mqty, "type": "manual", "discount": mdiscount, "serial": "", "option":""}, "tax_rate": mtax_rate, 'units': false, "options":false};
			localStorage.setItem('positems', JSON.stringify(positems));
			loadItems();
		}
		$('#mModal').modal('hide');
		$('#mcode').val('');
		$('#mname').val('');
		$('#mtax').val('');
		$('#mquantity').val('');
		$('#mdiscount').val('');
		$('#mprice').val('');
		return false;
	});

	$(document).on('change', '#mprice, #mtax, #mdiscount', function () {
	    var unit_price = parseFloat($('#mprice').val());
	    var ds = $('#mdiscount').val() ? $('#mdiscount').val() : '0';
	    if (ds.indexOf("%") !== -1) {
	        var pds = ds.split("%");
	        if (!isNaN(pds[0])) {
	            item_discount = parseFloat(((unit_price) * parseFloat(pds[0])) / 100);
	        } else {
	            item_discount = parseFloat(ds);
	        }
	    } else {
	        item_discount = parseFloat(ds);
	    }
	    unit_price -= item_discount;
	    var pr_tax = $('#mtax').val(), item_tax_method = 0;
	    var pr_tax_val = 0, pr_tax_rate = 0;
	    if (pr_tax !== null && pr_tax != 0) {
	        $.each(tax_rates, function () {
	        	if(this.id == pr_tax){
		        	if (this.type == 1) {

		        		if (item_tax_method == 0) {
		        			pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
		        			pr_tax_rate = formatDecimal(this.rate) + '%';
		        			unit_price -= pr_tax_val;
		        		} else {
		        			pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / 100);
		        			pr_tax_rate = formatDecimal(this.rate) + '%';
		        		}

		        	} else if (this.type == 2) {

		        		pr_tax_val = parseFloat(this.rate);
		        		pr_tax_rate = this.rate;

		        	}
		        }
		    });
	    }

	    $('#mnet_price').text(formatMoney(unit_price));
	    $('#mpro_tax').text(formatMoney(pr_tax_val));
	});

	/* --------------------------
	 * Edit Row Quantity Method
	--------------------------- */
	var old_row_qty;
	$(document).on("focus", '.rquantity', function () {
		old_row_qty = $(this).val();
	}).on("change", '.rquantity', function () {
		var row = $(this).closest('tr');
		if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
			$(this).val(old_row_qty);
			bootbox.alert(lang.unexpected_value);
			return;
		}
		var new_qty = parseFloat($(this).val()),
		item_id = row.attr('data-item-id');
		positems[item_id].row.base_quantity = new_qty;
		if(positems[item_id].row.unit != positems[item_id].row.base_unit) {
			$.each(positems[item_id].units, function(){
				if (this.id == positems[item_id].row.unit) {
					positems[item_id].row.base_quantity = unitToBaseQty(new_qty, this);
				}
			});
		}
		positems[item_id].row.qty = new_qty;
		localStorage.setItem('positems', JSON.stringify(positems));
		loadItems();
	});
    
    
// end ready function
});

/* -----------------------
 * Load all items
 ----------------------- */

//localStorage.clear();
function loadItems() {

	if (localStorage.getItem('positems')) {
		total = 0;
		count = 1;
		an = 1;
		product_tax = 0;
		invoice_tax = 0;
		product_discount = 0;
		order_discount = 0;
		total_discount = 0;
                tip = 0;

		$("#posTable tbody").empty();
		if(java_applet == 1) {
			order_data = "";
			bill_data = "";
			bill_data += chr(27) + chr(69) + "\r" + chr(27) + "\x61" + "\x31\r";
			bill_data += site.settings.site_name + "\n\n";
			order_data = bill_data;
			bill_data += lang.bill + "\n";
			order_data += lang.order + "\n";
			bill_data += $('#select2-chosen-1').text() + "\n\n";
			bill_data += " \x1B\x45\x0A\r\n ";
			order_data += $('#select2-chosen-1').text() + "\n\n";
			order_data += " \x1B\x45\x0A\r\n ";
			bill_data += "\x1B\x61\x30";
			order_data += "\x1B\x61\x30";
		} else {
			$("#order_span").empty(); $("#bill_span").empty();
			var styles = '<style>table, th, td { border-collapse:collapse; border-bottom: 1px solid #CCC; } .no-border { border: 0; } .bold { font-weight: bold; }</style>';
			// var pos_head1 = '<h3><img style="width: 40%;" src="'+localStorage.getItem('poslogo')+'" alt="'+site.settings.site_name+'"></h3>';
			var pos_head2 = '<p><b>'+localStorage.getItem('posmessagebill')+'</b> <br> '+localStorage.getItem('posinfobiller')+'</p>';
			var pos_head3 = '<h3>'+localStorage.getItem('postablelang')+' #'+localStorage.getItem('postablename')+'</h3>';
			var pos_head4 = '<p>'+localStorage.getItem('poslangwaiter')+': '+localStorage.getItem('poswaiter')+'<br>'+localStorage.getItem('poslangcustomer')+': '+$('#select2-chosen-1').text()+'<br>'+hrld()+'</p>';
			$("#order_span").prepend(styles +' Orden ' +pos_head2);
			$("#bill_span").prepend(styles + '<span style="text-align:center;"><h4 style="text-align:center;">' + localStorage.getItem('posbilltitle') + '</h4> ' + pos_head2 + pos_head3 + pos_head4 + '</span>');
			$("#order-table").empty(); $("#bill-table").empty();
		}
		positems = JSON.parse(localStorage.getItem('positems'));
		if (pos_settings.item_order == 1) {
			sortedItems = _.sortBy(positems, function(o){ return [parseInt(o.category), parseInt(o.order)]; } );
		} else if (site.settings.item_addition == 1) {
			sortedItems = _.sortBy(positems, function(o){return [parseInt(o.order)];})
		} else {
			sortedItems = positems;
		}
		var category = 0, print_cate = false;
		// var itn = parseInt(Object.keys(sortedItems).length);

		// products combined
		var products_array = {};

		$.each(sortedItems, function () {

			var item = this;
			var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
			positems[item_id] = item;
			item.order = item.order ? item.order : new Date().getTime();
			var product_id = item.row.id, item_type = item.row.type, combo_items = item.combo_items, item_price = item.row.price, item_qty = item.row.qty, item_aqty = item.row.quantity, item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_discount = 0, item_option = item.row.option, item_code = item.row.code, item_serial = item.row.serial, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
			var product_unit = item.row.unit, base_quantity = item.row.base_quantity;
			var unit_price = item.row.real_unit_price;
			if(item.row.fup != 1 && product_unit != item.row.base_unit) {
				$.each(item.units, function(){
					if (this.id == product_unit) {
						base_quantity = formatDecimal(unitToBaseQty(item.row.qty, this), 4);
						unit_price = formatDecimal((parseFloat(item.row.base_unit_price)*(unitToBaseQty(1, this))), 4);
					}
				});
			}
			if(item.options !== false) {
				$.each(item.options, function () {
					if(this.id == item.row.option && this.price != 0 && this.price != '' && this.price != null) {
						item_price = unit_price+(parseFloat(this.price)*parseFloat(item.row.base_quantity));
						unit_price = item_price;
					}
				});
			}

			var ds = item_ds ? item_ds : '0';
			if (ds.indexOf("%") !== -1) {
				var pds = ds.split("%");
				if (!isNaN(pds[0])) {
					item_discount = formatDecimal((parseFloat(((unit_price) * parseFloat(pds[0])) / 100)), 4);
				} else {
					item_discount = formatDecimal(ds);
				}
			} else {
				 item_discount = formatDecimal(ds);
			}
			product_discount += formatDecimal(item_discount * item_qty);

			unit_price = formatDecimal(unit_price-item_discount);
			var pr_tax = item.tax_rate;
			var pr_tax_val = 0, pr_tax_rate = 0;
			if (site.settings.tax1 == 1) {
				if (pr_tax !== false) {
					if (pr_tax.type == 1) {

						if (item_tax_method == '0') {
							pr_tax_val = formatDecimal(((unit_price) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)), 4);
							pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
						} else {
							pr_tax_val = formatDecimal(((unit_price) * parseFloat(pr_tax.rate)) / 100, 4);
							pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
						}

					} else if (pr_tax.type == 2) {

						pr_tax_val = formatDecimal(pr_tax.rate);
						pr_tax_rate = pr_tax.rate;

					}
					product_tax += pr_tax_val * item_qty;
				}
			}
			item_price = item_tax_method == 0 ? formatDecimal((unit_price-pr_tax_val), 4) : formatDecimal(unit_price);
			unit_price = formatDecimal((unit_price+item_discount), 4);
			var sel_opt = '';

			$.each(item.options, function () {
				if(this.id == item_option) {
					sel_opt = this.name;
				}
			});

			if (pos_settings.item_order == 1 && category != item.row.category_id) {
				category = item.row.category_id;
				print_cate = true;
				var newTh = $('<tr></tr>');
				newTh.html('<td colspan="100%"><strong>'+item.row.category_name+'</strong></td>');
				newTh.appendTo("#posTable");
			} else {
				print_cate = false;
			}

			var row_no = (new Date).getTime();
			var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
			tr_html = '<td><input name="product_sid[]" type="hidden" class="srid" value="' + item.sid + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><span class="sname" id="name_' + row_no + '">' + item_code +' - '+ item_name +(sel_opt != '' ? ' ('+sel_opt+')' : '')+'</span></td>';
			tr_html += '<td class="text-right">';
			if (site.settings.product_serial == 1) {
				tr_html += '<input class="form-control input-sm rserial" name="serial[]" type="hidden" id="serial_' + row_no + '" value="'+item_serial+'">';
			}
			if (site.settings.product_discount == 1) {
				tr_html += '<input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '">';
			}
			if (site.settings.tax1 == 1) {
				tr_html += '<input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><input type="hidden" class="sproduct_tax" id="sproduct_tax_' + row_no + '" value="' + formatMoney(pr_tax_val * item_qty) + '">';
			}
			tr_html += '<input class="rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + item_price + '"><input class="ruprice" name="unit_price[]" type="hidden" value="' + unit_price + '"><input class="realuprice" name="real_unit_price[]" type="hidden" value="' + item.row.real_unit_price + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val)) + '</span></td>';
			tr_html += '<td><input class="form-control input-sm kb-pad text-center rquantity" tabindex="'+((site.settings.set_focus == 1) ? an : (an+1))+'" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"><input name="product_unit[]" type="hidden" class="runit" value="' + product_unit + '"><input name="product_base_quantity[]" type="hidden" class="rbase_quantity" value="' + base_quantity + '"></td>';
			tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</span></td>';
			tr_html += '<td class="text-center"><i class="fa fa-times tip pointer posdel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
			newTr.html(tr_html);
			if (pos_settings.item_order == 1) {
				newTr.appendTo("#posTable");
			} else {
				newTr.prependTo("#posTable");
			}
			total += formatDecimal(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)), 4);
			count += parseFloat(item_qty);
			an++;

			if (item_type == 'standard' && item.options !== false) {
				$.each(item.options, function () {
					if(this.id == item_option && base_quantity > this.quantity) {
						$('#row_' + row_no).addClass('danger');
					}
				});
			} else if(item_type == 'standard' && base_quantity > item_aqty) {
				$('#row_' + row_no).addClass('danger');
			} else if (item_type == 'combo') {
				if(combo_items === false) {
					$('#row_' + row_no).addClass('danger');
				} else {
					$.each(combo_items, function(){
						if(parseFloat(this.quantity) < (parseFloat(this.qty)*base_quantity) && this.type == 'standard') {
							$('#row_' + row_no).addClass('danger');
						}
					});
				}
			}
			if(java_applet == 1) {
				bill_data += item_name + "\n";
				bill_data += printLine(item_qty + " x " + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val))+": "+ formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)))) + "\n";
				order_data += printLine("#"+(an-1)+" "+ item_name + " (" + item_code + "):"+ formatDecimal(item_qty)) + "\n";
			} else {
				if (pos_settings.item_order == 1 && print_cate) {
					var bprTh = $('<tr></tr>');
					bprTh.html('<td colspan="100%" class="no-border"><strong>'+item.row.category_name+'</strong></td>');
					var oprTh = $('<tr></tr>');
					oprTh.html('<td colspan="100%" class="no-border"><strong>'+item.row.category_name+'</strong></td>');
					$("#order-table").append(oprTh);
					$("#bill-table").append(bprTh);
				}
				// var bprTr = '<tr class="row_' + item_id + '" data-item-id="' + item_id + '"><td colspan="2" class="no-border">'+ formatDecimal(item_qty) + " " + item_name + ' </td><td style="text-align:right;">'+ formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) +'</td></tr>';

				if(typeof products_array[item.row.code] === 'undefined') {
					// does not exist
					var product_data = {
						'price' : ((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)),
						'qty' : formatDecimal(item_qty),
						'name' : item_name,
						'item_id' : item_id
					};
					products_array[item.row.code] = product_data;
				}
				else {
					products_array[item.row.code]['qty'] += formatDecimal(item_qty);
					var product_price = ((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty));
					products_array[item.row.code]['price'] = (product_price + products_array[item.row.code]['price']);
				}

				// bprTr += '<tr class="row_' + item_id + '" data-item-id="' + item_id + '"><td>(' + formatDecimal(item_qty) + ' x ' + (item_discount != 0 ? '<del>'+formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val) + item_discount)+'</del>' : '') + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val))+ ')</td><td style="text-align:right;">'+ formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) +'</td></tr>';
				var oprTr = '<tr class="row_' + item_id + '" data-item-id="' + item_id + '"><td>#'+(an-1)+' ' + item_name + ' (' + item_code + ')</td><td>' + formatDecimal(item_qty) +'</td></tr>';
				$("#order-table").append(oprTr);
				// $("#bill-table").append(bprTr);
			}
		});

		$.each(products_array, function () {
			var prod = this;
			var bprTr = '<tr class="row_' + prod.item_id + '" data-item-id="' + prod.item_id + '"><td colspan="2" class="no-border">'+ prod.qty + "  " + prod.name + ' </td><td style="text-align:right;">'+ formatMoney(prod.price) +'</td></tr>';
			$("#bill-table").append(bprTr);
		});

		// console.log(products_array);

		// Order level discount calculations
		if (posdiscount = localStorage.getItem('posdiscount')) {
			var ds = posdiscount;
			if (ds.indexOf("%") !== -1) {
				var pds = ds.split("%");
				if (!isNaN(pds[0])) {
					order_discount = formatDecimal((parseFloat(((total) * parseFloat(pds[0])) / 100)), 4);
				} else {
					order_discount = parseFloat(ds);
				}
			} else {
				order_discount = parseFloat(ds);
			}
			//total_discount += parseFloat(order_discount);
		}

		// Order level tax calculations
		if (site.settings.tax2 != 0) {
			if (postax2 = localStorage.getItem('postax2')) {
				$.each(tax_rates, function () {
					if (this.id == postax2) {
						if (this.type == 2) {
							var fixed_value = $('#fixed_tax').val();
							invoice_tax = formatDecimal(fixed_value);
						}
						if (this.type == 1) {
							invoice_tax = formatDecimal((((total - order_discount) * this.rate) / 100), 4);
						}
					}
				});
			}
		}
                
                if(site.settings.tip != 0){
                    if (order_tip = localStorage.getItem('order_tip')) {
                        $.each(tip_rates, function () {
                            if (this.id == order_tip) {
                                if (this.type == 2) {
                                    var fixed_value = $('#fixed_tip').val();
                                    tip = formatDecimal(fixed_value);
                                }
                                if (this.type == 1) {
                                    if(site.settings.sale_tax_method == 0){
                                        tip = parseInt(formatDecimal((((total - invoice_tax - order_discount) * this.rate) / 100), 4));
                                    }else{
                                        tip = parseInt(formatDecimal((((total - order_discount) * this.rate) / 100), 4));
                                    }
                                }
                            }
                        });
                    }
                }

		total = formatDecimal(total);
		product_tax = formatDecimal(product_tax);
		total_discount = formatDecimal(order_discount + product_discount);

		// Totals calculations after item addition
                if(site.settings.sale_tax_method == 0){
                    var gtotal = parseFloat(((total + tip) - order_discount) + shipping);
                    var saved_sale = parseFloat(((total - invoice_tax) - order_discount) + shipping);
                }else {
                    var gtotal = parseFloat(((total + invoice_tax + tip) - order_discount) + shipping);
                }
		
		$('#total').text(formatMoney(total));
		$('#titems').text((an - 1) + ' (' + formatDecimal(parseFloat(count) - 1) + ')');
		$('#total_items').val((parseFloat(count) - 1));
		$('#tds').text('('+formatMoney(product_discount)+') '+formatMoney(order_discount));
		if (site.settings.tax2 != 0) {
			$('#ttax2').text(formatMoney(invoice_tax));
		}
                if (site.settings.tip != 0) {
			$('#tip').text(formatMoney(tip));
		}
		$('#gtotal').text(formatMoney(gtotal));
		if(java_applet == 1) {
			bill_data += "\n"+ printLine(lang_total+': '+ formatMoney(total)) +"\n";
			bill_data += printLine(lang_items+': '+ (an - 1) + ' (' + (parseFloat(count) - 1) + ')') +"\n";
			if(total_discount > 0) {
				bill_data += printLine(lang_discount+': ('+formatMoney(product_discount)+') '+formatMoney(order_discount)) +"\n";
			}
			if (site.settings.tax2 != 0 && invoice_tax != 0) {
				bill_data += printLine(lang_tax2+': '+ formatMoney(invoice_tax)) +"\n";
			}
			bill_data += printLine(lang_total_payable+': '+ formatMoney(gtotal)) +"\n";
		} else {
			var bill_totals = '';
                        if((site.settings.tip != 0 && tip != 0) || order_discount > 0 ){
                            var lang_subtotal_final = lang_subtotal ;
                        }else{
                            var lang_subtotal_final = lang_total ;
                        }
			bill_totals += '<tr class="bold grand-total"><td>'+lang_subtotal_final+'</td><td style="text-align:right;">'+formatMoney(total)+'</td></tr>';
			// bill_totals += '<tr class="bold"><td>'+lang_items+'</td><td style="text-align:right;">'+(an - 1) + ' (' + (parseFloat(count) - 1) + ')</td></tr>';
                        
			if(order_discount > 0) {
				bill_totals += '<tr class=""><td>'+lang_discount+'</td><td style="text-align:right;">- '+formatMoney(order_discount)+'</td></tr>';
			}
                        if(site.settings.sale_tax_method == 0){
                            bill_totals += '<tr class=""><td>'+lang_saved_sale+'</td><td style="text-align:right;">'+formatMoney(saved_sale)+'</td></tr>';
                        }
			if (site.settings.tax2 != 0 && invoice_tax != 0) {
				bill_totals += '<tr class=""><td>'+lang_tax2+'</td><td style="text-align:right;">+ '+formatMoney(invoice_tax)+'</td></tr>';
			}
                        if (site.settings.tip != 0 && tip != 0) {
				bill_totals += '<tr class=""><td>'+lang_tip+'</td><td style="text-align:right;">+ '+formatMoney(tip)+'</td></tr>';
			}
                        if(site.settings.sale_tax_method == 0){
                            var lang_total_final = lang_total ;
                        }else{
                            var lang_total_final = (site.settings.tax2 != 0 && invoice_tax != 0) ? lang_total + " + " + lang_tax2 : lang_total ;
                        }
                        lang_total_final = (site.settings.tip != 0 && tip != 0) ? lang_total_final + " + " + lang_tip : lang_total_final ;
			bill_totals += '<tr class="bold grand-total"><td>'+lang_total+'</td><td style="text-align:right;">'+formatMoney(gtotal)+'</td></tr>';
			bill_totals += '<tr><td colspan="2"><div class="well well-sm" style="text-align: center">'+localStorage.getItem('posinvoicefooter')+'</div>';
			bill_totals += '<div class="footer-fact" style="text-align: center;font-size: 10px;">Impreso por IGNIWEB POS<br>www.igniweb.com tel: 301 786 2011 - 745 1042</div></td></tr>';
			$('#bill-total-table').empty();
			$('#bill-total-table').append(bill_totals);
		}
		if(count > 1) {
			$('#poscustomer').select2("readonly", true);
			$('#poswarehouse').select2("readonly", true);
		} else {
			$('#poscustomer').select2("readonly", false);
			$('#poswarehouse').select2("readonly", false);
		}
		if (KB) { display_keyboards(); }
		if (site.settings.set_focus == 1) {
		    $('#add_item').attr('tabindex', an);
		    $('[tabindex='+(an-1)+']').focus().select();
		} else {
		    $('#add_item').attr('tabindex', 1);
		    $('#add_item').focus();
		}
	}
}

function printLine(str) {
	var size = pos_settings.char_per_line;
	var len = str.length;
	var res = str.split(":");
	var newd = res[0];
	for(i=1; i<(size-len); i++) {
		newd += " ";
	}
	newd += res[1];
	return newd;
}

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */

 function add_invoice_item(item) {

 	if (count == 1) {
 		positems = {};
 		if ($('#poswarehouse').val() && $('#poscustomer').val()) {
 			$('#poscustomer').select2("readonly", true);
 			$('#poswarehouse').select2("readonly", true);
 		} else {
 			bootbox.alert(lang.select_above);
 			item = null;
 			return;
 		}
 	}
 	if (item == null)
 		return;

 	var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
 	if (positems[item_id]) {
 		positems[item_id].row.qty = parseFloat(positems[item_id].row.qty) + 1;
 	} else {
 		positems[item_id] = item;
 	}
 	positems[item_id].order = new Date().getTime();
 	localStorage.setItem('positems', JSON.stringify(positems));
 	loadItems();
 	return true;
 }


 if (typeof (Storage) === "undefined") {
 	$(window).bind('beforeunload', function (e) {
 		if (count > 1) {
 			var message = "You will loss data!";
 			return message;
 		}
 	});
 }

 function display_keyboards() {

 	$('.kb-text').keyboard({
 		autoAccept: true,
 		alwaysOpen: false,
 		openOn: 'focus',
 		usePreview: false,
 		layout: 'custom',
		//layout: 'qwerty',
		display: {
			'bksp': "\u2190",
			'accept': 'return',
			'default': 'ABC',
			'meta1': '123',
			'meta2': '#+='
		},
		customLayout: {
			'default': [
			'q w e r t y u i o p {bksp}',
			'a s d f g h j k l {enter}',
			'{s} z x c v b n m , . {s}',
			'{meta1} {space} {cancel} {accept}'
			],
			'shift': [
			'Q W E R T Y U I O P {bksp}',
			'A S D F G H J K L {enter}',
			'{s} Z X C V B N M / ? {s}',
			'{meta1} {space} {meta1} {accept}'
			],
			'meta1': [
			'1 2 3 4 5 6 7 8 9 0 {bksp}',
			'- / : ; ( ) \u20ac & @ {enter}',
			'{meta2} . , ? ! \' " {meta2}',
			'{default} {space} {default} {accept}'
			],
			'meta2': [
			'[ ] { } # % ^ * + = {bksp}',
			'_ \\ | &lt; &gt; $ \u00a3 \u00a5 {enter}',
			'{meta1} ~ . , ? ! \' " {meta1}',
			'{default} {space} {default} {accept}'
			]}
		});
 	$('.kb-pad').keyboard({
 		restrictInput: true,
 		preventPaste: true,
 		autoAccept: true,
 		alwaysOpen: false,
 		openOn: 'click',
 		usePreview: false,
 		layout: 'custom',
 		display: {
 			'b': '\u2190:Backspace',
 		},
 		customLayout: {
 			'default': [
 			'1 2 3 {b}',
 			'4 5 6 . {clear}',
 			'7 8 9 0 %',
 			'{accept} {cancel}'
 			]
 		}
 	});
 	var cc_key = (site.settings.decimals_sep == ',' ? ',' : '{clear}');
 	$('.kb-pad1').keyboard({
 		restrictInput: true,
 		preventPaste: true,
 		autoAccept: true,
 		alwaysOpen: false,
 		openOn: 'click',
 		usePreview: false,
 		layout: 'custom',
 		display: {
 			'b': '\u2190:Backspace',
 		},
 		customLayout: {
 			'default': [
 			'1 2 3 {b}',
 			'4 5 6 . '+cc_key,
 			'7 8 9 0 %',
 			'{accept} {cancel}'
 			]
 		}
 	});

 }

/*$(window).bind('beforeunload', function(e) {
	if(count > 1){
	var msg = 'You will loss the sale data.';
		(e || window.event).returnValue = msg;
		return msg;
	}
});
*/
if(site.settings.auto_detect_barcode == 1) {
	$(document).ready(function() {
		var pressed = false;
		var chars = [];
		$(window).keypress(function(e) {
			if(e.key == '%') { pressed = true; }
			chars.push(String.fromCharCode(e.which));
			if (pressed == false) {
				setTimeout(function(){
					if (chars.length >= 8) {
						var barcode = chars.join("");
						$( "#add_item" ).focus().autocomplete( "search", barcode );
					}
					chars = [];
					pressed = false;
				},200);
			}
			pressed = true;
		});
	});
}
$(document).ready(function() {
	read_card();
});

function generateCardNo(x) {
	if(!x) { x = 16; }
	chars = "1234567890";
	no = "";
	for (var i=0; i<x; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		no += chars.substring(rnum,rnum+1);
	}
	return no;
}
function roundNumber(number, toref) {
	switch(toref) {
	    case 1:
	        var rn = formatDecimal(Math.round(number * 20)/20);
	        break;
	    case 2:
	        var rn = formatDecimal(Math.round(number * 2)/2);
	        break;
	    case 3:
	        var rn = formatDecimal(Math.round(number));
	        break;
	    case 4:
	        var rn = formatDecimal(Math.ceil(number));
	        break;
	    default:
	        var rn = number;
	}
	return rn;
}
function getNumber(x) {
	return accounting.unformat(x);
}
function formatQuantity(x) {
    return (x != null) ? '<div class="text-center">'+formatNumber(x, site.settings.qty_decimals)+'</div>' : '';
}
function formatNumber(x, d) {
    if(!d && d != 0) { d = site.settings.decimals; }
    if(site.settings.sac == 1) {
        return formatSA(parseFloat(x).toFixed(d));
    }
    return accounting.formatNumber(x, d, site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep, site.settings.decimals_sep);
}
function formatMoney(x, symbol) {
    if(!symbol) { symbol = ""; }
    if(site.settings.sac == 1) {
        return symbol+''+formatSA(parseFloat(x).toFixed(site.settings.decimals));
    }
    return accounting.formatMoney(x, symbol, site.settings.decimals, site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep, site.settings.decimals_sep, "%s%v");
}
function formatCNum(x) {
	if (site.settings.decimals_sep == ',') {
	    var x = x.toString();
	    var x = x.replace(",", ".");
	    return parseFloat(x);
	}
	return x;
}
function formatDecimal(x, d) {
	if (!d) { d = site.settings.decimals; }
	return parseFloat(accounting.formatNumber(x, d, '', '.'));
}
function hrsd(sdate) {
	return moment().format(site.dateFormats.js_sdate.toUpperCase())
}

function hrld(ldate) {
	return moment().format(site.dateFormats.js_sdate.toUpperCase()+' H:mm')
}
function is_valid_discount(mixed_var) {
	return (is_numeric(mixed_var) || (/([0-9]%)/i.test(mixed_var))) ? true : false;
}
function is_numeric(mixed_var) {
	var whitespace =
	" \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
	return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
		1)) && mixed_var !== '' && !isNaN(mixed_var);
}
function is_float(mixed_var) {
	return +mixed_var === mixed_var && (!isFinite(mixed_var) || !! (mixed_var % 1));
}
function currencyFormat(x) {
	if (x != null) {
		return formatMoney(x);
	} else {
		return '0';
	}
}
function formatSA (x) {
    x=x.toString();
    var afterPoint = '';
    if(x.indexOf('.') > 0)
       afterPoint = x.substring(x.indexOf('.'),x.length);
    x = Math.floor(x);
    x=x.toString();
    var lastThree = x.substring(x.length-3);
    var otherNumbers = x.substring(0,x.length-3);
    if(otherNumbers != '')
        lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;

    return res;
}

function unitToBaseQty(qty, unitObj) {
    switch(unitObj.operator) {
        case '*':
            return parseFloat(qty)*parseFloat(unitObj.operation_value);
            break;
        case '/':
            return parseFloat(qty)/parseFloat(unitObj.operation_value);
            break;
        case '+':
            return parseFloat(qty)+parseFloat(unitObj.operation_value);
            break;
        case '-':
            return parseFloat(qty)-parseFloat(unitObj.operation_value);
            break;
        default:
            return parseFloat(qty);
    }
}

function baseToUnitQty(qty, unitObj) {
    switch(unitObj.operator) {
        case '*':
            return parseFloat(qty)/parseFloat(unitObj.operation_value);
            break;
        case '/':
            return parseFloat(qty)*parseFloat(unitObj.operation_value);
            break;
        case '+':
            return parseFloat(qty)-parseFloat(unitObj.operation_value);
            break;
        case '-':
            return parseFloat(qty)+parseFloat(unitObj.operation_value);
            break;
        default:
            return parseFloat(qty);
    }
}

function read_card() {
	$('.swipe').keypress( function (e) {
		e.preventDefault();
		var payid = $(this).attr('id'),
		id = payid.substr(payid.length - 1);
		var TrackData = $(this).val();
		if (e.keyCode == 13) {
			e.preventDefault();

			var p = new SwipeParserObj(TrackData);

			if(p.hasTrack1)
			{
		// Populate form fields using track 1 data
		var CardType = null;
		var ccn1 = p.account.charAt(0);
		if(ccn1 == 4)
			CardType = 'Visa';
		else if(ccn1 == 5)
			CardType = 'MasterCard';
		else if(ccn1 == 3)
			CardType = 'Amex';
		else if(ccn1 == 6)
			CardType = 'Discover';
		else
			CardType = 'Visa';

		$('#pcc_no_'+id).val(p.account);
		$('#pcc_holder_'+id).val(p.account_name);
		$('#pcc_month_'+id).val(p.exp_month);
		$('#pcc_year_'+id).val(p.exp_year);
		$('#pcc_cvv2_'+id).val('');
		$('#pcc_type_'+id).val(CardType);

	}
	else
	{
		$('#pcc_no_'+id).val('');
		$('#pcc_holder_'+id).val('');
		$('#pcc_month_'+id).val('');
		$('#pcc_year_'+id).val('');
		$('#pcc_cvv2_'+id).val('');
		$('#pcc_type_'+id).val('');
	}

	$('#pcc_cvv2_'+id).focus();
}

}).blur(function (e) {
	$(this).val('');
}).focus( function (e) {
	$(this).val('');
});
}

function check_add_item_val() {
    $('#add_item').bind('keypress', function (e) {
        if (e.keyCode == 13 || e.keyCode == 9) {
            e.preventDefault();
            $(this).autocomplete("search");
        }
    });
}
function nav_pointer() {
    var pp = p_page == 'n' ? 0 : p_page;
    (pp == 0) ? $('#previous').attr('disabled', true) : $('#previous').attr('disabled', false);
    ((pp+pro_limit) > tcp) ? $('#next').attr('disabled', true) : $('#next').attr('disabled', false);
}


$.extend($.keyboard.keyaction, {
	enter : function(base) {
		if (base.$el.is("textarea")){
			base.insertText('\r\n');
		} else {
			base.accept();
		}
	}
});

$(document).ajaxStart(function(){
  $('#ajaxCall').show();
}).ajaxStop(function(){
  $('#ajaxCall').hide();
});

$(document).ready(function(){
	nav_pointer();
	$('#myModal').on('hidden.bs.modal', function() {
		$(this).find('.modal-dialog').empty();
		$(this).removeData('bs.modal');
	});
	$('#myModal2').on('hidden.bs.modal', function () {
		$(this).find('.modal-dialog').empty();
		$(this).removeData('bs.modal');
		$('#myModal').css('zIndex', '1050');
                $('#myModal3').css('zIndex', '1050');
		$('#myModal').css('overflow-y', 'scroll');
	});
	$('#myModal2').on('show.bs.modal', function () {
		$('#myModal').css('zIndex', '1040');
                $('#myModal3').css('zIndex', '1040');
	});
        $('#myModal3').on('hidden.bs.modal', function () {
		$(this).find('.modal-dialog').empty();
		$(this).removeData('bs.modal');
		$('#myModal').css('zIndex', '1050');
		$('#myModal').css('overflow-y', 'scroll');
	});
	$('#myModal3').on('show.bs.modal', function () {
		$('#myModal').css('zIndex', '1040');
                $('#myModal2').css('zIndex', '1060');
	});
	$('.modal').on('hidden.bs.modal', function() {
		$(this).removeData('bs.modal');
	});
	$('.modal').on('show.bs.modal', function () {
		$('#modal-loading').show();
		$('.blackbg').css('zIndex', '1041');
		$('.loader').css('zIndex', '1042');
	}).on('hide.bs.modal', function () {
		$('#modal-loading').hide();
		$('.blackbg').css('zIndex', '3');
		$('.loader').css('zIndex', '4');
	});
	$('#clearLS').click(function(event) {
        bootbox.confirm("Are you sure?", function(result) {
        if(result == true) {
            localStorage.clear();
            location.reload();
        }
        });
        return false;
    });
});

//$.ajaxSetup ({ cache: false, headers: { "cache-control": "no-cache" } });
if(pos_settings.focus_add_item != '') { shortcut.add(pos_settings.focus_add_item, function() { $("#add_item").focus(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.add_manual_product != '') { shortcut.add(pos_settings.add_manual_product, function() { $("#addManually").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.customer_selection != '') { shortcut.add(pos_settings.customer_selection, function() { $("#customer").select2("open"); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.add_customer != '') { shortcut.add(pos_settings.add_customer, function() { $("#add-customer").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.toggle_category_slider != '') { shortcut.add(pos_settings.toggle_category_slider, function() { $("#open-category").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.toggle_brands_slider != '') { shortcut.add(pos_settings.toggle_brands_slider, function() { $("#open-brands").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.toggle_subcategory_slider != '') { shortcut.add(pos_settings.toggle_subcategory_slider, function() { $("#open-subcategory").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.cancel_sale != '') { shortcut.add(pos_settings.cancel_sale, function() { $("#reset").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.suspend_sale != '') { shortcut.add(pos_settings.suspend_sale, function() { $("#suspend").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.print_items_list != '') { shortcut.add(pos_settings.print_items_list, function() { $("#print_btn").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.finalize_sale != '') { shortcut.add(pos_settings.finalize_sale, function() { if ($('#paymentModal').is(':visible')) { $("#submit-sale").click(); } else { $("#payment").trigger('click'); } }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.today_sale != '') { shortcut.add(pos_settings.today_sale, function() { $("#today_sale").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.open_hold_bills != '') { shortcut.add(pos_settings.open_hold_bills, function() { $("#opened_bills").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.close_register != '') { shortcut.add(pos_settings.close_register, function() { $("#close_register").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
shortcut.add("ESC", function() { $("#cp").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} );

if (site.settings.set_focus != 1) {
	$(document).ready(function(){ $('#add_item').focus(); });
}


function delete_sale(id_suspended_sale) {
        bootbox.confirm(lang.alert_x_delete_sale, function (result) {
                if (result) {

                    $('#modal-loading').show();
                    $(location).attr('href', 'pos/delete_suspended_sale/' + id_suspended_sale);
                }
        });
}