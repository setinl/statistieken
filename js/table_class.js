//// https://datatables.net/


function Table(table_name)
{
	this.m_table_name = table_name;
}

Table.prototype.CaptureTableSelections = function captureTableSelections()
{	
	var table_selected = "#"+this.m_table_name+" tbody";
	
	$(table_selected).on('click', 'td', function () {
		g_select_column = -1 ;
		if ($(this).hasClass("at"))	g_select_column = COLUMN_ALL_TEAM;
		if ($(this).hasClass("tm"))	g_select_column = COLUMN_TEAM;
		if ($(this).hasClass("cm"))	g_select_column = COLUMN_COUNTRY;
	} );
	
    $(table_selected).on('click', 'tr', function () {
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

//		processGraph(id,name);
	
    } ); 	
};


