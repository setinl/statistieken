
ACTION_READ_RAC_USER = 20;
ACTION_READ_RAC_TEAM = 21;

var g_seriesOptionsR = [];

//g_graph_firstime = true;
//var g_chart;

var highchartsOptions = new Highcharts.setOptions({
	lang:{
		loading: 'Laden...',
		weekdays: ['zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag'],
		months: ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
		shortMonths: ['jan', 'feb', 'Mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec'],
		exportButtonTitle: "Exporteer",
		printChart: "Afdrukken",
		rangeSelectorFrom: "Van",
		rangeSelectorTo: "tot",
		rangeSelectorZoom: "Periode",
		contextButtonTitle: "Beeld export selectie",
		downloadPNG: 'Download beeld PNG',
		downloadJPEG: 'Download beeld JPEG',
		downloadPDF: 'Download beeld PDF',
		downloadSVG: 'Download beeld SVG',
		thousandsSep: ".",
		decimalPoint: ','
	}
});

initGraphRac();


function initGraphRac()
{
	g_chartRac = new Highcharts.StockChart({	
		chart:{
			events: {
                addSeries: function() {
					seriesReadyEvent();
				}
			},			
            renderTo: 'graph_rac',
			animation: false
//			width: 800,
//			height: null
		},
		rangeSelector : {

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
			}],
			selected : 4
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
		series: g_seriesOptionsR,
		type: 'spline'
	});
};




