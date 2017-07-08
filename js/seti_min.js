function getSiteUrl(){var b,a="seti.nl/";g_siteUrl="";var c=window.location.href;c&&(b=c.indexOf(a),0<=b?g_siteUrl=c.substr(0,b+a.length):(a="localhost:8080/stats/",b=c.indexOf(a),0<=b&&(g_siteUrl=c.substr(0,b+a.length))))};var SORTING_TABLE_TEAM="TEAM",SORTING_TABLE_USER="USER",SORTING_COLUMN="_col",SORTING_DIRECTION="_dir",STORAGE_LANGUAGE="language";function storeSorting(b,a,c){if(!localStorageExists())return!1;localStorage.setItem(b+SORTING_COLUMN,a);localStorage.setItem(b+SORTING_DIRECTION,c)}function readSorting(b){if(!localStorageExists())return null;var a=[];a[0]=localStorage.getItem(b+SORTING_COLUMN);a[1]=localStorage.getItem(b+SORTING_DIRECTION);return isUndefined(a[0])||isUndefined(a[1])?null:a}
function storeLanguage(b){if(!localStorageExists())return!1;localStorage.setItem(STORAGE_LANGUAGE,b)}function readLanguage(b){if(!localStorageExists())return b;var a=localStorage.getItem(STORAGE_LANGUAGE);return isUndefined(a)?b:a}function localStorageExists(){try{return"localStorage"in window&&null!==window.localStorage}catch(b){return!1}};function isUndefined(b){return void 0===b||null===b?!0:!1};LANGUAGE_ENGLISH="US";LANGUAGE_DUTCH="NL";var g_language;function language(){var b;g_language=readLanguage(LANGUAGE_ENGLISH);switch(g_language){case LANGUAGE_DUTCH:languageDutch();b="<img src='js/lib/flags/blank.png' class='flag flag-nl '/>";break;default:languageEnglish(),b="<img src='js/lib/flags/blank.png' class='flag flag-us '/>"}$("#flag_selected_language").html(b)}
function languageToggle(){g_language==LANGUAGE_ENGLISH?storeLanguage(LANGUAGE_DUTCH):storeLanguage(LANGUAGE_ENGLISH);location.reload()}
function languageEnglish(){table_team_rac="Team RAC";table_team_total="Team Total";table_name="Name";table_total="Total";table_rac="RAC";table_country="Country";table_members="Members";table_country_rac="Country RAC";table_country_total="Country Total";table_world_rac="World RAC";table_world_total="World Total";overtake="Overtake";table_team="Team";table_lang_file="";text_credits="Credits";text_credits_future="Credits in the future";text_solid_line="Solid line: ";text_dash_line="Dashed lijn: ";text_cedit_future=
"Credits in the future ";text_tool_users="'Show user list'";text_one_team="Team: ";text_all_teams="All teams";text_all_users="All users";text_all_countries="All countries";text_one_country="Country: ";text_table_info="Info";text_table_status="Status";txt_stat_head_zero="Zero Day update";txt_stat_head_all="Daily update";txt_stat_head_user="Daily user update";txt_stat_head_team="Daily team update";txt_stat_head_post="Post processing";txt_stat_started="Started on:";txt_stat_processed="Processed in:";
txt_stat_status="State:";txt_stat_user="User:";txt_stat_processed_nr="Processed:";txt_stat_processed_last="Last processed on:";txt_stat_error_zero="Errors:";txt_stat_checked_time="Last checked on:";txt_stat_error_user="Error/ status user:";txt_stat_error_team="Error/ status team:";txt_stat_error_all="Error total:";txt_stat_data_read="Data read in:";txt_stat_data_processed="Data processed in:";txt_stat_data_added="Data added in";txt_stat_data_time="Data time stamp:";txt_processing_zero_wait="Waiting for the next update";
txt_processing_zero_run="Running";txt_processing_users="Processing users";txt_processing_teams="Processing teams";txt_stat_post="To do:";txt_stat_busy="Now processing:";txt_update_status1="Status will be automatically updated in ";txt_update_stauts2=" seconds";txt_update_now="Updating ...";txt_todo_gen_lists="Generate new lists";txt_progress_snl="Building S@NL list";txt_progress_build_users="Building users list for: ";txt_progress_build_all_team="Building all users list";txt_progress_build_all_country=
"Building country list";text_graph_first_time="<b>Click on one of the rows to show graphs</b>"}
function languageDutch(){table_team_rac="Team RAC";table_team_total="Team Totaal";overtake="Inhalen";table_name="Naam";table_total="Totaal";table_rac="RAC";table_country="Land";table_members="Leden";table_country_rac="L RAC";table_country_total="L Totaal";table_world_rac="W RAC";table_world_total="W Totaal";table_team="Team";table_lang_file="js/lib/data_tables/lang/nl.txt";text_credits="Punten";text_credits_future="Punten in de toekomst";text_solid_line="Doorgetrokken lijn: ";text_dash_line="Gestreepte lijn: ";
text_cedit_future="Punten in de toekomst ";text_tool_users="Toon gebruikers lijst";text_one_team="Team: ";text_all_teams="Alle teams";text_all_users="Alle gebruikers";text_all_countries="Alle landen";text_one_country="Land: ";text_table_info="Info";text_table_status="Status";txt_stat_head_zero="Status Zero Day update";txt_stat_head_all="Status dagelijkse update";txt_stat_head_user="Dagelijkse update gebruikers";txt_stat_head_team="Dagelijkse update team";txt_stat_head_post="Nabewerkingen";txt_stat_started=
"Gestart:";txt_stat_processed="Verwerkingstijd:";txt_stat_status="Status:";txt_stat_user="Gebruiker:";txt_stat_processed_nr="Verwerkt:";txt_stat_processed_last="Laatst verwerk:";txt_stat_error_zero="Fouten:";txt_stat_checked_time="Laatste controle:";txt_stat_error_user="Status / fouten gebruiker:";txt_stat_error_team="Status / fouten team:";txt_stat_error_all="Fouten totaal:";txt_stat_data_read="Data gelezen in:";txt_stat_data_processed="Data verwerkt in:";txt_stat_data_added="Data toegevoegd in";
txt_stat_data_time="Data van:";txt_processing_zero_wait="Wacht op de volgende update";txt_processing_zero_run="Loopt nu";txt_processing_users="Bezig met het verwerken gebruikers";txt_processing_teams="Bezig met het verwerken teams";txt_stat_post="Nog te doen:";txt_stat_busy="Nu bezig met:";txt_update_status1="Onderstaande info wordt over ";txt_update_stauts2=" seconden ververst";txt_update_now="Ververs ...";txt_todo_gen_lists="Genereer nieuwe lijsten";txt_progress_snl="Lijst S@NL wordt gemaakt";txt_progress_build_users=
"Lijst van gebruikers wordt gemaakt voor ";txt_progress_build_all_team="Lijst alle teams wordt gemaakt";txt_progress_build_all_country="Lijst alle landen wordt gemaakt";text_graph_first_time="<b>Klik op een van de rijen om de grafiek te laten zien</b>"};function Graph(b,a,c){this.m_chartRac=b;this.m_chartCredit=a;this.m_chartCreditTm=c;this.m_colors_array=[]}Graph.prototype.GraphType=function(b){this.m_table_type=b};Graph.prototype.ReadDataGraphRac=function(b,a){!1!==this.RemoveFromGraphRac(a)||g_rac_select!==COMBO_SELECT_RAC&&g_rac_select_dash!==COMBO_SELECT_RAC||(seriesSetBusy(),AddGraphColor(a,this.m_colors_array),this.AskDataRac(b,a))};
Graph.prototype.AskDataRac=function(b,a){var c=ACTION_READ_RAC_TEAM;switch(this.m_table_type){case TABLE_USER:c=ACTION_READ_RAC_USER;break;case TABLE_USERS_ALL:c=ACTION_READ_RAC_USER}c="{"+('"action":"'+c+'", "id":"'+b+'","time":"0","token":"read"}');phpSendDataArg(this,c,this.AskDataRacReady,a)};
Graph.prototype.AskDataRacReady=function(b,a,c){try{var d=JSON.parse(a)}catch(f){return}a=d.shift();if("data"===a[0]&&"ok"===a[1]){for(a=0;a<d.length;a++)d[a][0]*=36E5,d[a][1]=parseFloat(d[a][1]),d[a][1]=Math.round(parseFloat(d[a][1]));a=AddGraphColor(c,b.m_colors_array);var e=g_colors[a];g_rac_select===COMBO_SELECT_RAC?b.m_chartRac.addSeries({name:c+GRAPH_RAC_TEXT,data:d,color:e,legendIndex:2*a}):b.m_chartRac.addSeries({name:c+GRAPH_RAC_TEXT,data:d,dashStyle:"dash",color:e,legendIndex:2*a,showInLegend:!1})}};
Graph.prototype.GetRacSnl=function(b,a){var c=b.CalculateAvg14(b,a),d=[];if(2<c.length){d[0]=0;for(var e=1;e<c.length;e++)d[e]=(c[e]+2*d[e-1])/3}c=[];for(e=7;e<a.length;e++)c[e-7]=[],c[e-7][0]=36E5*a[e][0],c[e-7][1]=Math.round(d[e]);return c};Graph.prototype.GetAvg=function(b,a){for(var c=b.CalculateAvg14(b,a),d=[],e=7;e<a.length;e++)d[e-7]=[],d[e-7][0]=36E5*a[e][0],d[e-7][1]=Math.round(c[e]);return d};
Graph.prototype.CalculateAvg14=function(b,a){for(var c=[],d=0;d<a.length;d++)c[d]=b.GetAvg14(a,d);return c};Graph.prototype.GetAvg14=function(b,a){for(var c=Math.round(b[a][0]/24),d=b[a][1],e=1E4,f=a;0<=f;f--){var g=c-Math.round(b[f][0]/24);if(14===g)return(d-b[f][1])/14;if(14<g)return g-14<=Math.abs(e-14)?(d-b[f][1])/g:(d-b[f][1])/e;e=g}return 1<a?(d-b[0][1])/14:0};
Graph.prototype.ReadDataGraphCredit=function(b,a){!1===this.RemoveFromGraphCredit(a)&&(seriesSetBusy(),AddGraphColor(a,this.m_colors_array),g_time_machine?this.AskDataCredit(b,a,1):this.AskDataCredit(b,a,0))};
Graph.prototype.AskDataCredit=function(b,a,c){b='"action":"'+(this.m_table_type===TABLE_USER||this.m_table_type===TABLE_USERS_ALL?1===c?ACTION_READ_CREDIT_USER_TM:ACTION_READ_CREDIT_USER:1===c?ACTION_READ_CREDIT_TEAM_TM:ACTION_READ_CREDIT_TEAM)+'", "id":"'+b+'","time":"0","token":"read"}';phpSendDataArg(this,"{"+b,this.AskDataCreditReady,a,c)};
Graph.prototype.AskDataCreditReady=function(b,a,c,d){try{var e=JSON.parse(a)}catch(k){return}var f,g;a=e.shift();if("data"===a[0]&&"ok"===a[1]){for(f=0;f<e.length;f++)e[f][1]=parseFloat(e[f][1]),e[f][1]=Math.round(parseFloat(e[f][1]));a=AddGraphColor(c,b.m_colors_array);var h=g_colors[a];0===d&&(g_rac_select===COMBO_SELECT_AWG14&&(g=GRAPH_AVG14_TEXT,f=b.GetAvg(b,e),b.m_chartRac.addSeries({name:c+g,data:f,color:h,legendIndex:2*a+1})),g_rac_select_dash===COMBO_SELECT_AWG14&&(g=GRAPH_AVG14_TEXT,f=b.GetAvg(b,
e),b.m_chartRac.addSeries({name:c+g,data:f,dashStyle:"dash",color:h,legendIndex:2*a+1,showInLegend:!1})),g_rac_select===COMBO_SELECT_RAC_SNL&&(g=GRAPH_SNL_TEXT,f=b.GetRacSnl(b,e),b.m_chartRac.addSeries({name:c+g,data:f,color:h,legendIndex:2*a+1})),g_rac_select_dash===COMBO_SELECT_RAC_SNL&&(g=GRAPH_SNL_TEXT,f=b.GetRacSnl(b,e),b.m_chartRac.addSeries({name:c+g,data:f,dashStyle:"dash",color:h,legendIndex:2*a+1,showInLegend:!1})));for(f=0;f<e.length;f++)e[f][0]*=36E5;0===d?b.m_chartCredit.addSeries({name:c,
data:e,color:h,legendIndex:a}):b.m_chartCreditTm.addSeries({name:c,data:e,color:h,legendIndex:a})}};Graph.prototype.RemoveFromGraph=function(b){this.RemoveFromGraphCredit(b);this.RemoveFromGraphRac(b)};
Graph.prototype.RemoveFromGraphCredit=function(b){for(var a=!1,c=this.m_chartCredit.series.length,c=c-1;-1<c;c--){var d=this.m_chartCredit.series[c].name;if(d===b){this.m_chartCredit.series[c].remove(!0);RemoveColor(b,this.m_colors_array);a=!0;break}}c=this.m_chartCreditTm.series.length;for(--c;-1<c;c--)if(d=this.m_chartCreditTm.series[c].name,d===b){this.m_chartCreditTm.series[c].remove(!0);a=!0;break}return a};
Graph.prototype.RemoveFromGraphRac=function(b){if(void 0===this.m_chartRac.series)return!1;for(var a=!1,c=this.m_chartRac.series.length-1;-1<c;c--){var d=this.m_chartRac.series[c].name;if(d===b||d===b+GRAPH_AVG14_TEXT||d===b+GRAPH_SNL_TEXT||d===b+GRAPH_RAC_TEXT){this.m_chartRac.series[c].remove(!0);RemoveColor(b,this.m_colors_array);a=!0;break}}return a};ACTION_READ_RAC_USER=20;ACTION_READ_RAC_TEAM=21;
var g_seriesOptionsR=[],highchartsOptions=new Highcharts.setOptions({lang:{loading:"Laden...",weekdays:"zondag maandag dinsdag woensdag donderdag vrijdag zaterdag".split(" "),months:"januari februari maart april mei juni juli augustus september oktober november december".split(" "),shortMonths:"jan feb Mrt apr mei jun jul aug sep okt nov dec".split(" "),exportButtonTitle:"Exporteer",printChart:"Afdrukken",rangeSelectorFrom:"Van",rangeSelectorTo:"tot",rangeSelectorZoom:"Periode",contextButtonTitle:"Beeld export selectie",
downloadPNG:"Download beeld PNG",downloadJPEG:"Download beeld JPEG",downloadPDF:"Download beeld PDF",downloadSVG:"Download beeld SVG",thousandsSep:".",decimalPoint:","}});initGraphRac();
function initGraphRac(){g_chartRac=new Highcharts.StockChart({chart:{events:{addSeries:function(){seriesReadyEvent()}},renderTo:"graph_rac",animation:!1},rangeSelector:{buttons:[{type:"month",count:1,text:"1m"},{type:"month",count:3,text:"3m"},{type:"month",count:6,text:"6m"},{type:"year",count:1,text:"Jaar"},{type:"all",text:"Alles"}],selected:4},legend:{enabled:!0,floating:!0,backgroundColor:"#eeeeff",align:"center",verticalAlign:"top",layout:"vertical"},tooltip:{shared:!1},yAxis:{floor:0},credits:{enabled:!1},
series:g_seriesOptionsR,type:"spline"})};ACTION_READ_CREDIT_USER=10;ACTION_READ_CREDIT_USER_TM=11;ACTION_READ_CREDIT_TEAM=15;ACTION_READ_CREDIT_TEAM_TM=16;var g_seriesOptionsC=[],g_seriesOptionsCTm=[];
function initGraphCredit(){g_chartCredit=new Highcharts.StockChart({chart:{events:{addSeries:function(){seriesReadyEvent()}},renderTo:"graph_credit",animation:!1},rangeSelector:{selected:3,buttons:[{type:"month",count:1,text:"1m"},{type:"month",count:3,text:"3m"},{type:"month",count:6,text:"6m"},{type:"year",count:1,text:"Jaar"},{type:"all",text:"Alles"}]},legend:{enabled:!0,floating:!0,backgroundColor:"#eeeeff",align:"center",verticalAlign:"top",layout:"vertical"},tooltip:{shared:!1},yAxis:{floor:0},
credits:{enabled:!1},series:g_seriesOptionsC,type:"spline"})}
function initGraphCreditTm(){g_chartCreditTm=new Highcharts.StockChart({chart:{events:{addSeries:function(){seriesReadyEvent()}},renderTo:"graph_credit_tm",animation:!1},rangeSelector:{selected:1,buttons:[{type:"all",text:"Alles"}]},legend:{enabled:!0,floating:!0,backgroundColor:"#eeeeff",align:"center",verticalAlign:"top",layout:"vertical"},tooltip:{shared:!1},yAxis:{floor:0},credits:{enabled:!1},series:g_seriesOptionsCTm,type:"spline"})};var g_colors="#000000 #ff0000 #00ff00 #0000ff #ff9900 #ffff00 #996600".split(" "),GRAPH_RAC_TEXT=" 'RAC'",GRAPH_SNL_TEXT=" 'S@NL-RAC'",GRAPH_AVG14_TEXT=" 'AVG14'",COMBO_SELECT_RAC_SNL="S@NL-RAC",COMBO_SELECT_RAC="RAC",COMBO_SELECT_AWG14="AWG14",COMBO_SELECT_NONE="---",LOCAL_STORAGE_SELECT="SNL_GRAPH_RAC_SELECT",LOCAL_STORAGE_SELECT_DASH="SNL_GRAPH_RAC_SELECT_DASH",g_rac_select,g_rac_select_dash,g_time_machine=!1,g__graph_busy=0,g_graphs_hidden=!1;
$(document).ready(function(){g_rac_select=localStorage.LOCAL_STORAGE_SELECT;g_rac_select=void 0;void 0===g_rac_select?($("#rac_select").val(COMBO_SELECT_RAC),g_rac_select=COMBO_SELECT_RAC):$("#rac_select").val(g_rac_select);g_rac_select_dash=localStorage.LOCAL_STORAGE_SELECT_DASH;g_rac_select_dash=void 0;void 0===g_rac_select_dash?($("#rac_select_dash").val(COMBO_SELECT_NONE),g_rac_select_dash=COMBO_SELECT_NONE):$("#rac_select_dash").val(g_rac_select_dash);$("select").change(racChanged);initGraphCredit();
initGraphCreditTm();g_graph=new Graph(g_chartRac,g_chartCredit,g_chartCreditTm);g_graph.GraphType(g_table_type)});function racChanged(){var b=$("#rac_select").val(),a=$("#rac_select_dash").val();b!==a&&(g_rac_select=b,g_rac_select_dash=a,localStorage.LOCAL_STORAGE_SELECT=b,localStorage.LOCAL_STORAGE_SELECT_DASH=a,rebuildGraphs())}function resetGraph(){}function processGraph(b,a){g_graph.ReadDataGraphRac(b,a);g_graph.ReadDataGraphCredit(b,a)}
function rebuildGraphs(){var b=!1;g_chartCredit.destroy();g_chartCreditTm.destroy();g_chartRac.destroy();g_graph="";initGraphCredit();initGraphCreditTm();initGraphRac();g_graph=new Graph(g_chartRac,g_chartCredit,g_chartCreditTm);g_graph.GraphType(g_table_type);var a;void 0===g_current_table.fnGetData?a=g_current_table:(b=!0,a=g_current_table.fnGetData());g_current_table.$("tr").each(function(){if(0<=this.className.indexOf("selected")){if(!1===b)var c=a.row(this).data(),d=c[0],c=c[1];else c=this._DT_RowIndex,
d=a[c][0],c=a[c][1];processGraph(d,c)}})}function seriesSetBusy(){document.getElementById("checkbox_time").disabled=!0;document.getElementById("rac_select").disabled=!0;document.getElementById("rac_select_dash").disabled=!0;g__graph_busy++}function seriesReadyEvent(){g__graph_busy--;0>=g__graph_busy&&(document.getElementById("checkbox_time").disabled=!1,document.getElementById("rac_select").disabled=!1,document.getElementById("rac_select_dash").disabled=!1,g__graph_busy=0)}
function FindColor(b,a){for(var c=0;c<a.length;c++)if(a[c][0]===b)return a[c][1];return-1}function AddGraphColor(b,a){var c=this.FindColor(b,a);if(-1===c){c=[];c[0]=b;var d=unusedColorIndex(a);c[1]=d;a.push(c);return d}return c}function RemoveColor(b,a){for(var c=0;c<a.length;c++)a[c][0]===b&&a.splice(c,1)}function unusedColorIndex(b){for(var a=0;7>a;a++){found=!1;for(var c=0;c<b.length;c++)if(b[c][1]===a){found=!0;break}if(!found)return a}return 0}
function hideGraphs(){$("#graph_sh").hide();$("#graph_first_time_sh").html(text_graph_first_time);g_graphs_hidden=!0}function showGraphs(){g_graphs_hidden&&($("#graph_sh").show(),$("#graph_first_time_sh").html(""),g_graph.GraphType(g_table_type),resetGraph(),$("#graph_rac").highcharts().reflow(),$("#graph_credit").highcharts().reflow());g_graphs_hidden=!1};function phpSendData(b,a){var c=g_siteUrl+"/php/interface.php",d=new XMLHttpRequest;d.onreadystatechange=function(){4===d.readyState&&a(d.responseText)};d.open("POST",c,!0);d.setRequestHeader("X-File-Name","snl");d.setRequestHeader("Content-Type","application/json; charset=utf-8");d.timeout=1E4;d.send(b)}
function phpSendDataArg(b,a,c,d,e,f){var g=g_siteUrl+"/php/interface.php",h=new XMLHttpRequest;h.onreadystatechange=function(){4===h.readyState&&c(b,h.responseText,d,e,f)};h.open("POST",g,!0);h.setRequestHeader("X-File-Name","snl");h.setRequestHeader("Content-Type","application/json; charset=utf-8");h.timeout=1E4;h.send(a)};var CLASS_SELECTED="selected",TABLE_USER="USER",TABLE_TEAMS_ALL="TEAM_ALL",TABLE_USERS_ALL="USERS_ALL",TABLE_COUNTRIES_ALL="COUNTRIES_ALL",TABLE_COUNTRY="COUNTRY",SORTING_TABLE_TEAM=0,SORTING_TABLE_USER=1,SORTING_TABLE_USER_ALL=2,SORTING_TABLE_COUNTRIES_ALL=3,SORTING_TABLE_COUNTRIES=4,PARA_TEAM="team",PARA_TEAM_NAME="team_name",PARA_TEAMS_ALL="all_teams",PARA_USERS_ALL="all_users",PARA_COUNTRIES_ALL="all_countries",PARA_COUNTRY="country",PARA_PAGE_LENGTH="page_len",PARA_PAGE_NR="page_nr",PARA_SELECT=
"sel",PARA_ORDER="ord_col",PARA_ORDER_DIR="ord_dir",COLUMN_TEAM=0,COLUMN_ALL_TEAM=1,COLUMN_COUNTRY=2,COLUMN_USER=3,TEAM_SNL="30190",g_siteUrl,g_hide_table=!1,g_selected=[],g_table,g_initSorting=!0,g_table_interval_timer=null,g_current_table=null,g_team_id,g_team_name,g_team_same=!1,g_table_type=TABLE_TEAMS_ALL,g_page_length=10,g_page_nr=null,g_page_nr_type=TABLE_TEAMS_ALL,g_location_base="";$(document).ready(function(){UrlChange();window.onpopstate=function(b){UrlChange()}});
function UrlChange(){getUrlData();language();$('[id^="checkbox"]').change(function(b){"checkbox_time"===this.id&&($(this).is(":checked")?($("#graph_credit").hide(),$("#graph_credit_text").hide(),$("#graph_credit_tm").show(),$("#graph_credit_tm_text").show(),g_time_machine=!0):($("#graph_credit").show(),$("#graph_credit_text").show(),$("#graph_credit_tm_text").hide(),$("#graph_credit_tm").hide(),g_time_machine=!1),rebuildGraphs())});$("#graph_credit_tm_text").hide();$("#graph_credit_tm").hide();$('[name ="checkbox1"]').change(function(b){$(this).is(":checked")});
$("#button_language").click(function(){languageToggle()});$("#button_all_teams").click(function(){g_table_type!=TABLE_TEAMS_ALL&&(g_table_type=TABLE_TEAMS_ALL,g_page_nr=null,removeSelected(),setParameters(),UrlChange())});$("#button_all_users").click(function(){g_table_type!=TABLE_USERS_ALL&&(g_table_type=TABLE_USERS_ALL,g_page_nr=null,removeSelected(),setParameters(),UrlChange())});$("#button_all_countries").click(function(){g_table_type!=TABLE_COUNTRIES_ALL&&(g_table_type=TABLE_COUNTRIES_ALL,g_page_nr=
null,removeSelected(),setParameters(),UrlChange())});$("#button_info").click(function(){showSelectedInfo()});$("#button_status").click(function(){window.open(g_siteUrl+"status.php","_blank")});$("#text_solid_line").html(text_solid_line);$("#text_dash_line").html(text_dash_line);$("#text_cedit_future").html(text_cedit_future);$("#text_all_teams").html(text_all_teams);$("#text_all_users").html(text_all_users);$("#text_table_info").html(text_table_info);$("#text_table_status").html(text_table_status);
$("#text_all_countries").html(text_all_countries);$("#text_credits").html(text_credits);$("#text_credits_future").html(text_credits_future);getSiteUrl();$("#button").click(function(){table.row("."+CLASS_SELECTED).remove().draw(!1)});hideTables();hideGraphs();switchMode(g_table_type);tableCheckStartTimer()}
function getUrlData(){var b=decodeURIComponent(window.location.href);g_location_base=b;var a,c,d,e,f,g=0,h=-1,k="";e=PARA_SELECT+"0=";if(0<b.length){b=b.split("#");if(2>b.length)return;a=b[1].split("&");b=a.length;for(c=0;c<b;c++)if(f=a[c].lastIndexOf(PARA_TEAM+"="),0===f&&(0==g?(g_team_id=a[c].substr(PARA_TEAM.length+1),g_table_type=TABLE_USER,g++):g_team_name=decodeURIComponent(a[c].substr(PARA_TEAM.length+1))),f=a[c].lastIndexOf(PARA_TEAM_NAME+"="),0===f&&(g_team_name=decodeURIComponent(a[c].substr(PARA_TEAM_NAME.length+
1))),f=a[c].lastIndexOf(PARA_TEAMS_ALL),0===f&&(g_team_id=a[c].substr(PARA_TEAM.length),g_table_type=TABLE_TEAMS_ALL),f=a[c].lastIndexOf(PARA_USERS_ALL),0===f&&(g_team_id=a[c].substr(PARA_USERS_ALL.length),g_table_type=TABLE_USERS_ALL),f=a[c].lastIndexOf(PARA_COUNTRIES_ALL),0===f&&(g_table_type=TABLE_COUNTRIES_ALL),f=a[c].lastIndexOf(PARA_COUNTRY+"="),0===f&&(g_country_id=a[c].substr(PARA_COUNTRY.length+1),g_table_type=TABLE_COUNTRY),f=a[c].lastIndexOf(PARA_PAGE_LENGTH+"="),0===f&&(g_page_length=
a[c].substr(PARA_PAGE_LENGTH.length+1),setPageLength()),f=a[c].lastIndexOf(PARA_PAGE_NR+"="),0===f&&(g_page_nr=a[c].substr(PARA_PAGE_NR.length+1),setPageNr()),f=a[c].lastIndexOf(PARA_ORDER+"="),0===f&&(h=a[c].substr(PARA_ORDER.length+1),setPageNr()),f=a[c].lastIndexOf(PARA_ORDER_DIR+"="),0===f&&(k=a[c].substr(PARA_ORDER_DIR.length+1),setPageNr()),f=a[c].lastIndexOf(e),0===f)for(g_selected.push(a[c].substr(e.length)),d=1;d<b-c;d++)if(e=PARA_SELECT+d+"=",f=a[c+d].lastIndexOf(e),0===f)g_selected.push(a[c+
d].substr(e.length));else break}0<h.length&&0<k.length?0<h&&(g_order=h,g_order_dir=k):g_order=-1}
function setParameters(){var b={},a,c;switch(g_table_type){case TABLE_USER:b[PARA_TEAM]=g_team_id;b[PARA_TEAM_NAME]=encodeURIComponent(g_team_name);break;case TABLE_USERS_ALL:b[PARA_USERS_ALL]="1";break;case TABLE_COUNTRIES_ALL:b[PARA_COUNTRIES_ALL]="1";break;case TABLE_COUNTRY:b[PARA_COUNTRY]="1";break;default:b[PARA_TEAMS_ALL]="1"}b[PARA_PAGE_LENGTH]=g_page_length;null!=g_page_nr&&(b[PARA_PAGE_NR]=g_page_nr);c=g_selected.length;for(a=0;a<c;a++)sel=g_selected[a],b[PARA_SELECT+a]=sel;a=getTypeId();
a=getTable(a);isUndefined(a)||(c=a.order(),0<c.length&&(Array.isArray(c[0])&&(c=a=c[0]),a=c[0],c=c[1],b[PARA_ORDER]=a,b[PARA_ORDER_DIR]=c));history.pushState({},"","#"+$.param(b))}function getTypeId(){switch(g_table_type){case TABLE_USER:id="#seti_table_user";break;case TABLE_USERS_ALL:id="#seti_table_users_all";break;case TABLE_COUNTRIES_ALL:id="#seti_table_countries_all";break;case TABLE_COUNTRY:id="#seti_table_country";break;default:id="#seti_table_teams_all"}return id}
function getTable(b){return $.fn.DataTable.isDataTable(b)?b=$(b).DataTable():null}function changedLength(){removeSelected();setParameters()}function setPageLength(){var b;b=getTypeId();b=getTable(b);null!=b&&b.page.info().length!=g_page_length&&b.page.len(g_page_length).draw()}function setPageUrl(b){b=getTypeId();b=getTable(b);null!==b&&(g_page_nr=b.page.info().page+1);removeSelected();setParameters()}
function changedSorting(b,a,c){g_initSorting?g_initSorting=!1:(storeSorting(b,a,c),removeSelected(),setParameters())}function setPageNr(){}function removeSelected(){g_selected=[];var b=getTypeId();$(b+" tbody tr").removeClass(CLASS_SELECTED)}
function switchMode(){hideTables();switch(g_table_type){case TABLE_USER:g_table_user_init?g_team_same||tableUserSet(g_team_id,g_team_name):(g_current_table=tableUser(g_team_id,g_team_name),capture_u=new captureTableSelections("seti_table_user",g_table_user));g_table_user_init=!0;$("#seti_table_user_sh").show();g_current_table=g_table_user;break;case TABLE_USERS_ALL:g_table_users_all_init?tableUsersAllHeader():(g_current_table=tableUsersAll(),capture_tua=new captureTableSelections("seti_table_users_all",
g_table_users_all));g_table_users_all_init=!0;$("#seti_table_users_all_sh").show();g_current_table=g_table_users_all;break;case TABLE_COUNTRIES_ALL:g_table_countries_all_init?tableCountriesAllHeader():(tableCountriesAll(),capture_ca=new captureTableSelections("seti_table_countries_all",g_table_countries_all));g_table_countries_all_init=!0;$("#seti_table_countries_all_sh").show();g_current_table=g_table_countries_all;break;case TABLE_COUNTRY:g_table_country_init?tableCountryHeader(g_country_id):(tableCountry(g_country_id),
capture_c=new captureTableSelections("seti_table_country",g_table_country));g_table_country_init=!0;$("#seti_table_country_sh").show();g_current_table=g_table_country;break;default:g_table_teams_all_init?tableTeamsAllHeader():(tableTeamsAll(),capture_ta=new captureTableSelections("seti_table_teams_all",g_table_teams_all)),g_table_teams_all_init=!0,$("#seti_table_teams_all_sh").show(),g_current_table=g_table_teams_all}rebuildGraphs()}
function hideTables(){$("#seti_table_teams_all_sh").hide();$("#seti_table_user_sh").hide();$("#seti_table_user_sh").hide();$("#seti_table_users_all_sh").hide();$("#seti_table_countries_all_sh").hide();$("#seti_table_country_sh").hide()}
function captureTableSelections(b,a){item_text="#"+b+" tbody";$(item_text).on("click","td",function(){g_select_column=-1;$(this).hasClass("team")&&(g_select_column=COLUMN_TEAM)});$(item_text).on("click","tr",function(){showGraphs();var b=void 0===a.fnGetData?a.row(this).data():a.fnGetData(this),d=b[0],b=b[1];if(g_select_column===COLUMN_TEAM)g_table_type=TABLE_USER,g_team_same=g_team_id===d?!0:!1,g_team_id=d,g_team_name=b,switchMode(),setParameters();else if(g_select_column===COLUMN_ALL_TEAM)g_table_type=
TABLE_TEAMS_ALL,switchMode(),setParameters();else if(g_select_column===COLUMN_COUNTRY)g_table_type=TABLE_COUNTRY,g_country_id=d,switchMode(),setParameters();else{var e=$.inArray(d,g_selected);-1===e?g_selected.push(d):g_selected.splice(e,1);setParameters();$(this).toggleClass(CLASS_SELECTED);processGraph(d,b)}})}
function showSelectedInfo(){var b=!1,a=g_current_table,c;void 0===a.fnGetData?c=a:(b=!0,c=a.fnGetData());a.$("tr").each(function(){if(0<this.className.indexOf("selected")){if(!1===b)var a=c.row(this).data(),e=a[0],a=a[1];else a=this._DT_RowIndex,e=c[a][0],a=c[a][1];e=""+e+"<br>"+a+"<br><br>";$("#seti_info_field").html(e)}})}function tableCheckStartTimer(){null===g_table_interval_timer&&(g_table_interval_timer=setInterval("tableIntervalTimer()",4E3))}
function tableIntervalTimer(){var b=!1,a=g_current_table,c;void 0===a.fnGetData?c=a:(b=!0,c=a.fnGetData());a.$("tr").each(function(){if(0>this.className.indexOf("selected")){var a=!1===b?c.row(this).data()[1]:c[this._DT_RowIndex][1];g_graph.RemoveFromGraph(a)}})}function addUserColumn(b,a,c){b="<div  class='b_gou ' title="+text_tool_users+">"+b+"</>";$("td",a).eq(c).html(b)}function addTeamColumn(b,a,c){}
function addFlags(b,a,c){var d="",e=b;switch(b){case "INT":case "QY":case "QQ":e="INT";break;case "AR":d="flag-ar";break;case "AT":d="flag-at";break;case "AU":d="flag-au";break;case "BA":d="flag-ba";break;case "BE":d="flag-be";break;case "BG":d="flag-bg";break;case "BR":d="flag-br";break;case "CA":d="flag-ca";break;case "CH":d="flag-ch";break;case "CL":d="flag-cl";break;case "CN":d="flag-cn";break;case "CZ":d="flag-cz";break;case "DE":d="flag-de";break;case "DK":d="flag-dk";break;case "EE":d="flag-ee";
break;case "EG":d="flag-eg";break;case "ES":d="flag-es";break;case "FI":d="flag-fi";break;case "FR":d="flag-fr";break;case "GB":d="flag-gb";break;case "GR":d="flag-gr";break;case "GU":d="flag-gu";break;case "HK":d="flag-hk";break;case "HR":d="flag-hr";break;case "HU":d="flag-hu";break;case "IE":d="flag-ie";break;case "IL":d="flag-il";break;case "IN":d="flag-in";break;case "IS":d="flag-is";break;case "IT":d="flag-it";break;case "JO":d="flag-jo";break;case "JP":d="flag-jp";break;case "KR":d="flag-kr";
break;case "LT":d="flag-lt";break;case "LV":d="flag-lv";break;case "MX":d="flag-mx";break;case "MY":d="flag-my";break;case "NL":d="flag-nl";break;case "NO":d="flag-no";break;case "NZ":d="flag-nz";break;case "PA":d="flag-pa";break;case "PL":d="flag-pl";break;case "PT":d="flag-pt";break;case "RO":d="flag-ro";break;case "RS":d="flag-rs";break;case "RU":d="flag-ru";break;case "SG":d="flag-sg";break;case "TH":d="flag-th";break;case "TZ":d="flag-tz";break;case "US":d="flag-us";break;case "SE":d="flag-se";
break;case "SI":d="flag-si";break;case "SK":d="flag-sk";break;case "TT":d="flag-tt";break;case "TW":d="flag-tw";break;case "UA":d="flag-ua";break;case "VE":d="flag-ve";break;case "ZA":d="flag-za"}""!==d&&(e=b+"  <img src='js/lib/flags/blank.png' class='flag "+d+"'/>");$("td",a).eq(c).html(e)}function addComma(b,a,c){data_c=b.toString().replace(/\B(?=(\d{3})+(?!\d))/g,"`");$("td",a).eq(c).html(data_c)};var g_table_teams_all_init=!1,g_table_user_init=!1,g_table_users_all_init=!1,g_table_countries_all_init=!1,g_table_country_init=!1;function tableTeamsAllHeader(){$("#text_table").html(text_all_teams)}
function tableTeamsAll(){var b=!0;tableTeamsAllHeader();$("#seti_table_teams_all").on("order.dt",function(b,a,c){changedSorting(SORTING_TABLE_TEAM,c[0].col,c[0].dir)});$("#seti_table_teams_all").on("length.dt",function(b,a,c){g_page_length=c;changedLength()});$("#seti_table_teams_all").on("page.dt",function(){setPageUrl("#seti_table_teams_all");removeSelected()});var a=getSorting(SORTING_TABLE_TEAM,[[4,"asc"]]),c;null==g_page_nr?c=0:(c=g_page_length*(g_page_nr-1),0>c&&(c=0));g_table_teams_all=$("#seti_table_teams_all").dataTable({createdRow:function(b,
a,c){addComma(a[2],b,1);addComma(a[3],b,2);addFlags(a[6],b,5);addUserColumn(a[7],b,6)},serverSide:!0,ajax:g_siteUrl+"php/data_tables/server_list_all_teams.php",fnDrawCallback:function(a){b&&(b=!1,addSelectedClass())},aLengthMenu:[[10,15,20,25,50,100,200,500],[10,15,20,25,50,100,200,500]],iDisplayLength:g_page_length,displayStart:c,processing:!1,bAutoWidth:!1,aoColumns:[{sTitle:"Id",visible:!1,searchable:!1},{sTitle:table_name,sClass:"right",sWidth:"100px"},{sTitle:table_total,sClass:"right",sWidth:"40px"},
{sTitle:table_rac,sClass:"right",sWidth:"40px"},{sTitle:table_team_rac,sClass:"right",sWidth:"10px"},{sTitle:table_team_total,sClass:"right",sWidth:"10px"},{sTitle:table_country,sClass:"right",sWidth:"20px"},{sTitle:table_members,sClass:"right team",sWidth:"10px"}],order:a})}function tableUserHeader(b,a){$("#text_table").html(text_one_team+a+" ("+b+")")}
function tableUserSet(b,a){tableUserHeader(b,a);g_table_user.ajax.url(b===TEAM_SNL?g_siteUrl+"php/data_tables/server_list_snl_team.php":g_siteUrl+"php/data_tables/server_list_other_team.php");g_table_user.ajax.reload()}
function tableUser(b,a){var c=!0;tableUserHeader(b,a);$("#seti_table_user").on("order.dt",function(a,b,c){changedSorting(SORTING_TABLE_USER,c[0].col,c[0].dir)});$("#seti_table_user").on("length.dt",function(a,b,c){g_page_length=c;changedLength()});$("#seti_table_user").on("page.dt",function(){setPageUrl("#seti_table_user");removeSelected()});var d;d=b===TEAM_SNL?g_siteUrl+"php/data_tables/server_list_snl_team.php":g_siteUrl+"php/data_tables/server_list_other_team.php";var e=getSorting(SORTING_TABLE_USER,
[[4,"asc"]]),f;null==g_page_nr?f=0:(f=g_page_length*(g_page_nr-1),0>f&&(f=0));g_table_user=$("#seti_table_user").DataTable({createdRow:function(a,c,d){addComma(c[2],a,1);addComma(c[3],a,2);b==TEAM_SNL?addFlags(c[7],a,6):addFlags(c[6],a,5);addTeamColumn(c[5],a,5)},serverSide:!0,ajax:{url:d,data:function(a){g_team_id!=TEAM_SNL&&(a.team=g_team_id)}},fnDrawCallback:function(a){c&&(c=!1,addSelectedClass())},language:{url:table_lang_file},aLengthMenu:[[10,15,20,25,50,100,200,500],[10,15,20,25,50,100,200,
500]],iDisplayLength:g_page_length,displayStart:f,processing:!1,bAutoWidth:!1,aoColumns:[{sTitle:"Id",visible:!1,searchable:!1},{sTitle:table_name,sClass:"right",sWidth:"100px"},{sTitle:table_total,sClass:"right",sWidth:"60px"},{sTitle:table_rac,sClass:"right",sWidth:"60px"},{sTitle:table_team_rac,sClass:"center at",sWidth:"30px"},{sTitle:table_team_total,sClass:"center at",sWidth:"30px"},{sTitle:overtake,visible:!1,sClass:"center at",sWidth:"10px"},{sTitle:table_country,sClass:"center",sWidth:"20px"}],
order:e});b==TEAM_SNL&&g_table_user.column(6).visible(!0)}function tableUsersAllHeader(){$("#text_table").html(text_all_users)}
function tableUsersAll(){var b=!0;tableUsersAllHeader();var a;a=g_siteUrl+"php/data_tables/server_list_all_users.php";$("#seti_table_users_all").on("order.dt",function(a,b,c){changedSorting(SORTING_TABLE_USER_ALL,c[0].col,c[0].dir)});$("#seti_table_users_all").on("length.dt",function(a,b,c){g_page_length=c;changedLength()});$("#seti_table_users_all").on("page.dt",function(){setPageUrlUrl("#seti_table_users_all");removeSelected()});var c=getSorting(SORTING_TABLE_USER_ALL,[[4,"asc"]]),d;null==g_page_nr?
d=0:(d=g_page_length*(g_page_nr-1),0>d&&(d=0));g_table_users_all=$("#seti_table_users_all").dataTable({createdRow:function(a,b,c){addFlags(b[11],a,10);addComma(b[2],a,1);addComma(b[3],a,2)},serverSide:!0,ajax:{url:a},fnDrawCallback:function(a){b&&(b=!1,addSelectedClass())},order:c,language:{url:table_lang_file},aLengthMenu:[[10,15,20,25,50,100,200,500],[10,15,20,25,50,100,200,500]],iDisplayLength:g_page_length,displayStart:d,processing:!1,bAutoWidth:!1,aoColumns:[{sTitle:"Id",visible:!1,searchable:!1},
{sTitle:table_name,sClass:"right",sWidth:"100px"},{sTitle:table_total,sClass:"right",sWidth:"60px"},{sTitle:table_rac,sClass:"right",sWidth:"60px"},{sTitle:table_team_rac,sClass:"center at",sWidth:"30px"},{sTitle:table_team_total,sClass:"center at",sWidth:"30px"},{sTitle:table_country_rac,sClass:"center at",sWidth:"30px"},{sTitle:table_country_total,sClass:"center at",sWidth:"30px"},{sTitle:table_world_rac,sClass:"center at",sWidth:"30px"},{sTitle:table_world_total,sClass:"center at",sWidth:"30px"},
{sTitle:table_team,sClass:"center",sWidth:"20px"},{sTitle:table_country,sClass:"center",sWidth:"20px"}]})}function tableCountriesAllHeader(){$("#text_table").html(text_all_countries)}
function tableCountriesAll(){var b=!0;tableCountriesAllHeader();$("#seti_table_countries_all").on("order.dt",function(a,b,c){changedSorting(SORTING_TABLE_COUNTRIES_ALL,c[0].col,c[0].dir)});$("#seti_table_countries_all").on("page.dt",function(){setPageUrl("#seti_table_countries_all");removeSelected()});var a;a=g_siteUrl+"php/data_tables/server_list_all_countries.php";var c=getSorting(SORTING_TABLE_COUNTRIES_ALL,[[4,"asc"]]),d;null==g_page_nr?d=0:(d=g_page_length*(g_page_nr-1),0>d&&(d=0));g_table_countries_all=
$("#seti_table_countries_all").dataTable({createdRow:function(a,b,c){addFlags(b[0],a,0);addComma(b[1],a,1);addComma(Math.round(b[2]),a,2);addUserColumn(b[5],a,5)},serverSide:!0,ajax:{url:a},fnDrawCallback:function(a){b&&(b=!1,addSelectedClass())},order:c,language:{url:table_lang_file},aLengthMenu:[[10,15,20,25,50,100,200,500],[10,15,20,25,50,100,200,500]],iDisplayLength:g_page_length,displayStart:d,processing:!1,bAutoWidth:!1,aoColumns:[{sTitle:table_country,sClass:"center",sWidth:"20px"},{sTitle:table_total,
sClass:"right",sWidth:"60px"},{sTitle:table_rac,sClass:"right",sWidth:"60px"},{sTitle:table_country_rac,sClass:"center ",sWidth:"30px"},{sTitle:table_country_total,sClass:"center ",sWidth:"30px"},{sTitle:table_members,sClass:"center cm",sWidth:"10px"}]})}function tableCountryHeader(b){$("#text_table").html(text_one_country+b)}
function tableCountry(b){var a=!0;tableCountryHeader(b);var c;c=g_siteUrl+"php/data_tables/server_list_country.php";$(d).on("order.dt",function(a,b,c){changedSorting(SORTING_TABLE_COUNTRIES,c[0].col,c[0].dir)});var d="#seti_table_country";$(d).on("length.dt",function(a,b,c){g_page_length=c;changedLength()});$(d).on("page.dt",function(){setPageUrl(d);removeSelected()});var e=getSorting(SORTING_TABLE_COUNTRIES,[[4,"asc"]]),f;null==g_page_nr?f=0:(f=g_page_length*(g_page_nr-1),0>f&&(f=0));g_table_country=
$(d).dataTable({createdRow:function(a,b,c){addComma(b[2],a,1);addComma(b[3],a,2);addFlags(b[6],a,5);addTeamColumn(b[5],a,5)},serverSide:!0,ajax:{url:c,data:function(a){a.country=b}},fnDrawCallback:function(b){a&&(a=!1,addSelectedClass())},order:e,language:{url:table_lang_file},aLengthMenu:[[10,15,20,25,50,100,200,500],[10,15,20,25,50,100,200,500]],iDisplayLength:g_page_length,displayStart:f,processing:!1,bAutoWidth:!1,aoColumns:[{sTitle:"Id",visible:!1,searchable:!1},{sTitle:table_name,sClass:"right",
sWidth:"100px"},{sTitle:table_total,sClass:"right",sWidth:"60px"},{sTitle:table_rac,sClass:"right",sWidth:"60px"},{sTitle:table_country_rac,sClass:"center",sWidth:"30px"},{sTitle:table_country_total,sClass:"center",sWidth:"30px"},{sTitle:table_team,sClass:"center",sWidth:"20px"}]})}function getSorting(b,a){var c,d;d=[];if(0<=g_order)c=[],c[0]=g_order,c[1]=g_order_dir;else if(c=readSorting(b),null==c)return a;c=validateSorting(c,a);d.push(c);return d}
function validateSorting(b,a){return 0>b[0]?a:"asc"==b[1]||"desc"==b[1]?b:a}function addSelectedClass(){var b,a,c;c=getTypeId();var d=$(c).DataTable(),e=[];a=g_selected.length;0<a&&showGraphs();d.rows(function(d,g,h){for(b=0;b<a;b++)if(c=g[0],c==g_selected[b]){$(h).addClass(CLASS_SELECTED);processGraph(c,g[1]);e.push(c);break}}).data();g_selected=e;setParameters()};