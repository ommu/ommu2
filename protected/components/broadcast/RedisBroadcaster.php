<?php
declare(strict_types=1);
/**
 * RedisBroadcaster class
 *
 * Komponen ini akan mengirim data ke redis sehingga aplikasi sweeto agent dapat meneruskannya
 * ke server websocket.
 *
 * NOTE:
 * Untuk sementara baru mendukung tipe data string dan json, untuk file binary menyusul
 *
 * @author    Agus Susilo
 * @copyright Copyright (c) 2017 Agus Susilo
 */
namespace app\components\broadcast;

use Yii;
use app\libraries\http\Client;
use yii\httpclient\Client as HttpClient;

class RedisBroadcaster extends \yii\base\Component
{
    const DATA_STRING = 1;
    const DATA_BINARY = 2;
    const DATA_JSON   = 3;

    /**
     * @var Predis\Client menyimpan koneksi dari database redis
     */
    private static $client = null;
    /**
     * @var string menyimpan grup server pada websocket server yg nantinya akan dikirimi data dari php.
     */
    public $serverGroup = 'ss-rmhb-group-default';
    /**
     * @var string chanel redis yang akan dikirim data oleh aplikasi PHP.
     */
    public $channel = 'ss-rmhb-group-default:_ss_broadcasts';
    /**
     * @var string nama event yang dikirim oleh php, misalnya update-user, update-statistic
     */
    public $eventName;

    /**
     * Buat koneksi ke redis
     *
     * @return Predis\Client
     */
    public function connect()
    {
        if (self::$client == null) {
            self::$client = new \Predis\Client([
                'schema' => 'tcp',
                'host'   => Yii::$app->params['broadcaster']['server'],
                'port'   => Yii::$app->params['broadcaster']['port'],
            ]);
        }
        return $this;
    }

    /**
     * Mengembalikan koneksi redis
     *
     * @return Predis\Client
     */
    public function getClient()
    {
        $this->connect();
        return self::$client;
    }

    /**
     * Ubah $payload sesuai dengan $dataType
     *
     * @param mixed $payload data yg akan diubah
     * @param int $dataType tipe data
     * @return mixed data sesuai tipe datanya
     */
    protected function getData($payload, $dataType = 1)
    {
        $data = '';
        if ($dataType == self::DATA_STRING) {
            $data = base64_encode($payload);
        } elseif ($dataType == self::DATA_BINARY) {
        } elseif ($dataType == self::DATA_JSON) {
            $data = json_encode($payload);
            $data = base64_encode($data);
        }
        return $data;
    }

    /**
     * Broadcast akan mengirim data ke seluruh pengguna yg terhubung ke websocket
     *
     * @param mixed $payload data yang dikirim ke server
     * @param int $dataType
     */
    public function broadcast($payload, $dataType = 1)
    {
        $data = $this->getData($payload, $dataType);

        $this->eventName = 'broadcast';
        if ($dataType == self::DATA_BINARY) {
            $this->eventName = 'broadcastbin';
        } elseif ($dataType == self::DATA_JSON) {
            $this->eventName = 'broadcastjson';
        }

        $t = [
            'd' => $dataType, //DataType
            's' => $this->serverGroup, //ServerName
            'e' => $this->eventName, //EventName
            'r' => '', //RoomName
            'p' => $data,
        ];

        $msg = json_encode($t);
        $this->getClient()->publish($this->channel, $msg);
    }

    /**
     * Roomcast akan mengirim data ke channel/room tertentu, hanya user yg ada pada
     * channel/room tersebut yg akan mendapat pesan dari server.
     *
     * @param string $roomName nama room yg akan dikirim data
     * @param mixed $payload data yang akan dikirim
     * @param int $dataType tipe data
     * @return void
     */
    public function roomcast($roomName, $payload, $dataType = 1)
    {
        $data = $this->getData($payload, $dataType);

        $this->eventName = 'roomcast';
        if ($dataType == self::DATA_BINARY) {
            $this->eventName = 'roomcastbin';
        } elseif ($dataType == self::DATA_JSON) {
            $this->eventName = 'roomcastjson';
        }

        $t = [
            'd' => $dataType, //DataType
            's' => $this->serverGroup, //ServerName
            'e' => $this->eventName, //EventName
            'r' => $roomName, //RoomName
            'p' => $data,
        ];

        $msg = json_encode($t);
        $this->getClient()->publish($this->channel, $msg);
    }

    public function task($payload)
    {
        $data = $this->getData($payload, 3);

        $this->eventName = 'task';
        $t = [
            'd' => 3, //DataType
            's' => $this->serverGroup, //ServerName
            'e' => $this->eventName, //EventName
            'r' => '', //RoomName
            'p' => $data,
        ];

        $msg = json_encode($t);
        $this->getClient()->publish($this->channel, $msg);
    }

    /**
     * emit event to redis backend
     *
     * @param string $eventName nama event
     * @param array $payload data yang akan dipush ke redis server
     *        owner_id, owner_room_id, receiver_id, message
     * @return Response
     */
    public function emit(string $eventName, array $payload): \yii\httpclient\Response
    {
        $client = Client::getClient();
        $payload = array_merge($payload, [
            'event_name' => $eventName,
		]);

		$protocol = 'https:/';
		if (!Yii::$app->request->isConsoleRequest) {
			$protocol = Yii::$app->request->isSecureConnection ? 'https:/': 'http:/';
		}

        $response = $client->createRequest()
            ->setFormat(HttpClient::FORMAT_JSON)
            ->setMethod('POST')
            ->setUrl(join('/', [$protocol, Yii::$app->params['broadcaster']['push_server'], 'v1/emit']))
            ->setData($payload)
            ->send();

        if (!$response->isOk) {
            Yii::error('Error emit ws: statusCode => ' . $response->statusCode . ', response body => ' . $response->content);
        }

        return $response;
    }
}
