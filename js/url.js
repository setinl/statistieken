function getSiteUrl()
{
	g_siteUrl = "";

	var loc = window.location.href;
	if(loc)
	{
		var iPosQ = loc.lastIndexOf("localhost");	// debug
		if (iPosQ >= 0)		
		{
                    var iPos = loc.lastIndexOf("/stats");
                    if (iPos >= 0)	g_siteUrl = loc.substr(0,iPos);	
		}
		else
		{
                    g_siteUrl = 'https://stats.seti.nl';
		}
	} 
}