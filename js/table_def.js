
var g_table_teams_all_init		= false;
var g_table_user_init			= false;
var g_table_users_all_init		= false;
var g_table_countries_all_init          = false;
var g_table_country_init		= false;

function tableTeamsAllHeader()
{
	$( "#text_table" ).html(text_all_teams);		
}

function tableTeamsAll()
{
    var initialSelect = true;
    var id = '#seti_table_teams_all'
    tableTeamsAllHeader();

    $(id).on( 'order.dt', function (e, settings, len) {
        var col = len[0]['col'];
        var dir = len[0]['dir'];        
        changedSorting(SORTING_TABLE_TEAM,col,dir);
    } ); 
    
    $(id).on( 'length.dt', function ( e, settings, len ) {
        g_page_length = len;
        changedLength();
    } );
    
    $(id).on( 'page.dt', function () {
        setPageUrl(id);
        removeSelected();
    } );
    
    
    var def = Array([4, 'asc']);
    var sortingTable = getSorting(SORTING_TABLE_TEAM, def);
    var start;
    if (g_page_nr == null) start = 0;
    else 
    {
        start = g_page_length*(g_page_nr-1);
        if (start < 0) start = 0;
    }
//    var sortingTable = [[5, 'asc']];

    g_table_teams_all = $(id).dataTable( {
	"createdRow": function ( row, data, index )
	{
            addComma(data[2], row, 1);
            addComma(data[3], row, 2);	
            addFlags(data[6], row, 5);
            addUserColumn(data[7], row, 6);
        },		
        "serverSide": true,
        "ajax": g_siteUrl+"php/data_tables/server_list_all_teams.php",	
        "fnDrawCallback": function( oSettings ) {
            if (initialSelect)
            {
                initialSelect = false;
                addSelectedClass(); 
            }
        },	
        "aLengthMenu": [[10, 15, 20, 25, 50, 100, 200, 500], [10, 15, 20, 25, 50, 100, 200, 500]],
        "iDisplayLength": g_page_length,
        "displayStart": start,
        "processing": false,
	"bAutoWidth": false,
	"aoColumns": [
            { "sTitle": "Id", "visible": false, "searchable": false  },
            { "sTitle": table_name, "sClass": "right", "sWidth": "100px" },
            { "sTitle": table_total, "sClass": "right","sWidth": "40px"  },
            { "sTitle": table_rac, "sClass": "right","sWidth": "40px"  },
            { "sTitle": table_team_rac, "sClass": "right", "sWidth": "10px"  },
            { "sTitle": table_team_total, "sClass": "right","sWidth": "10px"},
            { "sTitle": table_country, "sClass": "right", "sWidth": "20px" },
            { "sTitle": table_members, "sClass": "right team", "sWidth": "10px" }			
	],
          "order": sortingTable //[[4, 'asc']]
//        "order": [4, 'asc']
    } );    
}

function tableUserHeader(team_id, team_name)
{
	$( "#text_table" ).html(text_one_team + team_name + " ("+ team_id+")");	
}

function tableUserSet(team_id, team_name)
{
	tableUserHeader(team_id, team_name);
                
	var team_url;
	if (team_id === TEAM_SNL) team_url = g_siteUrl + "php/data_tables/server_list_snl_team.php";
	else team_url = g_siteUrl + "php/data_tables/server_list_other_team.php";
	g_table_user.ajax.url(team_url);
	g_table_user.ajax.reload();
}

function tableUser(team_id, team_name)
{
    var initialSelect = true;
    tableUserHeader(team_id, team_name);
	
    var id = '#seti_table_user';
    $(id).on( 'order.dt', function (e, settings, len) {
        var col = len[0]['col'];
        var dir = len[0]['dir'];        
        changedSorting(SORTING_TABLE_USER,col,dir);
    } ); 
    
    $(id).on( 'length.dt', function ( e, settings, len ) {
        g_page_length = len;
        changedLength();
    } );
    
    $(id).on( 'page.dt', function () {
        setPageUrl(id);
        removeSelected();
    } );
        
    var team_url;
    if (team_id === TEAM_SNL) team_url = g_siteUrl+"php/data_tables/server_list_snl_team.php";
    else team_url = g_siteUrl+"php/data_tables/server_list_other_team.php";
	
    var def = Array([4, 'asc']);
    var sortingTable = getSorting(SORTING_TABLE_USER, def);                
        
    var start;
    if (g_page_nr == null) start = 0;
    else 
    {
        start = g_page_length*(g_page_nr-1);
        if (start < 0) start = 0;
    }        
        
    g_table_user = $(id).DataTable( {
	"createdRow": function ( row, data, index )
	{
            addComma(data[2], row, 1);  // data colomn starting at 0 (0 is id and invisible)
            addComma(data[3], row, 2);  // last number is visible column		
            if (team_id == TEAM_SNL)
            {
                addFlags(data[7], row, 6);
            }
            else
            {
                addFlags(data[6], row, 5);              
            }
            addTeamColumn(data[5], row, 5);
        },
        "serverSide": true,
	"ajax": {
		"url": team_url,
		"data": function ( d ) {
                    if (g_team_id == TEAM_SNL)
                    {                    
                    }
                    else
                    {
                        d.team = g_team_id;
                    }
		}
	},
        "fnDrawCallback": function( oSettings ) {
            if (initialSelect)
            {
                initialSelect = false;
                addSelectedClass(); 
            }
        },
        "language": {
	"url": table_lang_file
        },
        "aLengthMenu": [[10, 15, 20, 25, 50, 100, 200, 500], [10, 15, 20, 25, 50, 100, 200, 500]],
        "iDisplayLength": g_page_length,
        "displayStart": start,        
        "processing": false,
	"bAutoWidth": false,		
	"aoColumns": [
	{ "sTitle": "Id", "visible": false, "searchable": false  },
        { "sTitle": table_name, "sClass": "right", "sWidth": "100px" },
        { "sTitle": table_total , "sClass": "right","sWidth": "60px"  },
        { "sTitle": table_rac, "sClass": "right","sWidth": "60px"  },
        { "sTitle": table_team_rac,  "sClass": "center at", "sWidth": "30px"  },
        { "sTitle": table_team_total,  "sClass": "center at","sWidth": "30px"  },
        { "sTitle": overtake, "visible": false,  "sClass": "center at","sWidth": "10px"  },
        { "sTitle": table_country, "sClass": "center", "sWidth": "20px" }
	],
        "order": sortingTable  
    } );
    
    if (team_id == TEAM_SNL) {
        g_table_user.column(6).visible( true ) // show credit_diff = overtake    
    }
 
}

function tableUsersAllHeader()
{
	$( "#text_table" ).html(text_all_users);	
}

function tableUsersAll()
{
    var initialSelect = true;    
    tableUsersAllHeader();
	
    var url;
    url = g_siteUrl+"php/data_tables/server_list_all_users.php";
	
    var id = '#seti_table_users_all';        
        
    $(id).on( 'order.dt', function (e, settings, len) {
        var col = len[0]['col'];
        var dir = len[0]['dir'];        
        changedSorting(SORTING_TABLE_USER_ALL,col,dir);
    } );         
        
    $(id).on( 'length.dt', function ( e, settings, len ) {
        g_page_length = len;
        changedLength();
    } );   
    
    $(id).on( 'page.dt', function () {
        setPageUrlUrl(id);
        removeSelected();
    } );
    
    var def = Array([4, 'asc']);    
    var sortingTable = getSorting(SORTING_TABLE_USER_ALL, def);        
        
    var start;
    if (g_page_nr == null) start = 0;
    else 
    {
        start = g_page_length*(g_page_nr-1);
        if (start < 0) start = 0;
    }        
        
        
        
        
    g_table_users_all = $(id).dataTable( {
		"createdRow": function ( row, data, index )
		{
			addFlags(data[11], row, 10);
			addComma(data[2], row, 1);			
			addComma(data[3], row, 2);				
        },
        "serverSide": true,
	"ajax": {
		"url": url
	},
        "fnDrawCallback": function( oSettings ) {
            if (initialSelect)
            {
                initialSelect = false;
                addSelectedClass(); 
            }
        },        
        "order": sortingTable,
        "language": {
			"url": table_lang_file
        },
        "aLengthMenu": [[10, 15, 20, 25, 50, 100, 200, 500], [10, 15, 20, 25, 50, 100, 200, 500]],
        "iDisplayLength": g_page_length,
        "displayStart": start,        
        "processing": false,
		"bAutoWidth": false,		
		"aoColumns": [
			{ "sTitle": "Id", "visible": false, "searchable": false  },
            { "sTitle": table_name, "sClass": "right", "sWidth": "100px" },
            { "sTitle": table_total , "sClass": "right","sWidth": "60px"  },
            { "sTitle": table_rac, "sClass": "right","sWidth": "60px"  },
            { "sTitle": table_team_rac,  "sClass": "center at", "sWidth": "30px"  },
            { "sTitle": table_team_total,  "sClass": "center at","sWidth": "30px"  },
            { "sTitle": table_country_rac,  "sClass": "center at", "sWidth": "30px"  },
            { "sTitle": table_country_total,  "sClass": "center at","sWidth": "30px"  },			
            { "sTitle": table_world_rac,  "sClass": "center at", "sWidth": "30px"  },
            { "sTitle": table_world_total,  "sClass": "center at","sWidth": "30px"  },			
            { "sTitle": table_team, "sClass": "center", "sWidth": "20px" },
            { "sTitle": table_country, "sClass": "center", "sWidth": "20px" }
	]
    } );
}

function tableCountriesAllHeader()
{
	$( "#text_table" ).html(text_all_countries);	
}

function tableCountriesAll()
{
    var initialSelect = true;    
    tableCountriesAllHeader();
        
    var id = '#seti_table_countries_all';              
	
    $(id).on( 'order.dt', function (e, settings, len) {
        var col = len[0]['col'];
        var dir = len[0]['dir'];        
        changedSorting(SORTING_TABLE_COUNTRIES_ALL,col,dir);
    } );                   
        
    $(id).on( 'page.dt', function () {
        setPageUrl(id);
        removeSelected();
    } );
        
    var url;
    url = g_siteUrl+"php/data_tables/server_list_all_countries.php";
	
    var def = Array([4, 'asc']);    
    var sortingTable = getSorting(SORTING_TABLE_COUNTRIES_ALL, def);         
        
    var start;
    if (g_page_nr == null) start = 0;
    else 
    {
        start = g_page_length*(g_page_nr-1);
        if (start < 0) start = 0;
    }        
        
    g_table_countries_all = $(id).dataTable( {
		"createdRow": function ( row, data, index )
		{
			addFlags(data[0], row, 0);
			addComma(data[1], row, 1);
			addComma(Math.round(data[2]), row, 2);
			addUserColumn(data[5], row, 5);
        },
        "serverSide": true,
	"ajax": {
		"url": url
	},
        "fnDrawCallback": function( oSettings ) {
            if (initialSelect)
            {
                initialSelect = false;
                addSelectedClass(); 
            }
        },        
        "order": sortingTable,
        "language": {
			"url": table_lang_file
        },
        "aLengthMenu": [[10, 15, 20, 25, 50, 100, 200, 500], [10, 15, 20, 25, 50, 100, 200, 500]],
        "iDisplayLength": g_page_length,
        "displayStart": start,
        "processing": false,
		"bAutoWidth": false,		
		"aoColumns": [
  			{ "sTitle": table_country, "sClass": "center", "sWidth": "20px" },
            { "sTitle": table_total , "sClass": "right","sWidth": "60px"  },
            { "sTitle": table_rac, "sClass": "right","sWidth": "60px"  },
            { "sTitle": table_country_rac,  "sClass": "center ", "sWidth": "30px"  },
            { "sTitle": table_country_total,  "sClass": "center ","sWidth": "30px"  },
            { "sTitle": table_members,  "sClass": "center cm","sWidth": "10px"  }			
	]
    } );	
}

function tableCountryHeader(country_id)
{
    $( "#text_table" ).html(text_one_country + country_id);
}

function tableCountry(country_id)
{
    var initialSelect = true;    
    tableCountryHeader(country_id);
	
    var url;
    url = g_siteUrl+"php/data_tables/server_list_country.php";
    
    $(id).on( 'order.dt', function (e, settings, len) {
        var col = len[0]['col'];
        var dir = len[0]['dir'];
        changedSorting(SORTING_TABLE_COUNTRIES,col,dir);
    } );                   
            
    
    var id = '#seti_table_country';       
    $(id).on( 'length.dt', function ( e, settings, len ) {
        g_page_length = len;
        changedLength();
    } );             
        
    $(id).on( 'page.dt', function () {
        setPageUrl(id);
        removeSelected();
    } );
    
    var def = Array([4, 'asc']);    
    var sortingTable = getSorting(SORTING_TABLE_COUNTRIES, def);      
    
    var start;
    if (g_page_nr == null) start = 0;
    else 
    {
        start = g_page_length*(g_page_nr-1);
        if (start < 0) start = 0;
    }    
    
    g_table_country = $(id).dataTable( {
		"createdRow": function ( row, data, index )
		{
			addComma(data[2], row, 1);				
			addComma(data[3], row, 2);			
			addFlags(data[6], row, 5);
			addTeamColumn(data[5], row, 5);
        },
        "serverSide": true,
	"ajax": {
            "url": url,
            "data": function ( d ) {
		d.country = country_id;
            }
	},
        "fnDrawCallback": function( oSettings ) {
            if (initialSelect)
            {
                initialSelect = false;
                addSelectedClass(); 
            }
        },        
        "order": sortingTable,
        "language": {
			"url": table_lang_file
        },
        "aLengthMenu": [[10, 15, 20, 25, 50, 100, 200, 500], [10, 15, 20, 25, 50, 100, 200, 500]],
        "iDisplayLength": g_page_length,
        "displayStart": start,        
        "processing": false,
		"bAutoWidth": false,		
		"aoColumns": [
			{ "sTitle": "Id", "visible": false, "searchable": false  },
            { "sTitle": table_name, "sClass": "right", "sWidth": "100px" },
            { "sTitle": table_total , "sClass": "right","sWidth": "60px"  },
            { "sTitle": table_rac, "sClass": "right","sWidth": "60px"  },
            { "sTitle": table_country_rac,  "sClass": "center", "sWidth": "30px"  },
            { "sTitle": table_country_total,  "sClass": "center","sWidth": "30px"  },
			{ "sTitle": table_team, "sClass": "center", "sWidth": "20px" }
	]
    } );		
}

function getSorting(id,err_def)
{
    var sorting,sortingMain;
    
    sortingMain = [];
    if (g_order >=0)
    {
        sorting = [];
        sorting[0] = g_order;
        sorting[1] = g_order_dir;
    }
    else
    {
        sorting = readSorting(id);
        if (sorting == null)
        {    
            return err_def;
        }
    }
    sorting = validateSorting(sorting,err_def);
    sortingMain.push(sorting);
    return sortingMain;  
 //   return validateSorting(sorting,err_def);
}

function validateSorting(sorting,err_def)
{
    if (sorting[0] < 0)
    {
        return err_def;
    }
    if (sorting[1] == 'asc')
    {
        return sorting;
    }
    if (sorting[1] == 'desc')
    {
        return sorting;
    }  
    return err_def;
}


function addSelectedClass()
{
    var i, len, id;
    var id = getTypeId(); 
    var table = $(id).DataTable();
    var selected = [];
    
    len = g_selected.length;
    if (len > 0)
    {
        showGraphs(); 
    }
    table.rows(function ( idx, data, node )
    {
        for (i=0;i<len;i++)
        {
            id = data[0];
            if (id == g_selected[i])
            {                
                $(node).addClass(CLASS_SELECTED);
                processGraph(id,data[1]);
                selected.push(id);
                break;
            }
        }
    } )
    .data();

    g_selected = selected;  // only the selected found
    setParameters(); // now update with the actual selected
}