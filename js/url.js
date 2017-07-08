function getSiteUrl()
{
    var iPosQ, iPos;
    var search = "seti.nl/";
    g_siteUrl = "";

    var loc = window.location.href;
    if(loc)
    {
        iPosQ = loc.indexOf(search);
        if (iPosQ >= 0)		
        {
            g_siteUrl = loc.substr(0,iPosQ+search.length);
            return;
        }            
       
       search = "localhost:8080/stats/";
       iPosQ = loc.indexOf(search);
       if (iPosQ >= 0)		
       {
           g_siteUrl = loc.substr(0,iPosQ+search.length);
           return;
       }    
    }

}