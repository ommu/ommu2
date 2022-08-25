<?php declare(strict_types=1);
namespace app\components\jobs;

use Yii;
use yii\base\BaseObject;
use Symfony\Component\Process\Process;
use yii\helpers\ArrayHelper;

/**
 * ComposerJob
 */

class ComposerJob extends BaseObject
{
    public $user_id = 0;
    public $cmd = 'st';
    public $args = [];

    public $revert = 0;

	/**
	 * {@inheritdoc}
	 */
    public function execute()
    {
        if ($this->user_id < 1) {
            return;
        }

        $args = array_filter($this->args);

        if(is_array($args) && count($args)) {
            $cmd = ArrayHelper::merge([$this->cmd], $args);

            chdir(self::repoPath());
            $process = new Process(join(' ', $cmd));
            $process->run(function ($type, $buffer) {
                if (Process::ERR == $type) {
                    Yii::$app->broadcaster->publish("devtool", ['message' => $buffer]);
                } else {
                    Yii::$app->broadcaster->publish("devtool", ['message' => $buffer]);
                }
            });
        }
    }

	/**
	 * {@inheritdoc}
	 */
    private static function repoPath()
    {
        return Yii::getAlias('@webroot');
    }
}
