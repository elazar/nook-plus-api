<?php

namespace NookPlus;

use Predis\Client;
use Ramsey\Uuid\Uuid;

class Data
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $keys = [
        'clothing-need',
        'crafting-need',
        'critters-caught',
        'critters-donated',
        'critters-made',
        'fossils-donated',
        'furniture-need',
        'plants-quantity',
        'recipes-need',
        'settings',
        'songs-owned',
        'villagers-favorites',
        'villagers-residents',
        'villagers-photos',
    ];

    const USERS = 'users';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private function getKey(string $uuid, string $key): string
    {
        return "$uuid-$key";
    }

    public function createUser(): string
    {
        do {
            $uuid = Uuid::uuid4()->toString();
        } while ($this->client->sismember(self::USERS, $uuid));

        $this->client->sadd(self::USERS, $uuid);

        return $uuid;
    }

    public function userExists(string $uuid): bool
    {
        return $this->client->sismember(self::USERS, $uuid);
    }

    public function keyExists(string $key): bool
    {
        return in_array($key, $this->keys);
    }

    public function getValues(string $uuid, string $key)
    {
        return $this->client->smembers($this->getKey($uuid, $key));
    }

    public function addValues(string $uuid, string $key, $values): void
    {
        if ($values) {
            $this->client->sadd($this->getKey($uuid, $key), (array) $values);
        }
    }

    public function removeValue(string $uuid, string $key, string $value): void
    {
        $this->client->srem($this->getKey($uuid, $key), $value);
    }

    public function getUser(string $uuid): array
    {
        $keys = array_map(fn($key) => $this->getKey($uuid, $key), $this->keys);
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->client->smembers($key);
        }
        return $values;
    }
}
