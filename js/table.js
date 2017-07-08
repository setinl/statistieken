//ACTION_READ_TABLE_TEAM_SNL = 100;

var CLASS_SELECTED = 'selected';

var TABLE_USER = "USER";
var TABLE_TEAMS_ALL = "TEAM_ALL";
var TABLE_USERS_ALL = "USERS_ALL";
var TABLE_COUNTRIES_ALL = "COUNTRIES_ALL";
var TABLE_COUNTRY = "COUNTRY";

var SORTING_TABLE_TEAM = 0;
var SORTING_TABLE_USER = 1;
var SORTING_TABLE_USER_ALL = 2;
var SORTING_TABLE_COUNTRIES_ALL = 3;
var SORTING_TABLE_COUNTRIES = 4;

var PARA_TEAM = "team";
var PARA_TEAM_NAME = "team_name";
var PARA_TEAMS_ALL = "all_teams";
var PARA_USERS_ALL = "all_users";
var PARA_COUNTRIES_ALL = "all_countries";
var PARA_COUNTRY = "country";

var PARA_PAGE_LENGTH = "page_len";
var PARA_PAGE_NR = "page_nr";
    
var PARA_SELECT = "sel";

var PARA_ORDER = "ord_col";
var PARA_ORDER_DIR = "ord_dir";

    
var COLUMN_TEAM = 0;
var COLUMN_ALL_TEAM = 1;
var COLUMN_COUNTRY = 2;
var COLUMN_USER  = 3;

var TEAM_SNL = "30190";

var g_siteUrl;
var g_hide_table = false;

var g_selected = [];
var g_table;

var g_order = -1;
var g_order_dir = "";

var g_initSorting = true;

var g_table_interval_timer = null;
var g_current_table = null;

var g_team_id;
var g_team_name;
var g_team_same = false;
var g_table_type = TABLE_TEAMS_ALL;
var g_page_length = 10;

var g_page_nr = null;
var g_page_nr_type = TABLE_TEAMS_ALL;

var g_location_base = "";

$(document).ready(function() {
    UrlChange();
    
    window.onpopstate = function (event) {
        UrlChange();
    };
} );

function UrlChange()
{
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
    
    
  	$( "#button_language" ).click(function()
	{
		languageToggle();
//		return;
	});    
	$( "#button_all_teams" ).click(function()
	{
            if (g_table_type == TABLE_TEAMS_ALL) return;               
            g_table_type = TABLE_TEAMS_ALL;
            g_page_nr = null;
            removeSelected();
            setParameters()
            UrlChange();
 //           return;
	}); 
	$( "#button_all_users" ).click(function()
	{
            if (g_table_type == TABLE_USERS_ALL) return;
            g_table_type = TABLE_USERS_ALL;
            g_page_nr = null;
            removeSelected();
            setParameters();
            UrlChange();            
 //           return;
	}); 
	$( "#button_all_countries" ).click(function() {
            if (g_table_type == TABLE_COUNTRIES_ALL) return;
            g_table_type = TABLE_COUNTRIES_ALL;
            g_page_nr = null;
            removeSelected();
            setParameters();
            UrlChange();            
 //           return;
	}); 	

	$( "#button_info" ).click(function() {
            showSelectedInfo();            
 //           return;
	});
        
	$( "#button_status" ).click(function() {
            window.open(g_siteUrl+'status.php', '_blank');
 //           return;
	});        
        
        
    
  
	$( "#text_solid_line" ).html(text_solid_line);	
	$( "#text_dash_line" ).html(text_dash_line);		
	$( "#text_cedit_future" ).html(text_cedit_future);	
	$( "#text_all_teams" ).html(text_all_teams);		
	$( "#text_all_users" ).html(text_all_users);
	$( "#text_table_info" ).html(text_table_info);
	$( "#text_table_status" ).html(text_table_status);        
	$( "#text_all_countries" ).html(text_all_countries);		

   	$( "#text_credits" ).html(text_credits);
	$( "#text_credits_future" ).html(text_credits_future);        
    
    
    
	getSiteUrl();
    
    $('#button').click( function () {
        table.row('.'+CLASS_SELECTED).remove().draw( false );
    } );    
    
	hideTables();
	hideGraphs();

	switchMode(g_table_type);

	tableCheckStartTimer();
	
//	helpText(1);
}


function getUrlData()
{
    // first # rest with &
	var loc = decodeURIComponent(window.location.href);
        g_location_base = loc;
        var len, para,data,i, sel, selstr, iPos;
        var iTeam = 0;
        var order = -1, order_dir = "";
        
        selstr = PARA_SELECT+'0=';
        
   	if(loc.length > 0)
	{
           para = loc.split("#");
           if (para.length < 2) return; // no parameters
           data = para[1].split("&");
           len = data.length;
           for (i=0; i < len; i++)
           {
               iPos = data[i].lastIndexOf(PARA_TEAM+'=');
               if (iPos === 0)
               {
                   if (iTeam == 0)
                   {
                        g_team_id = data[i].substr(PARA_TEAM.length+1);
                        g_table_type = TABLE_USER;
                        iTeam++;
                   }
                   else
                   {
                       g_team_name = decodeURIComponent(data[i].substr(PARA_TEAM.length+1));    // older 2x team new team + team name
                   }
                }
  		iPos = data[i].lastIndexOf(PARA_TEAM_NAME+'=');
		if (iPos === 0)
		{
                    g_team_name = decodeURIComponent(data[i].substr(PARA_TEAM_NAME.length+1));
                }                 
                iPos = data[i].lastIndexOf(PARA_TEAMS_ALL);
		if (iPos === 0)
		{
                    g_team_id = data[i].substr(PARA_TEAM.length);
                    g_table_type = TABLE_TEAMS_ALL;
		}                
		iPos = data[i].lastIndexOf(PARA_USERS_ALL);
		if (iPos === 0)
		{
                    g_team_id = data[i].substr(PARA_USERS_ALL.length);
                    g_table_type = TABLE_USERS_ALL;
		}                
		iPos = data[i].lastIndexOf(PARA_COUNTRIES_ALL);
		if (iPos === 0)
		{
                    g_table_type = TABLE_COUNTRIES_ALL;
		}             
  		iPos = data[i].lastIndexOf(PARA_COUNTRY+'=');
		if (iPos === 0)
		{
                    g_country_id = data[i].substr(PARA_COUNTRY.length+1);
                    g_table_type = TABLE_COUNTRY;
                } 
  		iPos = data[i].lastIndexOf(PARA_PAGE_LENGTH+'=');
		if (iPos === 0)
		{
                    g_page_length = data[i].substr(PARA_PAGE_LENGTH.length+1);
                    setPageLength();
                }
  		iPos = data[i].lastIndexOf(PARA_PAGE_NR+'=');
       		if (iPos === 0)
		{
                    g_page_nr = data[i].substr(PARA_PAGE_NR.length+1);
                    setPageNr();
                }
    		iPos = data[i].lastIndexOf(PARA_ORDER+'=');
       		if (iPos === 0)
		{
                    order = data[i].substr(PARA_ORDER.length+1);
                    setPageNr();
                }
    		iPos = data[i].lastIndexOf(PARA_ORDER_DIR+'=');                
       		if (iPos === 0)
		{
                    order_dir = data[i].substr(PARA_ORDER_DIR.length+1);
                    setPageNr();
                }                
                
                // selections last
                iPos = data[i].lastIndexOf(selstr);            
                if (iPos === 0)            
                {
                    g_selected.push(data[i].substr(selstr.length));
                    for (sel=1;sel<len-i;sel++)
                    {
                        selstr = PARA_SELECT+sel+'=';
                        iPos = data[i+sel].lastIndexOf(selstr);
                        if (iPos === 0)
                        {
                            g_selected.push(data[i+sel].substr(selstr.length));
                            var iii = 1;
                        }
                        else break;
                    }
                }
           }
        }
        
        if ((order.length > 0) && (order_dir.length >0))
        {
            if (order > 0)
            {
                g_order = order;
                g_order_dir = order_dir;
            }
        }
        else
        {
            g_order = -1;
        }
}


function setParameters()
{  
    var queries = {};
    var i, len, id, table, orderArray, orderArray2, order, orderDir;

    switch(g_table_type)
    {
        case TABLE_USER:
            queries[PARA_TEAM]=g_team_id;
            queries[PARA_TEAM_NAME]=encodeURIComponent(g_team_name);
        break;
        case TABLE_USERS_ALL:
            queries[PARA_USERS_ALL]='1';
        break;        
        case TABLE_COUNTRIES_ALL:
            queries[PARA_COUNTRIES_ALL]='1';
            id = PARA_COUNTRIES_ALL;
        break;        
        case TABLE_COUNTRY:
            queries[PARA_COUNTRY]='1';
        break;
        default: 
            queries[PARA_TEAMS_ALL]='1';        
    }
    
    queries[PARA_PAGE_LENGTH]=g_page_length;

    if (g_page_nr != null)
    {
        queries[PARA_PAGE_NR]=g_page_nr; 
    }
    
    var len = g_selected.length;
    for (i=0; i<len; i++)
    {
        sel = g_selected[i];
        queries[PARA_SELECT+i] = sel;      
    }
    
    id = getTypeId();
    table = getTable(id);
    if (!isUndefined(table))
    {
        orderArray = table.order();
        if (orderArray.length > 0)
        {
            if (Array.isArray(orderArray[0]))
            {
                orderArray2 = orderArray[0];            
                orderArray = orderArray2;
            }
            order = orderArray[0];        
            orderDir = orderArray[1];
            queries[PARA_ORDER] =  order;
            queries[PARA_ORDER_DIR] = orderDir;
        }
    }
    
    
    history.pushState({}, '', "#"+$.param(queries));
    
    
  /*  
	var loc_new = "";
	var loc = window.location.href;
	if(loc.length > 0)
	{
		var loc_split = loc.split("#");
		loc_new = loc_split[0];
	}
	loc_new += "#"+encodeURIComponent(paraAdd1);
	if (paraAdd2.length > 0)
	{
		loc_new += "="+encodeURIComponent(paraAdd2);
                if (paraAdd3.length > 0)
                {
                    loc_new += "&"+encodeURIComponent(paraAdd1);
                    loc_new += "="+encodeURIComponent(paraAdd3);
                }                  
	}     
               
	window.location.href = loc_new;
        g_location_base = loc_new;
	location.reload();
*/        
}

function getTypeId()
{
   switch(g_table_type)
    {
        case TABLE_USER:
            id = '#seti_table_user';
        break;
        case TABLE_USERS_ALL:
            id = '#seti_table_users_all';
        break;        
        case TABLE_COUNTRIES_ALL:
            id = '#seti_table_countries_all';
        break;        
        case TABLE_COUNTRY:
            id = '#seti_table_country';
        break;
        default: 
            id = '#seti_table_teams_all';
  
    }
    return id;
}

function getTable(id)
{
    var table;

    if ($.fn.DataTable.isDataTable(id) )
    {   
        table = $(id).DataTable();
        return table;
    }
    return null;
}

function changedLength()
{
    removeSelected();    
    setParameters();
}

function setPageLength()
{
   var id, table;
   id = getTypeId();   
   table = getTable(id);
   if (table == null) return;
    var info = table.page.info();
    if (info.length != g_page_length)
    {
        table.page.len(g_page_length).draw();
    }  
}

function setPageUrl(id)
{
    id = getTypeId();      
    var table = getTable(id);
    if (table !== null)
    {
        var info = table.page.info();
        g_page_nr = info.page+1;
    }
    removeSelected();    
    setParameters();    
}

function changedSorting(table,col,dir)
{
    if (g_initSorting){
        g_initSorting = false;
        return;
    }  //skip the first one
    
    storeSorting(table,col,dir);
    removeSelected();
    setParameters();
}

function setPageNr()
{
    
}

function removeSelected()
{
    g_selected = [];    // empty selected array

    var id = getTypeId();
    $(id+ ' tbody tr').removeClass(CLASS_SELECTED);
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
//		if ($(this).hasClass("at"))         g_select_column = COLUMN_ALL_TEAM;
		if ($(this).hasClass("team"))       g_select_column = COLUMN_TEAM;
//		if ($(this).hasClass("cm"))         g_select_column = COLUMN_COUNTRY;
//		if ($(this).hasClass("user_all"))   g_select_column = COLUMN_USER
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
			setParameters();
			return;
		}
		if (g_select_column === COLUMN_ALL_TEAM)
		{
			g_table_type = TABLE_TEAMS_ALL;
			switchMode();	
			setParameters();
			return;
		}
		if (g_select_column === COLUMN_COUNTRY)
		{
			g_table_type = TABLE_COUNTRY;
			g_country_id = id;
			switchMode();	
			setParameters();
			return;
		}		
		
        var index = $.inArray(id, g_selected);
 
        if ( index === -1 )
        {
            g_selected.push( id );
        }
        else
        {
            g_selected.splice( index, 1 );
        }
 
        setParameters();
 
        $(this).toggleClass(CLASS_SELECTED);
        processGraph(id,name);
	
    } ); 	
}

function showSelectedInfo()
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
    
    
    // find first selected item
    table.$('tr').each(function()
    {
	var str = this.className;
	if (str.indexOf("selected") > 0)
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
            var text = "";
            text = text + id + '<br>';
            text = text + name + '<br>';           
            text += '<br>';
            $('#seti_info_field').html(text);        
	}
    });    
}

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
               	case "PA": flag = "flag-pa";
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
            flags = country+"  <img src='js/lib/flags/blank.png' class='flag "+ flag + "'/>";
	}
	$('td', row).eq(i_cell).html(flags);	// 5		
}

function addComma(data, row, i_cell)
{
	data_c = data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "`");
	$('td', row).eq(i_cell).html(data_c);
}