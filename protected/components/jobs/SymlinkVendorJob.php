<?php declare(strict_types=1);
namespace app\components\jobs;

use Yii;
use yii\base\BaseObject;
use Symfony\Component\Process\Process;
use yii\helpers\ArrayHelper;

/**
 * SymlinkVendorJob
 */

class SymlinkVendorJob extends BaseObject
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

        $data = $this->args;

        if(is_array($data) && count($data)) {
            foreach ($data as $key => $val) {
                $cmd = [$this->cmd, $key, $val];

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
    }

	/**
	 * {@inheritdoc}
	 */
    private static function repoPath()
    {
        return Yii::getAlias('@webroot');
    }
}
