LANGUAGE_ENGLISH = "US";
LANGUAGE_DUTCH = "NL";

g_language = LANGUAGE_ENGLISH;

function language()
{
	switch(g_language) 
	{
		case LANGUAGE_ENGLISH:
			languageEnglish();
		break;
		case LANGUAGE_DUTCH:
			languageDutch();
		break;		
	}	
}

function languageEnglish()
{
	table_team_rac = "Team RAC";
	table_team_total = "Team Total";

	table_name = "Name";
	table_total = "Total";	
	table_rac = "RAC";
	table_country = "Country";
	table_members = "Members";	
	
    table_country_rac = "Country RAC";
    table_country_total	= "Country Total";
    table_world_rac = "World RAC";
    table_world_total = "World Total";
	table_team = "Team";
	
	table_lang_file = "";
	
//	check_hide_table = "Hide table";
	
	text_solid_line = "Solid line: ";
	text_dash_line = "Dashed lijn: ";
	text_cedit_future = "Credits in the future ";
	text_tool_users = "'Show user list'";
	text_one_team = "Team: ";
	text_all_teams = "All teams";
	text_all_users = "All users";	
	text_all_countries = "All countries";
	text_one_country = "Country: ";	
        text_table_info = "Info";
        text_table_status = "Status";
	
	txt_stat_head_zero = "Zero Day update";
	txt_stat_head_all = "Daily update";
	txt_stat_head_user = "Daily user update";
	txt_stat_head_team = "Daily team update";
	txt_stat_head_post = "Post processing";
	
	txt_stat_started = "Started on:";
	txt_stat_processed = "Processed in:";
	txt_stat_status = "State:";
	txt_stat_user = "User:";
	txt_stat_processed_nr = "Processed:";
	txt_stat_processed_last = "Last processed on:";
	txt_stat_error_zero	= "Errors:";
	txt_stat_checked_time = "Last checked on:";
	txt_stat_error_user = "Error/ status user:";
	txt_stat_error_team = "Error/ status team:";
	txt_stat_error_all = "Error total:";
	txt_stat_data_read = "Data read in:";
	txt_stat_data_processed = "Data processed in:";
	txt_stat_data_added = "Data added in";
	txt_stat_data_time = "Data time stamp:";
	
	txt_processing_zero_wait = "Waiting for the next update";
	txt_processing_zero_run = "Running";
	
	txt_processing_users = "Processing users";
	txt_processing_teams = "Processing teams";
	
	txt_stat_post = "To do:";
	txt_stat_busy = "Now processing:";
	
	txt_update_status1 = "Status will be automatically updated in ";
	txt_update_stauts2 = " seconds";
	txt_update_now = "Updating ...";
	
	txt_todo_gen_lists = "Generate new lists";
	txt_progress_snl = "Building S@NL list";
	txt_progress_build_users = "Building users list for: ";
	txt_progress_build_all_team = "Building all users list";
	txt_progress_build_all_country = "Building country list";
	
	text_graph_first_time = "<b>Click on one of the rows to show graphs</b>";
	
}
function languageDutch()
{
	table_team_rac = "Team RAC";
	table_team_total = "Team Totaal";	

	table_name = "Naam";
	table_total = "Totaal";	
	table_rac = "RAC";	
	table_country = "Land";
	table_members = "Leden";
	
    table_country_rac = "L RAC";
    table_country_total	= "L Total";
    table_world_rac = "W RAC";
    table_world_total = "W Total";
	table_team = "Team";	
	
	table_lang_file = "/stats/js/lib/data_tables/lang/nl.txt";	
	
//	check_hide_table = "Verberg tabel";	
	
	text_solid_line = "Doorgetrokken lijn: ";
	text_dash_line = "Dashed lijn: ";
	text_cedit_future = "Credits in de toekomst ";
	text_tool_users = "Toon gebruikers lijst";
	text_one_team = "Team: ";	
	text_all_teams = "Alle teams";
	text_all_users = "Alle gebruikers";
	text_all_countries = "Alle landen";
	text_one_country = "Land: ";	
        text_table_info = "Info";        
        text_table_status = "Status";
	
	txt_stat_head_zero = "Status Zero Day update";
	txt_stat_head_all = "Status dagelijkse update";
	txt_stat_head_user = "Dagelijkse update gebruikers";
	txt_stat_head_team = "Dagelijkse update team";
	txt_stat_head_post = "Nabewerkingen";
	
	txt_stat_started = "Gestart:";
	txt_stat_processed = "Verwerkingstijd:";
	txt_stat_status = "Status:";
	txt_stat_user = "Gebruiker:";
	txt_stat_processed_nr = "Verwerkt:";
	txt_stat_processed_last = "Laatst verwerk:";
	txt_stat_error_zero	= "Fouten:";
	txt_stat_checked_time = "Laatste controle:";
	txt_stat_error_user = "Status / fouten gebruiker:";
	txt_stat_error_team = "Status / fouten team:";
	txt_stat_error_all = "Fouten totaal:";
	txt_stat_data_read = "Data gelezen in:";
	txt_stat_data_processed = "Data verwerkt in:";
	txt_stat_data_added = "Data toegevoegd in";
	txt_stat_data_time = "Data van:";
	
	txt_processing_zero_wait = "Wacht op de volgende update";	
	txt_processing_zero_run = "Loopt nu";	
	
	txt_processing_users = "Bezig met het verwerken gebruikers";
	txt_processing_teams = "Bezig met het verwerken teams";	
	
	txt_stat_post = "Nog te doen:";
	txt_stat_busy = "Nu bezig met:";

	txt_update_status1 = "Onderstaande info wordt over";
	txt_update_stauts2 = "seconden ververst";
	txt_update_now = "Ververs ...";	
	txt_todo_gen_lists = "Genereer nieuwe lijsten";	
	txt_progress_snl = "Lijst S@NL wordt gemaakt";
	txt_progress_build_users = "Lijst van gebruikers wordt gemaakt voor ";	
	txt_progress_build_all_team = "Lijst alle teams wordt gemaakt";
	txt_progress_build_all_country = "Lijst alle landen wordt gemaakt";	
	
	text_graph_first_time = "<b>Klik op een van de rijen om de grafiek te laten zien</b>";	
}

