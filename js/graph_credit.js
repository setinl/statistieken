ACTION_READ_CREDIT_USER = 10;
ACTION_READ_CREDIT_USER_TM = 11;
ACTION_READ_CREDIT_TEAM = 15;
ACTION_READ_CREDIT_TEAM_TM = 16;

var g_seriesOptionsC = [];
var g_seriesOptionsCTm = [];

function initGraphCredit()
{
	g_chartCredit = new Highcharts.StockChart({	
		chart:{
			events: {
                addSeries: function() {
					seriesReadyEvent();
				}
			},
            renderTo: 'graph_credit',			
			animation: false
		},
		rangeSelector : {
			selected : 3,
//			inputEnabled: $('#graph_credit').width() > 480,
			buttons: [{
			type: 'month',
			count: 1,
			text: '1m'
			}, {
			type: 'month',
			count: 3,
			text: '3m'
			}, {
			type: 'month',
			count: 6,
			text: '6m'
			}, {
			type: 'year',
			count: 1,
			text: 'Jaar'
			}, {
			type: 'all',
			text: 'Alles'
			}]			
		},
        legend: {
			enabled: true,
			floating: true,
            backgroundColor: '#eeeeff',
            align:'center',
            verticalAlign:'top',
            layout:'vertical'
        },
		tooltip: {
            shared: false
        },
		yAxis: {
           floor: 0
		},
		credits: {
			enabled: false
		},		
		series: g_seriesOptionsC,
		type: 'spline'
	});
};

function initGraphCreditTm()
{
	g_chartCreditTm = new Highcharts.StockChart({	
		chart:{
			events: {
                addSeries: function() {
					seriesReadyEvent();
				}
			},			
            renderTo: 'graph_credit_tm',			
			animation: false
		},
		rangeSelector : {
			selected : 1,
//			inputEnabled: $('#graph_credit').width() > 480,
			buttons: [{
			type: 'all',
			text: 'Alles'
			}]			
		},
        legend: {
			enabled: true,
			floating: true,
            backgroundColor: '#eeeeff',
            align:'center',
            verticalAlign:'top',
            layout:'vertical'
        },
		tooltip: {
            shared: false
        },		
		yAxis: {
			floor: 0
		},
		credits: {
			enabled: false
		},
		series: g_seriesOptionsCTm,
		type: 'spline'
	});
};


