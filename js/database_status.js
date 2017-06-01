ACTION_READ_DATABASE_STATUS = 0;
ACTION_READ_DATABASE_ZERO_DAY_STATUS = 1;

g_database_status_interval_timer = null;
g_database_status_timer_count = 0;
g_database_zero_day_status_timer_count = 0;

function DatabaseStatusStartTimer()
{
	DatabaseZeroDayStatus();
	DatabaseStatus();
	g_database_status_timer_count = 4;
	g_database_zero_day_status_timer_count = 4;
	if (g_database_status_interval_timer === null)
	{
		g_database_status_interval_timer = setInterval('DatabaseStatusTimer()', 1000); // 1 sec / in mSec.
	}
}

function DatabaseStatusTimer()
{
	if (g_database_status_timer_count <= 0)
	{
		$('#text_status_update').html(txt_update_now);
		DatabaseStatus();
	}
	else
	{
		if (g_database_status_timer_count < 100)
		{
			$('#text_status_update').html(txt_update_status1+g_database_status_timer_count+txt_update_stauts2);
		}
		g_database_status_timer_count--;
	}
	
	if (g_database_zero_day_status_timer_count <=0)
	{
		DatabaseZeroDayStatus();
		g_database_zero_day_status_timer_count = 4;
	}
	else
	{
		g_database_zero_day_status_timer_count--;	
	}	
}

function DatabaseStatus()
{
	g_database_status_timer_count = 10000;
	DatabaseStatusRead(DatabaseStatusReadReady);
}

function DatabaseStatusRead(readyFunction)
{
	var id = 0;
	var time = 0;	
	var token = "read";	
	var jsonString = '"action":"'+ ACTION_READ_DATABASE_STATUS + '", "id":"' + id + '","time":"' + time + '","token":"' + token + '"';
	jsonString = jsonString + "}";
	jsonString = "{" + jsonString;
	phpSendData(jsonString, readyFunction);
}

function DatabaseStatusReadReady(result)
{
	g_database_status_timer_count = 15;	
	try{
		var json_array = JSON.parse(result);
    }catch(e){
        return;	// error
    }

	var item = json_array.shift();
	if (item !== "data") return;
	item = json_array.shift();	
	if (item !== "ok") return;	

	DatabasePrintStatus(json_array);
}

function DatabasePrintStatus(json_array)
{
//progress
//progress_text
//progress_time
//remove_logging_count
//team_add_duration
//team_error_count
//team_file_read_duration
//team_processed_duration
//team_start_time
//team_url_file_time
//todo
//u_t_total_error_count
//user_add_duration
//user_file_read_duration
//user_processed_duration
//user_start_time
//user_team_time
//user_url_file_time
//users_error_count

	var busy = false;

	var data = json_array[0];
	
	var s_todo = "";
	var todo = data.todo;
	if (todo === '1')
	{
		s_todo = txt_todo_gen_lists;
	}
	$('#text_status_todo').html(s_todo);	
	
	var s_progress;
	var s_progress_time = PrintFullTime(data.progress_time);
	var progress_active = false;
	var progress_text = data.progress_text;
	var progress = data.progress;
//	var progress_time = data.progress_time;
	
// PROGRESS_LIST_SNL 1
// PROGRESS_LIST_USERS 2
// PROGRESS_LIST_ALL_TEAMS 3
// PROGRESS_LIST_ALL_COUNTRIES 4
	
	switch(progress)
	{
		case "1":
			s_progress = txt_progress_snl;
			progress_active = true;
		break;		
		case "2":
			s_progress = txt_progress_build_users + progress_text;
			progress_active = true;
		break;
		case "3":
			s_progress = txt_progress_build_all_team;
			progress_active = true;
		break;	
		case "4":
			s_progress = txt_progress_build_all_country;
			progress_active = true;
		break;			
		default:
			s_progress = "";
	}
	
	$('#text_status_progress').html(s_progress);
	$('#text_status_progress_start').html(s_progress_time);
	$('#text_status_progress_duration').html(data.progress_duration);		
	$('#text_status_progress_error').html(data.progress_error);	

	$('#text_status_start').html(PrintFullTime(data.user_team_time));	
	
	$('#text_status_user_start').html(PrintFullTime(data.user_start_time));	
	$('#text_status_user_file_duration').html(data.user_file_read_duration);		
	$('#text_status_user_processed_duration').html(data.user_processed_duration);	
	$('#text_status_user_added_duration').html(data.user_add_duration);	
	$('#text_status_user_url_time').html(PrintFullTimeUTC(data.user_url_file_time*3600));	

	$('#text_status_team_start').html(PrintFullTime(data.team_start_time));	
	$('#text_status_team_file_duration').html(data.team_file_read_duration);
	$('#text_status_team_processed_duration').html(data.team_processed_duration);	
	$('#text_status_team_added_duration').html(data.team_add_duration);	
	$('#text_status_team_url_time').html(PrintFullTimeUTC(data.team_url_file_time*3600));	

	$('#text_error_total').html(data.u_t_total_error_count);		
	
	var error_user = data.users_error_count;
	if (error_user === -1 || error_user === "-1")
	{
		error_user = txt_processing_users;
		busy = true;
	}
	$('#text_error_user').html(error_user);
	
	var error_team = data.team_error_count;	
	if (error_team === -1 || error_team === "-1")
	{
		error_team = txt_processing_teams;
		busy = true;
	}	
	$('#text_error_team').html(error_team);	
}

function DatabaseZeroDayStatus()
{
	g_database_zero_day_status_timer_count = 10000;
	DatabaseStatusZeroRead(DatabaseZeroDayStatusReadReady);
}

function DatabaseStatusZeroRead(readyFunction)
{
	var id = 0;
	var time = 0;	
	var token = "read";	
	var jsonString = '"action":"'+ ACTION_READ_DATABASE_ZERO_DAY_STATUS + '", "id":"' + id + '","time":"' + time + '","token":"' + token + '"';
	jsonString = jsonString + "}";
	jsonString = "{" + jsonString;
	phpSendData(jsonString, readyFunction);	
}

function DatabaseZeroDayStatusReadReady(result)
{
	g_database_zero_day_status_timer_count = 4;	
	try{
		var json_array = JSON.parse(result);
    }catch(e){
        return;	// error
    }

	var item = json_array.shift();
	if (item !== "data") return;
	item = json_array.shift();	
	if (item !== "ok") return;	
	
	DatabaseZeroDayPrintStatus(json_array);	
}

function DatabaseZeroDayPrintStatus(json_array)
{
//duration
//error_count
//last_fetch_time
//processed_count
//start_time
//status
//user_name

	var status;
	var status_text;
	var data = json_array[0];

	$('#text_zero_status_start').html(PrintFullTime(data.start_time));
	$('#text_zero_status_fetch').html(PrintFullTime(data.last_fetch_time));		
	$('#text_zero_status_duration').html(data.duration);
	
	status = data.status;
	status_text = txt_processing_zero_wait;
	if (status === "1") status_text = txt_processing_zero_run;
	$('#text_zero_status').html(status_text);	
	
	$('#text_zero_status_user').html(data.user_name);
	$('#text_zero_status_processed').html(data.processed_count);
	
	status = data.error_count;
	if (status === "-1") status_text = "";
	else status_text = status;
	
	$('#text_zero_error').html(status_text);
}	


function PrintFullTime(timestamp)
{
	timestamp *= 1000;
	var date = new Date (timestamp);
	return date.toLocaleString();
}

function PrintFullTimeUTC(timestamp)
{
	timestamp *= 1000;
	var date = new Date (timestamp);
	return date.toUTCString();
}

//"	["database","ok","1403155021","1403155021","0 H, 1 M, 50 S","","0 H, 0 M, 13 S","389756","1403155779","0 H, 0 M, 8 S","0 H, 1 M, 28 S","0 H, 12 M, 55 S","389757","0","0","0"]"