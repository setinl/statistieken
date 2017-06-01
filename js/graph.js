var g_colors = ['#000000','#ff0000','#00ff00','#0000ff','#ff9900','#ffff00','#996600'];
//var g_colors_index = 0;
//var g_colors_array = [];

var GRAPH_RAC_TEXT = " 'RAC'";
var GRAPH_SNL_TEXT = " 'S@NL-RAC'";
var GRAPH_AVG14_TEXT = " 'AVG14'";

var COMBO_SELECT_RAC_SNL = "S@NL-RAC";
var COMBO_SELECT_RAC = "RAC";
var COMBO_SELECT_AWG14 = "AWG14";
var COMBO_SELECT_NONE = "---";

var LOCAL_STORAGE_SELECT = "SNL_GRAPH_RAC_SELECT";
var LOCAL_STORAGE_SELECT_DASH = "SNL_GRAPH_RAC_SELECT_DASH";

var g_rac_select;
var g_rac_select_dash;

var g_time_machine = false;

var g__graph_busy = 0;
var g_graphs_hidden = false;

$(document).ready(function() {

	g_rac_select = localStorage.LOCAL_STORAGE_SELECT;
	g_rac_select = undefined;
	if (g_rac_select === undefined)
	{
		$("#rac_select").val(COMBO_SELECT_RAC);
		g_rac_select = COMBO_SELECT_RAC;
	}
	else
	{
		$("#rac_select").val(g_rac_select);
	}

	g_rac_select_dash = localStorage.LOCAL_STORAGE_SELECT_DASH;
	g_rac_select_dash = undefined;
	if (g_rac_select_dash === undefined)
	{
		$("#rac_select_dash").val(COMBO_SELECT_NONE);
		g_rac_select_dash = COMBO_SELECT_NONE;		
	}
	else $("#rac_select_dash").val(g_rac_select_dash);
		
	$('select').change( racChanged );
	
	initGraphCredit();
	initGraphCreditTm();	
	g_graph = new Graph(g_chartRac, g_chartCredit, g_chartCreditTm);
	g_graph.GraphType(g_table_type);

} );

function racChanged()
{
	var rac_select = $('#rac_select').val();
	var rac_select_dash = $('#rac_select_dash').val();
	
	if (rac_select === rac_select_dash) return;
	
	g_rac_select = rac_select;
	g_rac_select_dash = rac_select_dash;
	
	localStorage.LOCAL_STORAGE_SELECT = rac_select;
	localStorage.LOCAL_STORAGE_SELECT_DASH = rac_select_dash;
	
	rebuildGraphs();
	
}

function resetGraph()
{
//	g_colors_array = [];
}

function processGraph(id,name)
{
	g_graph.ReadDataGraphRac(id, name);
	g_graph.ReadDataGraphCredit(id, name);	
}

function rebuildGraphs()
{
	var bgetData = false;
	
	g_chartCredit.destroy();
	g_chartCreditTm.destroy();
	g_chartRac.destroy();
	g_graph = "";

	initGraphCredit();
	initGraphCreditTm();
	initGraphRac();
	g_graph = new Graph(g_chartRac, g_chartCredit, g_chartCreditTm);	
	g_graph.GraphType(g_table_type);
	
	// now add selected
	var data_table;
	if ( g_current_table.fnGetData === undefined)
	{
		data_table = g_current_table;
	}
	else
	{
		bgetData = true;
		data_table = g_current_table.fnGetData();	
	}
	g_current_table.$('tr').each(function()
	{
		var str = this.className;
		if (str.indexOf("selected") >= 0)
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
			processGraph(id,name);
		}
	});	
}

function seriesSetBusy()
{
//	if (g__graph_busy === 0)
	{
		document.getElementById("checkbox_time").disabled = true;
		document.getElementById("rac_select").disabled = true;	
		document.getElementById("rac_select_dash").disabled = true;
	}
	g__graph_busy++;
}

function seriesReadyEvent()
{
	g__graph_busy--;
	if (g__graph_busy <= 0)
	{
		document.getElementById("checkbox_time").disabled = false;
		document.getElementById("rac_select").disabled = false;			
		document.getElementById("rac_select_dash").disabled = false;
		g__graph_busy = 0;
	}
}

function  FindColor(name, c_array)
{
	for (var i = 0; i < c_array.length; i++)
	{
		if (c_array[i][0] === name)
		{
			return c_array[i][1];
		}
	}
	return -1;
};

function AddGraphColor(name, c_array)
{
	var color_found = this.FindColor(name, c_array);
	if ( color_found === -1)
	{
		var array_item = [];
		array_item[0] = name;
		var color_index = unusedColorIndex(c_array);
		array_item[1] = color_index;		
		c_array.push(array_item);
		return color_index;	
	}
	return color_found;
};

function RemoveColor(name, c_array)
{
	for (var i = 0; i < c_array.length; i++)
	{
		if (c_array[i][0] === name)
		{
			c_array.splice(i, 1);
		}
	}
};

function unusedColorIndex(c_array)
{
	for (var color_index = 0; color_index < 7; color_index++)
	{
		found = false;
		for (var i=0; i < c_array.length; i++)
		{
			if (c_array[i][1] === color_index)
			{
				found = true;
				break;
			}
		}
		if (!found)
		{
			return color_index;
		}
	}
	return 0;
}

function hideGraphs()
{
	$( "#graph_sh" ).hide();
	$( "#graph_first_time_sh" ).html(text_graph_first_time);		
	g_graphs_hidden = true;
}

function showGraphs()
{
	if (g_graphs_hidden)
	{
		$( "#graph_sh" ).show();
		$( "#graph_first_time_sh" ).html("");
		g_graph.GraphType(g_table_type);
		resetGraph();
		$('#graph_rac').highcharts().reflow();
		$('#graph_credit').highcharts().reflow();		
	}
	g_graphs_hidden = false;
}