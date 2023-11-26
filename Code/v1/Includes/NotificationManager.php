<?php

    class NotificationManager {
        private $telegramBotToken;
        private $chatId;
        private $discordWebhookUrl;

        public function __construct($telegramBotToken, $chatId, $discordWebhookUrl) {
            $this->telegramBotToken = $telegramBotToken;
            $this->chatId = $chatId;
            $this->discordWebhookUrl = $discordWebhookUrl;
        }

        public function sendTelegramMessage($message) {
            $url = "https://api.telegram.org/bot{$this->telegramBotToken}/sendMessage";
            $data = array(
                'chat_id' => $this->chatId,
                'text' => $message,
            );

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ),
            );

            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            //$api = $this->telegramBotToken;
            //$result = file_get_contents("https://api.telegram.org/bot$api/sendMessage?".http_build_query($data));

            return $result;
        }

        public function sendDiscordWebhook($message) {
            $data = array('content' => $message);
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => json_encode($data),
                ),
            );

            $context  = stream_context_create($options);
            $result = file_get_contents($this->discordWebhookUrl, false, $context);

            return $result;
        }
    }

    // Przykład użycia:
    //$telegramBotToken = 'TWÓJ_TOKEN_BOTA_TELEGRAM';
    //$chatId = 'ID_CHATU';
    //$discordWebhookUrl = 'TWÓJ_URL_WEBHOOKA_DISCORD';

    //$notificationManager = new NotificationManager($telegramBotToken, $chatId, $discordWebhookUrl);

    // Wysyłanie wiadomości na Telegram
    //$notificationManager->sendTelegramMessage("To jest testowa wiadomość na Telegram!");

    // Wysyłanie wiadomości na Discord
    //$notificationManager->sendDiscordWebhook("To jest testowa wiadomość na Discord!");
?>
