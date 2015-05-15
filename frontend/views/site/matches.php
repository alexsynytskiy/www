<?php
/**
 * @var $this yii\web\View
 * @var $matchDataProvider yii\data\ActiveDataProvider
**/
?>

<div class="search-box default-box">
    <div class="club-select">
        <a href="#">
            <div class="button active">"Динамо" Киев</div>
        </a>
        <a href="#">
            <div class="button">"Динамо" U-19</div>
        </a>
        <a href="#">
            <div class="button">"Динамо-2"</div>
        </a>
        <a href="#">
            <div class="button">"Динамо" М</div>
        </a>
        <a href="#">
            <div class="button">Сборная Украины</div>
        </a>
    </div>
    <div class="box-content">
        <form class="search-matches" action="">           

            <div class="select-championship selectize-box">
                <label for="select-championship">Выбрать турнир</label>
                <select name="championship" id="select-championship" placeholder="Выбрать турнир">
                    <option value="all-champ" selected class="data-default">Все турниры</option>
                    <option value="euro2012">Евро 2012</option>
                    <option value="euro2016">Евро 2016 Отборочный турнир</option>
                    <option value="ukr">Кубок Украины</option>
                    <option value="world2014">Чемпионат мира-2014. Отборочный турнир</option>
                </select>
            </div>

            <div class="select-season selectize-box">
                <label for="select-season">Выбрать сезон</label>
                <select name="season" id="select-season" placeholder="Выбрать сезон">
                    <option value="">Выбрать сезон</option>
                    <option value="2014" selected class="data-default">Cезон 2014/15</option>
                    <option value="2013">Cезон 2013/14</option>
                    <option value="2012">Cезон 2012/13</option>
                    <option value="2011">Cезон 2011/12</option>
                    <option value="2010">Cезон 2010/11</option>
                    <option value="2009">Cезон 2009/10</option>
                    <option value="2008">Cезон 2008/09</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="matches default-box">
	
	<div class="box-content">
        <table class="default-table">
            <thead>
                <tr>
                    <td class="status"></td>
                    <th class="date">Дата</th>
                    <th class="competition">Турнир</th>
                    <th class="home"></th>
                    <th class="logo"></th>
                    <th class="score" style="font-weight:normal;">Счёт</th>
                    <th class="logo"></th>
                    <th class="visitors"></th>
                    <th class="more"></th>
                </tr>
            </thead>
            <tbody>
		
				<?php
				echo \yii\widgets\ListView::widget([
					'dataProvider' => $matchDataProvider,
					'itemOptions' => ['class' => 'item'],
					'itemView' => '@frontend/views/site/match_item',
				    'summary' => '',
				]);
				?>
				
			</tbody>
        </table>
    </div>
</div>