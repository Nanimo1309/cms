$(function()
{
	if($(document).width() <= 600)
	{
		let h3 = $("address > h3:first-of-type");
		let a = $("<a>").attr("href", "tel:" + h3.text()).text(h3.text()).css("color", "#e52");
		
		h3.html(a);
	}
});