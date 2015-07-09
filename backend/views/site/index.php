<?php
/* @var $this yii\web\View */

$this->title = 'Динамомания';
?>
<div class="site-index">
    <div class="col-md-12">
    	Суточное количество новостей за последние 30 дней	
		<canvas id="news"></canvas>
	</div>
	<div class="col-md-12">
    	Суточное количество комментариев за последние 30 дней	
		<canvas id="comments"></canvas>
	</div>
	<div class="col-md-12">
    	Суточное количество новых пользователей за последние 30 дней	
		<canvas id="newUsers"></canvas>
	</div>
	<script>
		var lineChartDataNews = {
			labels : [<?= $daysNewsDatesString ?>],
			datasets : [
				{
					label: "Новости",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					data : [<?= $daysNewsCountString ?>]
				},
			]
		}

		var lineChartDataComments = {
			labels : [<?= $daysCommentsDatesString ?>],
			datasets : [
				{
					label: "Комментарии",
					fillColor: "rgba(151,187,205,0.2)",
		            strokeColor: "rgba(151,187,205,1)",
		            pointColor: "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data : [<?= $daysCommentsCountString ?>]
				},
			]
		}

		var lineChartDataUsers = {
			labels : [<?= $daysNewUsersDatesString ?>],
			datasets : [
				{
					label: "Пользователи",
					fillColor: "rgba(151,187,205,0.2)",
		            strokeColor: "rgba(151,187,205,1)",
		            pointColor: "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke: "rgba(151,187,205,1)",
					data : [<?= $daysNewUsersCountString ?>]
				},
			]
		}
                                               
		window.onload = function(){
			var ctx = document.getElementById("news").getContext("2d");
			window.myLine = new Chart(ctx).Line(lineChartDataNews, {
				responsive: true,
				animation: true,
			    animationSteps: 60,
			    animationEasing: "easeOutQuart",
			});

			var ctx2 = document.getElementById("comments").getContext("2d");
			window.myLine = new Chart(ctx2).Line(lineChartDataComments, {
				responsive: true,
				animation: true,
			    animationSteps: 60,
			    animationEasing: "easeOutQuart",
			});

			var ctx3 = document.getElementById("newUsers").getContext("2d");
			window.myLine = new Chart(ctx3).Line(lineChartDataUsers, {
				responsive: true,
				animation: true,
			    animationSteps: 60,
			    animationEasing: "easeOutQuart",
			});
		}
	</script>	
</div>
