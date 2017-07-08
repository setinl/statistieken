function Graph(chart_rac, chart_credit, chart_credit_tm)
{
	this.m_chartRac = chart_rac;
	this.m_chartCredit = chart_credit;
	this.m_chartCreditTm = chart_credit_tm;

	this.m_colors_array = [];
	
}

Graph.prototype.GraphType = function(table_type)
{
	this.m_table_type = table_type;	
};

Graph.prototype.ReadDataGraphRac = function (seti_id, seti_name)
{
//	var len_rac = 	g_chartRac.series.length;
	
	var found = this.RemoveFromGraphRac(seti_name);

	if (found === false)
	{
		if (g_rac_select === COMBO_SELECT_RAC || g_rac_select_dash === COMBO_SELECT_RAC)
		{	
			seriesSetBusy();
			AddGraphColor(seti_name, this.m_colors_array);
			this.AskDataRac(seti_id, seti_name);
		}
	}
};

Graph.prototype.AskDataRac = function(id, seti_name)
{
//	id = "8906489";//S@NL - eFMer - www.efmer.eu/boinc

	var action = ACTION_READ_RAC_TEAM;

	switch (this. m_table_type)
	{ 
		case TABLE_USER:
			action = ACTION_READ_RAC_USER;	
		break;
		case TABLE_USERS_ALL:
			action = ACTION_READ_RAC_USER;	
		break;                
	}

	var time = "0";	
	var token = "read";	
	var jsonString = '"action":"'+ action + '", "id":"' + id + '","time":"' + time + '","token":"' + token + '"';
	jsonString = jsonString + "}";
	jsonString = "{" + jsonString;
	phpSendDataArg(this, jsonString, this.AskDataRacReady, seti_name);
};
 
Graph.prototype.AskDataRacReady = function (context, result, seti_name)
{
    try{
	var json_array = JSON.parse(result);
    }catch(e){
        return;	// error
    }

	var first_item = json_array.shift();
	if (first_item[0] !== "data") return;
	if (first_item[1] !== "ok") return;
	
	for (var i = 0; i < json_array.length; i++)
	{
		json_array[i][0] *=  3600000;
		json_array[i][1] = parseFloat(json_array[i][1]);//;.round();
		json_array[i][1] = Math.round(parseFloat(json_array[i][1] ));
	}

	var color_index = AddGraphColor(seti_name, context.m_colors_array);
	var color_graph = g_colors[color_index];
	
	if (g_rac_select === COMBO_SELECT_RAC)
	{
		context.m_chartRac.addSeries({
			name: seti_name + GRAPH_RAC_TEXT,
			data: json_array,
			color: color_graph,
			legendIndex: color_index*2
		});		
	}
	else
	{
		context.m_chartRac.addSeries({
			name: seti_name + GRAPH_RAC_TEXT,
			data: json_array,
			dashStyle: 'dash',				
			color: color_graph,
			legendIndex: color_index*2,
			showInLegend: false			
		});			
	}
};

// SNL_RAC = (AVG14+prevSNL_RAC*2)/3
Graph.prototype.GetRacSnl = function(context, json_array)
{
	var offset = 7;

	var avg14 = context.CalculateAvg14(context, json_array);
	var snl = [];	
	
	if (avg14.length > 2)
	{
		snl[0] = 0;
		for (var i = 1; i < avg14.length; i++)
		{
			snl[i] = (avg14[i] + snl[i-1] * 2)/3;
		}
	}
	
	var json_array_snl = [];
	for (var i = offset; i < json_array.length; i++)
	{
		json_array_snl[i-offset] = [];
		json_array_snl[i-offset][0] = json_array[i][0]  * 3600000;	// normalize time;
		json_array_snl[i-offset][1] = Math.round(snl[i]);
	}
	return json_array_snl;	
};

Graph.prototype.GetAvg = function (context, json_array)
{
	var offset = 7;

	var avg14 = context.CalculateAvg14(context, json_array);
	var json_array_avg14 = [];
	for (var i = offset; i < json_array.length; i++)
	{
		json_array_avg14[i-offset] = [];
		json_array_avg14[i-offset][0] = json_array[i][0]  * 3600000;	// normalize time;
		json_array_avg14[i-offset][1] = Math.round(avg14[i]);
	}
	return json_array_avg14;
};

Graph.prototype.CalculateAvg14 = function (context, json_array)
{
	var avg14 = [];
	
	for (var iPos = 0; iPos < json_array.length; iPos++)
	{
		avg14[iPos] = context.GetAvg14(json_array, iPos);
	}
	
	return avg14;
	
};

// time = 1 hour interval
Graph.prototype.GetAvg14 = function (json_array, i_end)
{
	var day_end = Math.round(json_array[i_end][0]/24);	// round to the day
	var prev_day = - 100000;
	var credits_end = json_array[i_end][1];
	var delta_prev = 10000;
	for (var i = i_end; i >= 0; i--)
	{
		var day = Math.round(json_array[i][0]/24);		// round to the day
		var delta = day_end - day;
		if (delta === 14) // perfect
		{
			return ((credits_end - json_array[i][1])/14);
		}
		if (delta > 14) // too far
		{
			var delta1 = delta - 14;
			var delta2 = Math.abs(delta_prev - 14);
			if (delta1 <=  delta2)
			{
				return ((credits_end - json_array[i][1])/delta);
			}
			else
			{
				return ((credits_end - json_array[i][1])/delta_prev);				
			}		
		}
		delta_prev = delta;
	}
	
	if (i_end > 1)
	{
		return ((credits_end - json_array[0][1])/14);				
	}

	
	return 0;
};

////////////////////////////////////////////////////////////////////////////////

Graph.prototype.ReadDataGraphCredit = function(seti_id, seti_name)
{
	var found = this.RemoveFromGraphCredit(seti_name);

	if (found === false)
	{
		seriesSetBusy();
		AddGraphColor(seti_name, this.m_colors_array);
		if (g_time_machine) this.AskDataCredit(seti_id, seti_name,1);	
		else
		{
			this.AskDataCredit(seti_id, seti_name,0);
		}
	}
};

Graph.prototype.AskDataCredit = function(id, seti_name, tm)
{
//	id = "8906489";//S@NL - eFMer - www.efmer.eu/boinc

	var action;

	if ((this.m_table_type === TABLE_USER) || (this.m_table_type === TABLE_USERS_ALL))
	{
		if (tm === 1) action = ACTION_READ_CREDIT_USER_TM;
		else action = ACTION_READ_CREDIT_USER;
	}
	else
	{
		if (tm === 1) action = ACTION_READ_CREDIT_TEAM_TM;
		else action = ACTION_READ_CREDIT_TEAM;		
	}

	var time = "0";	
	var token = "read";	
	var jsonString = '"action":"'+ action + '", "id":"' + id + '","time":"' + time + '","token":"' + token + '"';
	jsonString = jsonString + "}";
	jsonString = "{" + jsonString;
	phpSendDataArg(this, jsonString, this.AskDataCreditReady, seti_name, tm);
};

Graph.prototype.AskDataCreditReady = function(context, result, seti_name, tm)
{
	try{
		var json_array = JSON.parse(result);
    }catch(e){
        return;	// error
    }
	var json_array_avg;
	var seti_name_extra = "";
	
	var first_item = json_array.shift();
	if (first_item[0] !== "data") return;
	if (first_item[1] !== "ok") return;	

	for (var i = 0; i < json_array.length; i++)
	{
		json_array[i][1] = parseFloat(json_array[i][1]);//;.round();
		json_array[i][1] = Math.round(parseFloat(json_array[i][1] ));		
	}

	var color_index = AddGraphColor(seti_name, context.m_colors_array);
	var color_graph = g_colors[color_index];

	if (tm === 0)
	{
		if (g_rac_select === COMBO_SELECT_AWG14)
		{
			seti_name_extra = GRAPH_AVG14_TEXT; 
			json_array_avg = context.GetAvg(context, json_array);
			context.m_chartRac.addSeries({
				name: seti_name + seti_name_extra,
				data: json_array_avg,
				color: color_graph,
				legendIndex: (color_index*2)+1
			});			
		}
		if (g_rac_select_dash === COMBO_SELECT_AWG14)
		{
			seti_name_extra = GRAPH_AVG14_TEXT; 
			json_array_avg = context.GetAvg(context, json_array);
			context.m_chartRac.addSeries({
				name: seti_name + seti_name_extra,
				data: json_array_avg,
				dashStyle: 'dash',
				color: color_graph,
				legendIndex: (color_index*2)+1,
				showInLegend: false
			});			
		}
		if (g_rac_select === COMBO_SELECT_RAC_SNL)
		{
			seti_name_extra = GRAPH_SNL_TEXT; 
			json_array_avg = context.GetRacSnl(context, json_array);
			context.m_chartRac.addSeries({
				name: seti_name + seti_name_extra,
				data: json_array_avg,
				color: color_graph,
				legendIndex: (color_index*2)+1
			});		
		}	
				
		if (g_rac_select_dash === COMBO_SELECT_RAC_SNL)
		{
			seti_name_extra = GRAPH_SNL_TEXT; 
			json_array_avg = context.GetRacSnl(context, json_array);
			context.m_chartRac.addSeries({
				name: seti_name + seti_name_extra,
				data: json_array_avg,
				dashStyle: 'dash',
				color: color_graph,
				legendIndex: (color_index*2)+1,
				showInLegend: false
			});		
		}
	}
	
	for (var i = 0; i < json_array.length; i++)
	{
		json_array[i][0]  *=  3600000;	// normalize time
	}
	
	if (tm === 0)
	{
		context.m_chartCredit.addSeries({
			name: seti_name,
			data: json_array,
			color: color_graph,
			legendIndex: color_index
		});			
	}
	else
	{
		context.m_chartCreditTm.addSeries({
			name: seti_name,
			data: json_array,
			color: color_graph,
			legendIndex: color_index
		});	
	}
};

///////////////////////////////////////////////////////////////////////////////

Graph.prototype.RemoveFromGraph = function (seti_name)
{
	this.RemoveFromGraphCredit(seti_name);
	this.RemoveFromGraphRac(seti_name);
};


Graph.prototype.RemoveFromGraphCredit = function (seti_name)
{
	var found = false;	
	
	var len = this.m_chartCredit.series.length;
	for (var i = len-1; i > -1; i--)
	{
		var name = this.m_chartCredit.series[i].name;
		if (name === seti_name)
		{
			this.m_chartCredit.series[i].remove(true);
			RemoveColor(seti_name, this.m_colors_array);
			found = true;		
			break;
		}
	}
	var len = this.m_chartCreditTm.series.length;
	for (var i = len-1; i > -1; i--)
	{
		var name = this.m_chartCreditTm.series[i].name;
		if (name === seti_name)
		{
			this.m_chartCreditTm.series[i].remove(true);
//			removeColor(seti_name);
			found = true;		
			break;
		}
	}	
	return found;
};

Graph.prototype.RemoveFromGraphRac = function (seti_name)
{
	if (this.m_chartRac.series === undefined)
	{
		return false;
	}
	
	var found = false;		
	var len = this.m_chartRac.series.length;
	for (var i = len-1; i > -1; i--)
	{
		var name = this.m_chartRac.series[i].name;
		if ((name === seti_name || name === seti_name + GRAPH_AVG14_TEXT) || (name === seti_name + GRAPH_SNL_TEXT) || (name === seti_name + GRAPH_RAC_TEXT))
		{
			this.m_chartRac.series[i].remove(true);
			RemoveColor(seti_name, this.m_colors_array);
//			var found_now = true;
			found = true;
			break;			
		}		
	}
	return found;
};

////////////////////////////////////////////////////////////////////////////////

