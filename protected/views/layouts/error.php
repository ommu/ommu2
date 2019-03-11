<?php 
/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
?>

<?php /* @var $this Controller */ ?>
<?php $this->beginContent('@themes/gentelella/layouts/default.php'); ?>

	<?php /* page content */?>
	<div class="col-md-12">
		<div class="col-middle">
			<div class="text-center">

				<?php echo $content ?>

				<div class="mid_center">
					<br/><br/>
					<form>
						<div class="col-xs-12 form-group pull-right top_search">
							<div class="input-group">
								<input type="text" class="form-control" placeholder="Search for...">
								<span class="input-group-btn">
									<button class="btn btn-default" type="button">Go!</button>
								</span>
							</div>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
	<?php /* end.page content */?>

<?php $this->endContent(); ?>