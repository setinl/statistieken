function phpSendData(data, callback)
{
	var url = g_siteUrl + "/php/interface.php";

	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function()
    {
		if (xhr.readyState === 4)
		{
        		callback(xhr.responseText);
		}
	};
	xhr.open("POST", url, true);
	xhr.setRequestHeader("X-File-Name", "snl");
	xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
	xhr.timeout = 10000;	// 10 seconds
	xhr.send(data);
}

function phpSendDataArg(context, data, callback, arg1, arg2, arg3)
{
	var url = g_siteUrl + "/php/interface.php";

	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function()
    {
		if (xhr.readyState === 4)
		{
        	callback(context, xhr.responseText, arg1, arg2, arg3);
		}
	};
	xhr.open("POST", url, true);
	xhr.setRequestHeader("X-File-Name", "snl");
	xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
	xhr.timeout = 10000;	// 10 seconds
	xhr.send(data);
}
