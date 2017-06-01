function getSiteUrl()
{
	g_siteUrl = "";

	var loc = window.location.href;
	if(loc)
	{
		var iPosQ = loc.lastIndexOf("localhost");	// debug
		if (iPosQ >= 0)		
		{
			var iPosQ = loc.lastIndexOf("/?");	// Drupal node
			if (iPosQ >= 0)
			{
				g_siteUrl = loc.substr(0,iPosQ);
			}
			else
			{
				var iPos = loc.lastIndexOf("/stats");
				if (iPos >= 0)	g_siteUrl = loc.substr(0,iPos);		
			}
		}
		else
		{
			var iPosQ = loc.lastIndexOf("seti");	// Seti site on lightsail
			if (iPosQ >= 0)
			{			
				var iPos = loc.lastIndexOf("/stats");
				if (iPos >= 0)	g_siteUrl = loc.substr(0,iPos);				
			}
			else
			{
				var iPos = loc.lastIndexOf("/stats");
				if (iPos >= 0)	g_siteUrl = loc.substr(0,iPos);	
			}
		}
	} 
}