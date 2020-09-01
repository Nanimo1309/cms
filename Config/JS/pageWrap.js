$(function()
{
	$("header > form").on("submit", function()
	{
		location.hash = '';
	});
	
	$("nav > ul").children().on("click", function()
	{
		$("nav > input").prop("checked", false);
	});
	
	(function()
	{
		let e = $("#error");
		let x = $("nav");
		
		setInterval(function()
		{
			e.html(x.position().top + "<br />" + x.offset().top);
		}, 10);
	})();
});