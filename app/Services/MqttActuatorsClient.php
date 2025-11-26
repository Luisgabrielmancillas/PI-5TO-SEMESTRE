<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttActuatorsClient
{
    protected static function publish(string $topic, string $payload): void
    {
        $server   = config('hydrobox_mqtt.host');
        $port     = (int) config('hydrobox_mqtt.port', 1883);
        $clientId = config('hydrobox_mqtt.client_id_prefix', 'hydrobox-web') . '-' . uniqid();
        $user     = config('hydrobox_mqtt.user') ?: null;
        $pass     = config('hydrobox_mqtt.pass') ?: null;

        $settings = (new ConnectionSettings)
            ->setUsername($user)
            ->setPassword($pass)
            ->setKeepAliveInterval(30);
            // OJO: algunas versiones no tienen setCleanSession, por eso no lo usamos

        $mqtt = new MqttClient($server, $port, $clientId);

        try {
            // true = conexión con sesión limpia
            $mqtt->connect($settings, true);

            $mqtt->publish($topic, $payload, MqttClient::QOS_AT_LEAST_ONCE);

            $mqtt->disconnect();
        } catch (\Throwable $e) {
            Log::warning('Error enviando comando MQTT de actuadores', [
                'topic'   => $topic,
                'payload' => $payload,
                'error'   => $e->getMessage(),
            ]);
            // No relanzamos la excepción: la vista seguirá funcionando
        }
    }

    public static function sendSwitch(string $deviceId, bool $on): void
    {
        $base    = config('hydrobox_mqtt.base_topic', 'hydrobox');
        $topic   = "{$base}/actuators/{$deviceId}/set";
        $payload = json_encode(['on' => $on]);

        self::publish($topic, $payload);
    }

    public static function sendDose(string $deviceId, int $ml): void
    {
        $base    = config('hydrobox_mqtt.base_topic', 'hydrobox');
        $topic   = "{$base}/actuators/{$deviceId}/set";
        $payload = json_encode(['dose_ml' => $ml]);

        self::publish($topic, $payload);
    }
}