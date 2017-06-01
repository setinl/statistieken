//ACTION_READ_TABLE_TEAM_SNL = 100;

TABLE_USER = "USER";
TABLE_TEAMS_ALL = "TEAM_ALL";
TABLE_USERS_ALL = "USERS_ALL";
TABLE_COUNTRIES_ALL = "COUNTRIES_ALL";
TABLE_COUNTRY = "COUNTRY";

PARA_TEAM = "team";
PARA_TEAMS_ALL = "all_teams";
PARA_USERS_ALL = "all_users";
PARA_COUNTRIES_ALL = "all_countries";
PARA_COUNTRY = "country";

COLUMN_TEAM = 0;
COLUMN_ALL_TEAM = 1;
COLUMN_COUNTRY = 2;

var g_siteUrl;
var g_hide_table = false;

var selected = [];
var g_table;


var g_table_interval_timer = null;
var g_current_table = null;

var g_team_id;
var g_team_same = false;
var g_table_type = TABLE_TEAMS_ALL;

$(document).ready(function() {
  
	getUrlData();
 
	language();
 
 
    $('[id^="checkbox"]').change(function(event)
	{
//		if (this.id === "checkbox_hide")
//		{
//			if ($(this).is(":checked"))
//			{
//				$( "#seti_div_table" ).hide();
//				g_hide_table = true;				
//			}
//			else
//			{
//				$( "#seti_div_table" ).show();		
//				g_hide_table = false;		
//			}
//		}
		if (this.id === "checkbox_time")
		{
			if ($(this).is(":checked"))
			{
				$( "#graph_credit" ).hide();
				$( "#graph_credit_text" ).hide();
				$( "#graph_credit_tm" ).show();
				$( "#graph_credit_tm_text" ).show();
				g_time_machine = true;	
			}
			else
			{
				$( "#graph_credit" ).show();
				$( "#graph_credit_text" ).show();		
				$( "#graph_credit_tm_text" ).hide();				
				$( "#graph_credit_tm" ).hide();
				g_time_machine = false;
			}
			rebuildGraphs();
		}
    }); 
	$( "#graph_credit_tm_text" ).hide();				
	$( "#graph_credit_tm" ).hide();	
  
	$('[name ="checkbox1"]').change(function(event) {
  //      console.log('a');
        if ($(this).is(":checked")) {
  //           $(this).closest('tr').addClass('selected');
        }
        else {
 //            $(this).closest('tr').removeClass('selected');
        }
    });  
  
	$( "#button_all_teams" ).click(function()
	{
		g_table_type = TABLE_TEAMS_ALL;
		switchMode();
//		addParameter(PARA_TEAMS_ALL,"");
		return;
	}); 
	$( "#button_all_users" ).click(function()
	{
		g_table_type = TABLE_USERS_ALL;
		switchMode();
	//	addParameter(PARA_USERS_ALL,"");
		return;
	}); 
	$( "#button_all_countries" ).click(function() {
//		addParameter(PARA_COUNTRIES_ALL,"");
		g_table_type = TABLE_COUNTRIES_ALL;
		switchMode();
		return;
	}); 	
	
  
//	$( "#text_hide_table" ).html(check_hide_table);
	$( "#text_solid_line" ).html(text_solid_line);	
	$( "#text_dash_line" ).html(text_dash_line);		
	$( "#text_cedit_future" ).html(text_cedit_future);	
	$( "#text_all_teams" ).html(text_all_teams);		
	$( "#text_all_users" ).html(text_all_users);	
	$( "#text_all_countries" ).html(text_all_countries);		
	
	getSiteUrl();
    
    $('#button').click( function () {
        table.row('.selected').remove().draw( false );
    } );    
    
	hideTables();
	hideGraphs();

	switchMode(g_table_type);

	tableCheckStartTimer();
	
//	helpText(1);

} );

function getUrlData()
{
	var loc = window.location.href;
	if(loc.length > 0)
	{
		var para = loc.split("#");
		for (var i=1; i < para.length; i++)
		{
			var data = para[i];
			var iPos = data.lastIndexOf(PARA_TEAM+'=');
			if (iPos === 0)
			{
				g_team_id = data.substr(PARA_TEAM.length+1);
				g_table_type = TABLE_USER;
			}
			var iPos = data.lastIndexOf(PARA_TEAMS_ALL);
			if (iPos === 0)
			{
				g_team_id = data.substr(PARA_TEAM.length);
				g_table_type = TABLE_TEAMS_ALL;
			}			
			var iPos = data.lastIndexOf(PARA_USERS_ALL);
			if (iPos === 0)
			{
				g_team_id = data.substr(PARA_USERS_ALL.length);
				g_table_type = TABLE_USERS_ALL;
			}			
			var iPos = data.lastIndexOf(PARA_COUNTRIES_ALL);
			if (iPos === 0)
			{
				g_table_type = TABLE_COUNTRIES_ALL;
			}				
			var iPos = data.lastIndexOf(PARA_COUNTRY+'=');
			if (iPos === 0)
			{
				g_country_id = data.substr(PARA_COUNTRY.length+1);
				g_table_type = TABLE_COUNTRY;
			}			
		}
	}
}


function addParameter(paraAdd1, paraAdd2)
{
	var loc_new = "";
	var loc = window.location.href;
	if(loc.length > 0)
	{
		var loc_split = loc.split("#");
		loc_new = loc_split[0];
	}
	loc_new += "#"+paraAdd1;
	if (paraAdd2.length > 0)
	{
		loc_new += "="+paraAdd2;
	}
	
	window.location.href = loc_new;
	location.reload();
}

function switchMode()
{
	hideTables();
	
	switch (g_table_type)
	{
		case TABLE_USER:
			if (!g_table_user_init)
			{
				g_current_table = tableUser(g_team_id, g_team_name);
				capture_u = new captureTableSelections("seti_table_user", g_table_user);			
			}
			else
			{
				if (!g_team_same) tableUserSet(g_team_id, g_team_name);
			}			
			g_table_user_init = true;
			$( "#seti_table_user_sh" ).show();
			g_current_table = g_table_user;
		break;
		case TABLE_USERS_ALL:
			if (!g_table_users_all_init)
			{
				g_current_table = tableUsersAll();
				capture_tua = new captureTableSelections("seti_table_users_all",g_table_users_all);				
			}
			else
			{
				tableUsersAllHeader();
			}
			g_table_users_all_init = true;
			$( "#seti_table_users_all_sh" ).show();	
			g_current_table = g_table_users_all;
		break
		case TABLE_COUNTRIES_ALL:
			if (!g_table_countries_all_init)
			{
				tableCountriesAll();
				capture_ca = new captureTableSelections("seti_table_countries_all",g_table_countries_all);				
			}
			else
			{
				tableCountriesAllHeader();
			}
			g_table_countries_all_init = true;
			$( "#seti_table_countries_all_sh" ).show();	
			g_current_table = g_table_countries_all;
		break
		case TABLE_COUNTRY:
			if (!g_table_country_init)
			{
				tableCountry(g_country_id);
				capture_c = new captureTableSelections("seti_table_country",g_table_country);
			}
			else
			{
				tableCountryHeader(g_country_id);				
			}
			g_table_country_init = true;
			$( "#seti_table_country_sh" ).show();			
			g_current_table = g_table_country;
		break;
		default:
			if (!g_table_teams_all_init)
			{
				tableTeamsAll();				
				capture_ta = new captureTableSelections("seti_table_teams_all", g_table_teams_all);
			}
			else
			{
				tableTeamsAllHeader();
			}
			g_table_teams_all_init = true;			
			$( "#seti_table_teams_all_sh" ).show();
			g_current_table = g_table_teams_all;
	}
	rebuildGraphs();
}

function hideTables()
{
	$( "#seti_table_teams_all_sh" ).hide();
	$( "#seti_table_user_sh" ).hide();	
	$( "#seti_table_user_sh" ).hide();	
	$( "#seti_table_users_all_sh" ).hide();	
	$( "#seti_table_countries_all_sh" ).hide();	
	$( "#seti_table_country_sh" ).hide();		
}

function captureTableSelections(item, table)
{	
	item_text = "#"+item+" tbody";
	
	$(item_text).on('click', 'td', function ()
	{
		g_select_column = -1 ;
		if ($(this).hasClass("at"))	g_select_column = COLUMN_ALL_TEAM;
		if ($(this).hasClass("tm"))	g_select_column = COLUMN_TEAM;
		if ($(this).hasClass("cm"))	g_select_column = COLUMN_COUNTRY;
	} );
	
    $(item_text).on('click', 'tr', function ()
	{
		showGraphs();
		if (table.fnGetData === undefined)	// why??????????
		{
			var this_row = table.row(this);		
			var this_item = this_row.data();
			
		}
		else
		{
			var this_item = table.fnGetData(this);
		}
			
		var id = this_item[0];
		var name = this_item[1];
  
		if (g_select_column === COLUMN_TEAM)
		{
			g_table_type = TABLE_USER;
			if (g_team_id === id) g_team_same = true;
			else g_team_same = false;
			
			g_team_id = id;
			g_team_name = name;
			switchMode();
		//	addParameter(PARA_TEAM, id);
			return;
		}
		if (g_select_column === COLUMN_ALL_TEAM)
		{
			g_table_type = TABLE_TEAMS_ALL;
			switchMode();	
		//	addParameter(PARA_TEAMS_ALL,"");
			return;
		}
		if (g_select_column === COLUMN_COUNTRY)
		{
			g_table_type = TABLE_COUNTRY;
			g_country_id = id;
			switchMode();	
//			addParameter(PARA_COUNTRY, id);
			return;
		}		
		
        var index = $.inArray(id, selected);
 
        if ( index === -1 ) selected.push( id );
        else selected.splice( index, 1 );
 
        $(this).toggleClass('selected');

		processGraph(id,name);
	
    } ); 	
}

/*
function captureTableSelections()
{	
	$('#tbody').on('click', 'td', function () {
		g_select_column = -1 ;
		if ($(this).hasClass("at"))	g_select_column = COLUMN_ALL_TEAM;
		if ($(this).hasClass("tm"))	g_select_column = COLUMN_TEAM;
		if ($(this).hasClass("cm"))	g_select_column = COLUMN_COUNTRY;
	} );	
	
	$('#seti_table tbody').on('click', 'td', function () {
		g_select_column = -1 ;
		if ($(this).hasClass("at"))	g_select_column = COLUMN_ALL_TEAM;
		if ($(this).hasClass("tm"))	g_select_column = COLUMN_TEAM;
		if ($(this).hasClass("cm"))	g_select_column = COLUMN_COUNTRY;
	} );
	
    $('#seti_table tbody').on('click', 'tr', function () {
  		var this_item = g_table.fnGetData(this);
		var id = this_item[0];
		var name = this_item[1];
  
		if (g_select_column === COLUMN_TEAM)
		{
			addParameter(PARA_TEAM, id);
			return;
		}
		if (g_select_column === COLUMN_ALL_TEAM)
		{
			addParameter(PARA_TEAMS_ALL,"");
			return;
		}
		if (g_select_column === COLUMN_COUNTRY)
		{
			addParameter(PARA_COUNTRY, id);
			return;
		}		
		
        var index = $.inArray(id, selected);
 
        if ( index === -1 ) selected.push( id );
        else selected.splice( index, 1 );
 
        $(this).toggleClass('selected');

		processGraph(id,name);
	
    } ); 	
}
*/

/*
function helpText(show)
{
	var help =	"Door op een of meerdere gebruikers te klikken worden de gebruikers grafieken getoond.<br>"+
				"Vink: 'Credits in de toekomst' aan om het Credit verloop in de volgende 3 jaar te zien.<br>"+
				"Je kunt de grafiek eenvoudig exporteren door op de 3 streepjes te drukken rechts boven in de grafiek.<br>"+
				"<br><br>";
				
				
	$("#help_text").html(help);
				
}
*/

////////////////////////////////////////////////// check table, if still selected and if the graphs still match

function tableCheckStartTimer()
{
	if (g_table_interval_timer === null)
	{
		g_table_interval_timer = setInterval('tableIntervalTimer()', 4000); // 4 sec / in mSec.
	}
}

function tableIntervalTimer()
{
	var bgetData = false;
	
	var table = g_current_table;
	
	var data_table;
	if (table.fnGetData === undefined)
	{
		data_table = table;
	}
	else
	{
		bgetData = true;
		data_table = table.fnGetData();	
	}

	table.$('tr').each(function()
	{
		var str = this.className;
		if (str.indexOf("selected") < 0)
		{
			if (bgetData === false)
			{
				var this_row = data_table.row(this);	
				var this_item = this_row.data();
				var id = this_item[0];
				var name = this_item[1];	
			}
			else
			{
				var table_index = this._DT_RowIndex;
				var id  = data_table[table_index][0];
				var name  = data_table[table_index][1];
			}

			g_graph.RemoveFromGraph(name);
		}
	});
}

function addUserColumn(data, i_row, i_cell)
{
	var div = "<div  class='b_gou ' title=" + text_tool_users + ">"+data+"</>";
	$('td', i_row).eq(i_cell).html(div);
}

function addTeamColumn(data, row, i_cell)
{
//	var img = data+" <img class='b_gog'/>";	
//	var div = "<div  class='b_gog ' title='Toon alle teams'>"+data+"</>";
//	$('td', row).eq(4).html(div);	
}

function addFlags(country, row, i_cell)
{
//	$('td', row).eq(5).html('<img src="blank.gif" class="flag flag-us"/>');
//	return;
	var flag = "";
	var flags = country;
	
	switch (country)
	{
		case "INT":		
		case "QY":
		case "QQ":			
			flags = "INT";
		break;
		case "AR":	flag = "flag-ar";
		break;		
		case "AT":	flag = "flag-at";
		break;			
		case "AU":	flag = "flag-au";
		break;	
		case "BA":	flag = "flag-ba";
		break;                
		case "BE":	flag = "flag-be";
		break;
		case "BG":	flag = "flag-bg";
		break;		
		case "BR":	flag = "flag-br";
		break;		
		case "CA": flag = "flag-ca";
		break;
		case "CH": flag = "flag-ch";
		break;
		case "CL": flag = "flag-cl";
		break;			
		case "CN": flag = "flag-cn";
		break;		
		case "CZ": flag = "flag-cz";
		break;
		case "DE": flag = "flag-de";
		break;
		case "DK": flag = "flag-dk";
		break;		
		case "EE": flag = "flag-ee";
		break;		
		case "EG": flag = "flag-eg";
		break;			
		case "ES": flag = "flag-es";
		break;		
		case "FI": flag = "flag-fi";
		break;		
		case "FR": flag = "flag-fr";
		break;		
		case "GB": flag = "flag-gb";
		break;
		case "GR": flag = "flag-gr";
		break;		
		case "GU": flag = "flag-gu";
		break;		
		case "HK": flag = "flag-hk";
		break;		
		case "HR": flag = "flag-hr";
		break;			
		case "HU": flag = "flag-hu";
		break;		
		case "IE":	flag = "flag-ie";
		break;
		case "IL":	flag = "flag-il";
		break;
		case "IN":	flag = "flag-in";
		break;			
		case "IS":	flag = "flag-is";
		break;		
		case "IT":	flag = "flag-it";
		break;
		case "JO":	flag = "flag-jo";
		break;			
		case "JP":	flag = "flag-jp";
		break;		
		case "KR":	flag = "flag-kr";
		break;		
		case "LT":	flag = "flag-lt";
		break;		
		case "LV":	flag = "flag-lv";
		break;			
		case "MX": flag = "flag-mx";
		break;		
		case "MY": flag = "flag-my";
		break;			
		case "NL": flag = "flag-nl";
		break;
		case "NO": flag = "flag-no";
		break;		
		case "NZ": flag = "flag-nz";
		break;			
		case "PL": flag = "flag-pl";
		break;		
		case "PT": flag = "flag-pt";
		break;
		case "RO":	flag = "flag-ro";
		break;	
		case "RS":	flag = "flag-rs";
		break;	
		case "RU":	flag = "flag-ru";
		break;
		case "SG":	flag = "flag-sg";
		break;		
		case "TH":	flag = "flag-th";
		break;			
		case "TZ":	flag = "flag-tz";
		break;		
		case "US":	flag = "flag-us";
		break;
		case "SE":	flag = "flag-se";
		break;
		case "SI":	flag = "flag-si";
		break;		
		case "SK":	flag = "flag-sk";
		break;		
		case "TT":	flag = "flag-tt";
		break;		
		case "TW":	flag = "flag-tw";
		break;
		case "UA":	flag = "flag-ua";
		break;
		case "VE":	flag = "flag-ve";
		break;		
		case "ZA":	flag = "flag-za";
		break;		
	}
	if (flag !== "")
	{
//		flags = country+"  <img src='/b/img/b.png' class='flag "+ flag + "'/>";
		flags = country+"  <img src='../js/lib/flags/blank.png' class='flag "+ flag + "'/>";
	}
	$('td', row).eq(i_cell).html(flags);	// 5		
}

function addComma(data, row, i_cell)
{
	data_c = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "`");
	$('td', row).eq(i_cell).html(data_c);
}