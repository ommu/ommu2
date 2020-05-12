<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * DefaultController
 * @var $this yii\web\View
 *
 * DefaultController implements the CRUD actions for Gii generator.
 * Reference start
 * TOC :
 *	Index
*	View
*	Preview
*	Diff
*
* @author Putra Sudaryanto <putra@ommu.id>
* @contact (+62)856-299-4114
* @copyright Copyright (c) 2017 OMMU (www.ommu.id)
* @created date 24 September 2017, 12:38 WIB
* @modified date 9 October 2017, 11:22 WIB
* @link https://github.com/ommu/ommu
*
*/

namespace app\modules\gii\controllers;

use Yii;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use mdm\admin\components\AccessControl;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DefaultController extends Controller
{
	/**
	 * @var \yii\gii\Module
	 */
	public $module;
	/**
	 * @var \yii\gii\Generator
	 */
	public $generator;

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeAction($action)
	{
		Yii::$app->response->format = Response::FORMAT_HTML;
		return parent::beforeAction($action);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		$this->layout = 'main';

		$this->view->title = 'Welcome to Gii';
		$this->view->description = 'a magical tool that can write code for you';
		return $this->render('admin_index');
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionView($id)
	{
		$generator = $this->loadGenerator($id);
		$params = ['generator' => $generator, 'id' => $id];

		$preview = Yii::$app->request->post('preview');
		$generate = Yii::$app->request->post('generate');
		$answers = Yii::$app->request->post('answers');

		if ($preview !== null || $generate !== null) {
			if ($generator->validate()) {
				$generator->saveStickyAttributes();
				$files = $generator->generate();
				if ($generate !== null && !empty($answers)) {
					$params['hasError'] = !$generator->save($files, (array) $answers, $results);
					$params['results'] = $results;
				} else {
					$params['files'] = $files;
					$params['answers'] = $answers;
				}
			}
		}

		$this->view->title = $generator->getName();
		$this->view->description = $generator->getDescription();
		return $this->render('admin_view', $params);
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionPreview($id, $file)
	{
		$generator = $this->loadGenerator($id);
		if ($generator->validate()) {
			foreach ($generator->generate() as $f) {
				if ($f->id === $file) {
					$content = $f->preview();
					if ($content !== false) {
						return  '<div class="content">' . $content . '</div>';
					}
					return '<div class="error">Preview is not available for this file type.</div>';
				}
			}
		}
		throw new NotFoundHttpException("Code file not found: $file");
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionDiff($id, $file)
	{
		$generator = $this->loadGenerator($id);
		if ($generator->validate()) {
			foreach ($generator->generate() as $f) {
				if ($f->id === $file) {
					return $this->renderPartial('@yii/gii/views/default/diff', [
						'diff' => $f->diff(),
					]);
				}
			}
		}
		throw new NotFoundHttpException("Code file not found: $file");
	}

	/**
	 * Runs an action defined in the generator.
	 * Given an action named "xyz", the method "actionXyz()" in the generator will be called.
	 * If the method does not exist, a 400 HTTP exception will be thrown.
	 * @param string $id the ID of the generator
	 * @param string $name the action name
	 * @return mixed the result of the action.
	 * @throws NotFoundHttpException if the action method does not exist.
	 */
	public function actionAction($id, $name)
	{
		$generator = $this->loadGenerator($id);
		$method = 'action' . $name;
		if (method_exists($generator, $method)) {
			return $generator->$method();
		}
		throw new NotFoundHttpException("Unknown generator action: $name");
	}

	/**
	 * Loads the generator with the specified ID.
	 * @param string $id the ID of the generator to be loaded.
	 * @return \yii\gii\Generator the loaded generator
	 * @throws NotFoundHttpException
	 */
	protected function loadGenerator($id)
	{
		if (isset($this->module->generators[$id])) {
			$this->generator = $this->module->generators[$id];
			$this->generator->loadStickyAttributes();
			$this->generator->load(Yii::$app->request->post());

			return $this->generator;
		}
		throw new NotFoundHttpException("Code generator not found: $id");
	}
}
