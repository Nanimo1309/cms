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
		let header = $("header");
		let nav = $("nav");
		let toBegin = $("#toBegin");
		
		setInterval(function()
		{
			if(header.outerHeight(true) + 1 > nav.position().top)
			{
				if(toBegin.is(":visible"))
					toBegin.fadeOut(500);
			}
			else
			{
				if(toBegin.is(":hidden"))
				{
					toBegin.fadeIn(500);
					toBegin.css("display", "inherit");
				}
			}
		}, 500);
	})();
});