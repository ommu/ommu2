<?php
namespace app\components\widgets;

use Yii;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends \yii\bootstrap\Widget
{
	/**
	 * @var array the alert types configuration for the flash messages.
	 * This array is setup as $key => $value, where:
	 * - key: the name of the session flash variable
	 * - value: the bootstrap alert type (i.e. danger, success, info, warning)
	 */
	public $alertTypes = [
		'error'   => 'alert-danger',
		'danger'  => 'alert-danger',
		'success' => 'alert-success',
		'info'    => 'alert-info',
		'warning' => 'alert-warning'
	];

	public $alertSoftTypes = [
		'error'   => 'alert-soft-danger',
		'danger'  => 'alert-soft-danger',
		'success' => 'alert-soft-success',
		'info'    => 'alert-soft-info',
		'warning' => 'alert-soft-warning'
	];

	/**
	 * @var array the options for rendering the close button tag.
	 * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
	 */
	public $closeButton = [];

	/**
	 * {@inheritdoc}
	 */
	public function run()
	{
		$session = Yii::$app->session;
		$flashes = $session->getAllFlashes();

		$softAlert = isset($this->options['softAlert']) && $this->options['softAlert'] ? true : false;
		$template = isset($this->options['template']) && $this->options['template'] ? true : false;
		$appendClass = isset($this->options['class']) ? ' ' . $this->options['class'] : '';
		if($template)
			$appendClass = $appendClass . ' d-flex';

		$bootstrapClass = 'yii\bootstrap\Alert';
		if(isset(Yii::$app->view->themeSetting['bootstrap4']) && Yii::$app->view->themeSetting['bootstrap4'])
			$bootstrapClass = 'yii\bootstrap4\Alert';

		foreach ($flashes as $type => $flash) {
			if (!isset($this->alertTypes[$type])) {
				continue;
			}

			foreach ((array) $flash as $i => $message) {
				echo $bootstrapClass::widget([
					'body' => $template ? strtr($this->options['template'], ['{message}'=>$message]) : $message,
					'closeButton' => $this->closeButton,
					'options' => array_merge($this->options, [
						'id' => $this->getId() . '-' . $type . '-' . $i,
						'class' => (!$softAlert ? $this->alertTypes[$type] : $this->alertSoftTypes[$type]) . $appendClass,
					]),
				]);
			}

			$session->removeFlash($type);
		}
	}
}
