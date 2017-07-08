
ACTION_RESET_ERRRORS = 10;
ACTION_RELOAD_LIST = 11;
ACTION_FORCE_RELOAD = 15;
ACTION_UPDATE_MANUALLY = 20;

g_database_admin_status_ready_timer = null;
g_database_admin_status_timer_count = 0;

$(document).ready(function()
{
    $('#text_status_update').html('Auto update');
});

function buttonResetErrors(token)
{
	resetButtonStatus();
	var id = "";
	var time = "";
	var jsonString = '"action":"'+ ACTION_RESET_ERRRORS + '", "id":"' + id + '","time":"' + time + '","token":"' + token + '"';		
	
	jsonString = jsonString + "}";
	jsonString = "{" + jsonString;
	phpAdminSendData(jsonString, statsResetReady, 5000);	// 5 seconds
}

function buttonRestartList()
{
	resetButtonStatus();
	var id = "";
	var time = "";
        var token = "";        
	var jsonString = '"action":"'+ ACTION_RELOAD_LIST + '", "id":"' + id + '","time":"' + time + '","token":"' + token + '"';		
	
	jsonString = jsonString + "}";
	jsonString = "{" + jsonString;
	phpAdminSendData(jsonString, restartListReady, 5000);	// 5 seconds
}


function statsResetReady(result)
{
	try{
		var json_array = JSON.parse(result);
    }catch(e){
        return;	// error
    }	
	
	if (json_array[0] === "status")
	{
		switch(json_array[1])
		{
			case "reset_error_ok":
				$('#text_button_reset_errors').html("Errors reset OK");
			//	statsStatus();
			break;
			case "reset_error_no_need":
				$('#text_button_reset_errors').html("There are no errors to reset");
			//	statsStatus();
			break;			
			
			default:
				$('#text_button_reset_errors').html(json_array[1]);
			//	statsStatus();			
		}
	}
	else
	{
		printError(json_array[0], json_array[1], "#text_button_reset_errors");
	}
}

function restartListReady(result)
{
	try{
		var json_array = JSON.parse(result);
    }catch(e){
        return;	// error
    }	
	
	if (json_array[0] === "status")
	{
		switch(json_array[1])
		{
			case "list_build_set_ok":
				$('#text_button_manually_list').html("List set to build");
			//	statsStatus();
			break;			
			default:
				$('#text_button_manually_list').html(json_array[1]);
			//	statsStatus();			
		}
	}
	else
	{
		printError(json_array[0], json_array[1], "#text_button_manually_list");
	}
}

function buttonForceReload(token)
{
	resetButtonStatus();
	var id = "";
	var time = "";
	var jsonString = '"action":"'+ ACTION_FORCE_RELOAD + '", "id":"' + id + '","time":"' + time + '","token":"' + token + '"';		
	
	jsonString = jsonString + "}";
	jsonString = "{" + jsonString;
	phpAdminSendData(jsonString, forceReloadReady, 5000);	// 5 seconds
}

function forceReloadReady(result)
{
	try{
		var json_array = JSON.parse(result);
    }catch(e){
        return;	// error
    }	
	
	if (json_array[0] === "status")
	{
		switch(json_array[1])
		{
			case "force_reload_ok":
				$('#text_button_force_reload').html("Force reload OK");
			//	statsStatus();
			break;
			default:
				$('#text_button_force_reload').html(json_array[1]);
			//	statsStatus();			
		}
	}
	else
	{
		printError(json_array[0], json_array[1], "#text_button_force_reload");
	}
}

function buttonStatsManually(token, code)
{
    var id = "";
    var time = "";
    var jsonString = '"action":"'+ ACTION_UPDATE_MANUALLY + '", "id":"' + id + '","time":"' + time + '","token":"' + token + '"';		
    jsonString = jsonString + "}";
    jsonString = "{" + jsonString;
    $('#text_button_manually').html("Update gestart!");

    g_database_admin_status_timer_count = 4;
    if (g_database_admin_status_ready_timer === null)
    {
        g_database_admin_status_ready_timer = setInterval('DatabaseAdminStatusTimer()', 1000); // 1 sec / in mSec.
    }
    phpAdminSendData(jsonString, statsManuellyReady, 7200000); // 2 hours    
}


function DatabaseAdminStatusTimer()
{
	if (g_database_admin_status_timer_count <= 0)
	{
            DatabaseStatus();
            clearInterval(g_database_admin_status_ready_timer);
            g_database_admin_status_ready_timer = null;
	}
	else
        {
            g_database_admin_status_timer_count--;	
	}	
}


// never used !
function statsManuellyReady(result)
{
	try{
		var json_array = JSON.parse(result);
                if (json_array[0] === "status")
                {
                    switch(json_array[1])
                    {
			case "update_end":
				$('#text_button_manually').html("Update ready.");
				//statsStatus();
			break;

			break;			
			
			default:
				$('#text_button_manually').html(json_array[1]);
				//statsStatus();			
                    }
                }
                else
                {
                    printError(json_array[0], json_array[1], "#text_button_manually");
                }                
        }catch(e){
        }
}


function resetButtonStatus()
{
	$('#text_button_reset_errors').html("");
}

function printError(error_string, error_cause, text_span)
{
	if (error_string === "error")
	{
		switch(error_cause)
		{
			case "database":
				$(text_span).html("sql database error.");
			break;
			default:
				$(text_span).html(error_cause);				
		}
	}
}

