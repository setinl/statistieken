
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
    tableTeamsAllHeader();

    $('#seti_table_teams_all').on( 'order.dt', function (e, settings, len) {
        var col = len[0]['col'];
        var dir = len[0]['dir'];        
        storeSorting(SORTING_TABLE_TEAM,col,dir);
    } );    
    
    var def = Array([4, 'asc']);
    var sortingTable = getSorting(SORTING_TABLE_TEAM, def);
//    var sortingTable = [[5, 'asc']];

    g_table_teams_all = $('#seti_table_teams_all').dataTable( {
	"createdRow": function ( row, data, index )
	{
            addComma(data[2], row, 1);
            addComma(data[3], row, 2);	
            addFlags(data[6], row, 5);
            addUserColumn(data[7], row, 6);
        },		
        "serverSide": true,
        "ajax": g_siteUrl+"/stats/php/data_tables/server_list_all_teams.php",	
	
        "aLengthMenu": [[10, 25, 50, 100, 200, 500], [10, 25, 50, 100, 200, 500]],
        "iDisplayLength": 10,
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
            { "sTitle": table_members, "sClass": "right tm", "sWidth": "10px" }			
	],
          "order": sortingTable //[[4, 'asc']]
//        "order": [4, 'asc']
    } );
    
//    g_table_teams_all.order( [ 1, 'asc' ] );
//    g_table_teams_all.draw();
    
}

function tableUserHeader(team_id, team_name)
{
	$( "#text_table" ).html(text_one_team + team_name + " ("+ team_id+")");	
}

function tableUserSet(team_id, team_name)
{
	tableUserHeader(team_id, team_name);
                
	var team_url;
	if (team_id === "30190") team_url = g_siteUrl + "/stats/php/data_tables/server_list_snl_team.php";
	else team_url = g_siteUrl + "/stats/php/data_tables/server_list_other_team.php";
	g_table_user.ajax.url(team_url);
	g_table_user.ajax.reload();
}

function tableUser(team_id, team_name)
{
    tableUserHeader(team_id, team_name);
	
    $('#seti_table_user').on( 'order.dt', function (e, settings, len) {
        var col = len[0]['col'];
        var dir = len[0]['dir'];        
        storeSorting(SORTING_TABLE_USER,col,dir);
    } );         
        
    var team_url;
    if (team_id === "30190") team_url = g_siteUrl+"/stats/php/data_tables/server_list_snl_team.php";
    else team_url = g_siteUrl+"/stats/php/data_tables/server_list_other_team.php";
	
    var def = Array([4, 'asc']);
    var sortingTable = getSorting(SORTING_TABLE_USER, def);                
        
    g_table_user = $('#seti_table_user').DataTable( {
		"createdRow": function ( row, data, index )
		{
			addComma(data[2], row, 1);
			addComma(data[3], row, 2);				
			addFlags(data[6], row, 5);
			addTeamColumn(data[5], row, 5);
        },
        "serverSide": true,
	"ajax": {
		"url": team_url,
		"data": function ( d ) {
			if (g_team_id !== "30190")
			{
				d.team = g_team_id;
			}
		}
	},
        "language": {
	"url": table_lang_file
        },
        "aLengthMenu": [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
        "iDisplayLength": 10,
        "processing": false,
		"bAutoWidth": false,		
		"aoColumns": [
			{ "sTitle": "Id", "visible": false, "searchable": false  },
            { "sTitle": table_name, "sClass": "right", "sWidth": "100px" },
            { "sTitle": table_total , "sClass": "right","sWidth": "60px"  },
            { "sTitle": table_rac, "sClass": "right","sWidth": "60px"  },
            { "sTitle": table_team_rac,  "sClass": "center at", "sWidth": "30px"  },
            { "sTitle": table_team_total,  "sClass": "center at","sWidth": "30px"  },
			{ "sTitle": table_country, "sClass": "center", "sWidth": "20px" }
	],
        "order": sortingTable //[[4, 'asc']]        
    } );
}

function tableUsersAllHeader()
{
	$( "#text_table" ).html(text_all_users);	
}

function tableUsersAll()
{
	tableUsersAllHeader();
	
	var url;
	url = g_siteUrl+"/stats/php/data_tables/server_list_all_users.php";
	
    g_table_users_all = $('#seti_table_users_all').dataTable( {
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
		"order": [[ 4, "asc" ]],
        "language": {
			"url": table_lang_file
        },
        "aLengthMenu": [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
        "iDisplayLength": 10,
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
	tableCountriesAllHeader();
	
	var url;
	url = g_siteUrl+"/stats/php/data_tables/server_list_all_countries.php";
	
    g_table_countries_all = $('#seti_table_countries_all').dataTable( {
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
		"order": [[ 4, "asc" ]],
        "language": {
			"url": table_lang_file
        },
        "aLengthMenu": [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
        "iDisplayLength": 10,
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
	tableCountryHeader(country_id);
	
	var url;
	url = g_siteUrl+"/stats/php/data_tables/server_list_country.php";
	
    g_table_country = $('#seti_table_country').dataTable( {
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
		"order": [[ 4, "asc" ]],
        "language": {
			"url": table_lang_file
        },
        "aLengthMenu": [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
        "iDisplayLength": 10,
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
    var sorting = readSorting(id);
    if (sorting === null)
    {    
        return err_def;
    }    
    return sorting;
}