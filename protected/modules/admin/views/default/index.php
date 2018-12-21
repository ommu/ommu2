<?php
/**
 * @var $this yii\web\View
 * @var $this app\modules\admin\controllers\DefaultController
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 22 December 2018, 03:29 WIB
 * @link https://github.com/ommu/ommu
 *
 */

use Yii;
use yii\helpers\Html;
?>

<p>
	This is the view content for action "<?php echo $this->context->action->id ?>".
	The action belongs to the controller "<?php echo get_class($this->context) ?>"
	in the "<?php echo $this->context->module->id ?>" module.
</p>
<p>
	You may customize this page by editing the following file:<br>
	<code><?php echo __FILE__ ?></code>
</p>

<div class="admin-default-index"></div>