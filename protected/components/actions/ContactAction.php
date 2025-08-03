<?php
/**
 * ContactAction class
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 9 September 2019, 19:14 WIB
 * @link https://github.com/ommu/ommu2
 */

namespace app\components\actions;

use Yii;
use ommu\support\models\SupportFeedbacks;

class ContactAction extends \yii\base\Action
{
	/**
	 * {@inheritdoc}
	 */
	public $view;

	/**
	 * {@inheritdoc}
	 */
	public $layout;

	/**
	 * {@inheritdoc}
	 */
	public function run()
	{
		$model = new SupportFeedbacks();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		}

		if ($this->layout !== null)
			$this->controller->layout = $this->layout;

		$this->controller->view->title = Yii::t('app', 'Contact Us');
		$this->controller->view->description = '';
		$this->controller->view->keywords = '';

		return $this->renderHtmlResponse(['model' => $model]);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function renderHtmlResponse($params)
	{
		return $this->controller->render($this->view ?: $this->id, $params);
	}
}