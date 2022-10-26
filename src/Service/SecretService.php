<?php
namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Secret;
use App\Entity\SecretEntity;
use App\Service\EntityToResponse;

class SecretService 
{
    public function saveSecret(ManagerRegistry $doctrine, Secret $secret)
    {
        $secretEntity = new SecretEntity();
        // Get current date
        $createdAt_ = date('Y-m-d H:i:s', time());
        // Convert String to DateTime
        $createdAt = new \DateTimeImmutable('@'.strtotime($createdAt_));
        // Calculate expiresDate      
        if($secret->getexpireAfter() == 0) {
            $expiresAt = $createdAt;
        } else {
            $expiresAt = new \DateTimeImmutable('@'.strtotime('+'.$secret->getexpireAfter().'minutes'));
        }        
        // Generate hash      
        $hash = hash("sha256", $createdAt_.$secret->getSecret());

        $secretEntity->setHash($hash);
        $secretEntity->setSecretText($secret->getSecret());
        $secretEntity->setCreatedAt($createdAt);
        $secretEntity->setExpiresAt($expiresAt);
        $secretEntity->setRemainingViews($secret->getExpireAfterView()+1);
        
        $entityManager = $doctrine->getManager();
        $entityManager->persist($secretEntity);

        try {
            $entityManager->flush();
            return ['error' => false, 'hash' => $secretEntity->getHash()];
        } catch (\Exception $e) {
            return ['error' => true, 'msg' => $e->getMessage()];
        }
    }

    public function getSecret(ManagerRegistry $doctrine, $hash, $contentType)
    {
        $entityManager = $doctrine->getManager();
        $secretEntity = $doctrine->getRepository(SecretEntity::class)->findOneBy(
            ['hash' => $hash]
        );

        if(!$secretEntity) {
            return 'Secret Not Found.';
        }
        if($secretEntity->getRemainingViews()!=0) {
            $secretEntity->setRemainingViews($secretEntity->getRemainingViews()-1);
        }        
        $entityToResponse = new EntityToResponse($secretEntity);

        if($contentType == 'application/json' || $contentType == 'application/xml') {  
            $currentDate = new \DateTimeImmutable('@'.strtotime('now'));
            if($secretEntity->getRemainingViews()==0) {
                return 'Secret has no more views!';
            } else if($secretEntity->getExpiresAt() > $currentDate->format('Y-m-d H:i:s') 
            && $secretEntity->getExpiresAt()!=$secretEntity->getCreatedAt()) {
                return "Secret has expired!";
            }
            $entityManager->flush(); 
            if($contentType == 'application/json') {
                return $entityToResponse->jsonEncode();
            }                        
            return $entityToResponse->xmlEncode();
        } else {
            return "Not allowed Content-Type. Allowed Content-Types are: JSON or XML.";
        }

        return "Something went wrong!";        
    }
}