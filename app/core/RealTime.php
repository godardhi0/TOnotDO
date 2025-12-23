<?php

class Realtime
{
    public static function emit($event, $data = [])
    {
        $payload = json_encode([
            'event' => $event,
            'data' => $data
        ]);

        $ch = curl_init("http://localhost:3000");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}
