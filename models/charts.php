<?php

## Tous les membres du site
$all_ = $user->getStatistics($db, 1, false, true);

## Tous les membres actifs
# $active_x = round($user->getStatistics($db, 1)*100 / $all_);

## Tous les membres inactifs
$inactive_x = round($user->getStatistics($db, 0, false)*100 / $all_);
#$banned_x = round($user->getStatistics($db, 1, 998)*100 / $all_) + round($user->getStatistics($db, 0, 998)*100 / $all_);

## Tous les membres actifs par catégorie
$admin_x = round($user->getStatistics($db, 1, 1)*100 / $all_);
$free_x = $admin_x + round($user->getStatistics($db, 1, 4)*100 / $all_);
$silver_x = round($user->getStatistics($db, 1, 3)*100 / $all_);
$gold_x = round($user->getStatistics($db, 1, 2)*100 / $all_);
$active_x =  $free_x + $silver_x + $gold_x;

## nombre de surfeurs.
$nbs = $surfbar->countNowSurfbarSurfers($db, $sites);

// SURFBAR
$query=$db->prepare("SELECT id FROM sessions", []);
$nbconnected = $query->rowCount();
// ON LINE

?>
<?php

echo "<script type='text/javascript'>

$(function() {
	Highcharts.setOptions({colors:['#676767', '#fff', 'yellow', 'blue', 'red', '".$colors."']});
	var colors = Highcharts.getOptions().colors,
		categories = ['INACTIVE', 'ACTIVE'],
		data = [{
	   	 y:  $inactive_x,
	   	 color: colors[0],
	   	 drilldown: {
	   		 name: 'INACTIVE',
	   		 categories: ['INACTIVE'],
	   		 data: [$inactive_x],
	   		 color: colors[0]
	   	 }
		},{
	   	 y:  $active_x,
	   	 color: colors[5],
	   	 drilldown: {
	   		 name: 'ACTIVE',
	   		 categories: ['FREE', 'SILVER', 'GOLD'],
	   		 data: [$free_x, $silver_x, $gold_x],
	   		 color: colors[0, 3, 2]
	   	 }
		}],
		browserData = [],
		versionsData = [],
		i,
		j,
		dataLen = data.length,
		drillDataLen,
		brightness;


	// Build the data arrays
	for(i=0; i<dataLen; i+=1){

		// add browser data
		browserData.push({
	   	 name: categories[i],
	   	 y: data[i].y,
	   	 color: data[i].color
		});

		// add version data
		drillDataLen = data[i].drilldown.data.length;
		for (j = 0; j < drillDataLen; j += 1) {
	   	 brightness = 0.2 - (j / drillDataLen) / 5;
	   	 versionsData.push({
	   		 name: data[i].drilldown.categories[j],
	   		 y: data[i].drilldown.data[j],
	   		 color: Highcharts.Color(data[i].color).brighten(brightness).get()
	   	 });
		}
	}

	// Create the chart
		new Highcharts.Chart({
	   	 chart : {
	   	 renderTo:'charts',
	   	 type: 'pie',
	   	 backgroundColor:'transparent',
	   	 plotBackgroundColor: null,
	   	 plotBorderWidth: null,
	   	 plotShadow: false
		},
		title: {
	   	 text: 'Users Statistics'
		},
		subtitle: {
	   	 text: ' '
		},
		yAxis: {
	   	 title: {
	   		 text: 'Users Statistics'
	   	 }
		},
		plotOptions: {
	   	 pie: {
	   		 shadow: false,
	   		 center: ['50%', '50%']
	   	 }
		},
		tooltip: {
	   	 valueSuffix: '%'
		},
		series: [{
	   	 name: 'Percentage',
	   	 data: browserData,
	   	 size: '60%',
	   	 dataLabels: {
	   		 formatter: function() {
	   	   	  return this.y > 5 ? this.point.name : null;
	   		 },
	   		 color: '#ffffff',
	   		 distance: -30
	   	 }
		}, {
	   	 name: 'Percentage',
	   	 data: versionsData,
	   	 size: '80%',
	   	 innerSize: '60%',
	   	 dataLabels: {
	   		 formatter: function() {
	   	   	  //display only if larger than 1
	   	   	  return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y + '%' : null;
	   		 }
	   	 }
		}]
	});
});

</script>";

?>