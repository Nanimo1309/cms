$(function()
{
	let show = $("#galleryShow");
	let html = $("html");
	let img = show.children("img");
	let images = $(".gallery img");
	
	images.on("click", function()
	{
		show.css("display", "flex");
		html.css("overflow", "hidden");
		
		let t = $(this);
		img.attr("src", t.attr("src"));
	});
	
	show.children("div:nth-of-type(2)").children("div:first-of-type").on("click", function()
	{
		html.css("overflow", "initial");
		show.css("display", "none");
	});
	
	show.children("div:first-of-type").children("div:nth-of-type(2)").on("click", function()
	{
		let src = img.attr("src");
		
		let i = $.map(images, function(n, index)
		{
			if(src == $(n).attr("src"))
				return index;
		});
		
		i = i[0];
		
		if(i == 0)
			return;
		
		img.attr("src", images.eq(i - 1).attr("src"));
	});
	
	show.children("div:nth-of-type(2)").children("div:nth-of-type(2)").on("click", function()
	{
		let src = img.attr("src");
		
		let i = $.map(images, function(n, index)
		{
			if(src == $(n).attr("src"))
				return index;
		});
		
		i = i[0];
		
		if(i == images.length - 1)
			return;
		
		img.attr("src", images.eq(i + 1).attr("src"));
	});
});