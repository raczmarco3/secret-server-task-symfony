<?php

namespace App\Service;

use App\Entity\SecretEntity;

class EntityToResponse
{
    private $secretEntity;

    public function __construct(SecretEntity $secretEntity)
    {
        $this->secretEntity = $secretEntity;
    }

    public function jsonEncode()
    {
        return json_encode([
            'hash' => $this->secretEntity->getHash(),
            'secretText' => $this->secretEntity->getSecretText(),
            'createdAt' => $this->secretEntity->getCreatedAt()->format('Y-m-d H:i:s'),
            'expiresAt' => $this->secretEntity->getExpiresAt()->format('Y-m-d H:i:s'),
            'remainingViews' => $this->secretEntity->getRemainingViews()
        ]);
    }

    public function xmlEncode()
    {
        $secretXml = new \SimpleXMLElement("<Secret></Secret>");
        $secretXml->addChild('hash', $this->secretEntity->getHash());
        $secretXml->addChild('secretText', $this->secretEntity->getSecretText());
        $secretXml->addChild('createdAt', $this->secretEntity->getCreatedAt()->format('Y-m-d H:i:s'));
        $secretXml->addChild('expiresAt', $this->secretEntity->getExpiresAt()->format('Y-m-d H:i:s'));
        $secretXml->addChild('remainingViews', $this->secretEntity->getRemainingViews());                     
            
        return $secretXml->asXML();
    }
}