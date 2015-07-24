<?php
use yii\helpers\Url;
/**
 * @var $this yii\web\View
 * @var $matchDataProvider yii\data\ActiveDataProvider
**/
?>

<div class="search-box default-box">
    <div class="club-select">
        <?php 
            foreach ($selectTeamsOI as $team) {
                if($team->id == $activeTeam) {
                    $active = 'active';
                }
                else {
                    $active = '';
                }
        ?>
            <a href="/matches?team=<?= $team->id ?>">
                <div class="button <?= $active ?>"><?= $team->name ?><div class="select"></div></div>
            </a>
        <?php
            }
        ?>
    </div>
    <div class="box-content">
        <form class="search-matches" action="">
            <input type="hidden" name="team" value="<?= $activeTeam ?>">

            <div class="select-championship selectize-box">
                <label for="select-championship">Выбрать турнир</label>
                <select name="championship" id="select-championship" placeholder="Выбрать турнир">
                    <option value="all-tournaments" selected class="data-default">Все турниры</option>

                    <?php 
                        foreach ($tournaments as $tournament) {
                            if($tournament->id == $activeTournament) {
                                $active = 'selected class="data-default"';
                            }
                            else {
                                $active = '';
                            }
                    ?>
                        <option value="<?= $tournament->id ?>" <?= $active ?>><?= $tournament->name ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>

            <div class="select-season selectize-box">
                <label for="select-season">Выбрать сезон</label>
                <select name="season" id="select-season" placeholder="Выбрать сезон">
                    <option value="">Выбрать сезон</option>
                    <?php 
                        foreach ($seasons as $season) {
                            if($season->id == $activeSeason) {
                                $active = 'selected class="data-default"';
                            }
                            else {
                                $active = '';
                            }
                    ?>
                        <option value="<?= $season->id ?>" <?= $active ?>>Cезон <?= $season->name ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="matches default-box">
	
	<div class="box-content">

    <?php 
        if (count($matchDataProvider->getModels()) == 0) {
            echo "Таких матчей нет";
        }
        else {
    ?>

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
        <?php } ?>
    </div>
</div>