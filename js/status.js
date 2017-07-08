$(document).ready(function()
{
	language();	
	getSiteUrl();
	
        $( "#button_home" ).click(function()
	{
		location.href="index.php";
	}); 
        
        
	if (g_siteUrl === "")
	{
		$('#text_status_error').html("g_siteUrl not found");
	}

	$('#table_status_zero th').eq(1).html(txt_stat_head_zero);
	$('#table_status_zero td').eq(0).html(txt_stat_started);	
	$('#table_status_zero td').eq(2).html(txt_stat_processed);	
	$('#table_status_zero td').eq(4).html(txt_stat_status);	
	$('#table_status_zero td').eq(6).html(txt_stat_user);		
	$('#table_status_zero td').eq(8).html(txt_stat_processed_nr);		
	$('#table_status_zero td').eq(10).html(txt_stat_processed_last);		
	$('#table_status_zero td').eq(12).html(txt_stat_error_zero);		
	
	$('#table_status_all th').eq(1).html(txt_stat_head_all);
	$('#table_status_all td').eq(0).html(txt_stat_checked_time);	
	$('#table_status_all td').eq(2).html(txt_stat_error_user);	
	$('#table_status_all td').eq(4).html(txt_stat_error_team);	
	$('#table_status_all td').eq(6).html(txt_stat_error_all);	
	
	$('#table_status_user th').eq(1).html(txt_stat_head_user);	
	$('#table_status_user td').eq(0).html(txt_stat_processed_last);		
	$('#table_status_user td').eq(2).html(txt_stat_data_read);			
	$('#table_status_user td').eq(4).html(txt_stat_data_processed);			
	$('#table_status_user td').eq(6).html(txt_stat_data_added);		
	$('#table_status_user td').eq(8).html(txt_stat_data_time);			
	
	$('#table_status_team th').eq(1).html(txt_stat_head_team)
	$('#table_status_team td').eq(0).html(txt_stat_processed_last);		
	$('#table_status_team td').eq(2).html(txt_stat_data_read);			
	$('#table_status_team td').eq(4).html(txt_stat_data_processed);			
	$('#table_status_team td').eq(6).html(txt_stat_data_added);		
	$('#table_status_team td').eq(8).html(txt_stat_data_time);		
	
	$('#table_status_post th').eq(1).html(txt_stat_head_post);	
	$('#table_status_post td').eq(0).html(txt_stat_post);
	
	$('#table_status_post td').eq(2).html(txt_stat_processed_last);
	$('#table_status_post td').eq(4).html(txt_stat_data_processed);	
	$('#table_status_post td').eq(6).html(txt_stat_busy);			
	$('#table_status_post td').eq(8).html(txt_stat_error_all);	

	DatabaseStatusStartTimer();
});