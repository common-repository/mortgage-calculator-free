/**
 * Calculator
 *
 * @package Mortgage Calculator Plugin
 * @subpackage JavaScript
 */

jQuery(".calculateMort").click(function(){
	
	var calc = jQuery(this).closest('.mortgage_calculator_form');
	var sp,L,P,n,c,dp;
	n = parseInt(calc.find(".mcpTerm").val().replace(/,/g, ''))*12;
	c = parseFloat(calc.find(".mcpIRate").val().replace(/,/g, ''))/1200;
	sp = parseFloat(calc.find(".mcpSPrice").val().replace(/,/g, ''))
	dp = parseFloat(calc.find(".mcpDPayment").val().replace(/,/g, ''));
	L = sp-dp;
		
	if(calc.find('.has-error')){
		calc.find('.has-error').removeClass('has-error');
	}
	
	if(sp == '' || isNaN(sp) || sp > 30000000){
		calc.find(".mcpSPrice").parent().removeClass('has-success').addClass('has-error');
		calc.find(".mcpSPrice").focus();
		calc.find(".mpayment").addClass('hidden');		
		return false;
	} else {
		calc.find(".mcpSPrice").parent().removeClass('has-error').addClass('has-success');
	}
	
	if(c == '' ||isNaN(c) || (c*1200 > 100)){
		calc.find(".mcpIRate").parent().removeClass('has-success').addClass('has-error');
		calc.find(".mcpIRate").focus();
		calc.find(".mpayment").addClass('hidden');
		return false;
	} else {
		calc.find(".mcpIRate").parent().removeClass('has-error').addClass('has-success');
	}
	
	if(n == '' || isNaN(n) || n > 720){
		calc.find(".mcpTerm").parent().removeClass('has-success').addClass('has-error');
		calc.find(".mcpTerm").focus();
		calc.find(".mpayment").addClass('hidden');
		return false;
	} else {
		calc.find(".mcpTerm").parent().removeClass('has-error').addClass('has-success');
	}
	
	if(((dp == '' || isNaN(dp)) && dp != 0) || dp == sp || dp > sp || dp > 30000000){
		calc.find(".mcpDPayment").parent().removeClass('has-success').addClass('has-error');
		calc.find(".mcpDPayment").focus();
		calc.find(".mpayment").addClass('hidden');
		return false;
	} else {
		calc.find(".mcpDPayment").parent().removeClass('has-error').addClass('has-success');
	}
	
	P = (L*(c*Math.pow(1+c,n)))/(Math.pow(1+c,n)-1);
	
	if(!isNaN(P)){
		calc.find(".mcp_Payment").text(addCommas(P.toFixed(2)));
		calc.find(".mpayment").removeClass('hidden');
	} else {
		calc.find(".mcp_Payment").text('There was an error');
		calc.find(".mpayment").removeClass('hidden');
	}	
	return false;
	
});

jQuery('.mortgage_calculator_form .mcpSPrice').keyup(function () {
	var value = jQuery(this).val().replace(/,/g, '');
	if(value > 30000000){
		jQuery(this).parent().removeClass('has-success').addClass('has-error');
	} else {
		jQuery(this).parent().removeClass('has-error').addClass('has-success');
	}
});

jQuery('.mortgage_calculator_form .mcpIRate').keyup(function () {
	var value = jQuery(this).val().replace(/,/g, '');
	if(value > 100){
		jQuery(this).parent().removeClass('has-success').addClass('has-error');
	} else {
		jQuery(this).parent().removeClass('has-error').addClass('has-success');
	}
});

jQuery('.mortgage_calculator_form .mcpTerm').keyup(function () {
	var value = jQuery(this).val().replace(/,/g, '');
	if(value > 60){
		jQuery(this).parent().removeClass('has-success').addClass('has-error');
	} else {
		jQuery(this).parent().removeClass('has-error').addClass('has-success');
	}
});

jQuery('.mortgage_calculator_form .mcpDPayment').keyup(function () {
	var value = jQuery(this).val().replace(/,/g, '');
	if(value > 30000000){
		jQuery(this).parent().removeClass('has-success').addClass('has-error');
	} else {
		jQuery(this).parent().removeClass('has-error').addClass('has-success');
	}
});

jQuery('.mortgage_calculator_form .text').keyup(function () {
	var value = jQuery(this).val().replace(/,/g, '');
	jQuery(this).val(addCommas(value));
});

function addCommas(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}