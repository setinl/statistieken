function phpAdminSendData(data, callback, timeout)
{
	var url = g_siteUrl + "/stats/admin/php/admin_interface.php";

	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function()
    {
		if (xhr.readyState == 4)
		{
        		callback(xhr.responseText);
		}
	}
	xhr.open("POST", url, true);
	xhr.setRequestHeader("X-File-Name", "snl");
	xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
	xhr.timeout = timeout;	// 10 seconds
//	xhr.timeout = 7200000;	// 2 hour	
	xhr.send(data);
}