<?php

namespace Bertramtruong\Mailtrap;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Illuminate\Support\Facades\Config;

class MailtrapClient extends GuzzleClient
{

    public function __construct(Client $client, $config = [])
    {
        $description = new Description(json_decode(file_get_contents(__DIR__ . '/service.json'), true));
        parent::__construct($client, $description, $config);
    }

    public static function factory($config = [])
    {
        $client = new Client();
        $client->setDefaultOption('headers/Api-Token', Config::get('mailtrap::api_token', ''));
        return new self($client, $config);
    }

    public function getMessages($inboxId = null, $search = null, $page = null, $lastId = null)
    {
        if ($inboxId === null) {
            $inboxId = $this->getDefaultInboxID();
        }

        $request = ['inbox_id' => $inboxId];
        if ($search !== null) {
            $request['search'] = $search;
        }
        if ($page !== null) {
            $request['page'] = $page;
        }
        if ($lastId !== null) {
            $request['last_id'] = $lastId;
        }

        return parent::getMessages($request);
    }

    public function getMessage($id, $inboxId = null)
    {
        if ($inboxId === null) {
            $inboxId = $this->getDefaultInboxID();
        }
        return parent::getMessage(['id' => $id, 'inbox_id' => $inboxId]);
    }

    public function getLastMessage() {
        $messages = $this->getMessages();
        $message = null;
        if (!empty($messages)) {
            $message = $messages[0];
        }
        return $message;
    }

    /**
     * @return int
     */
    public function getDefaultInboxID()
    {
        $inboxId = Config::get('mailtrap::inbox_id', 1);
        return $inboxId;
    }

}