<?php

namespace Solcre\SolcreFramework2\Service;

use DateTime;
use Exception;
use Solcre\SolcreFramework2\Entity\ScheduleEmail;

class ScheduleEmailService extends BaseService
{
    private const MAX_RETRIED = 3;
    private const DELAYED_EMAIL_HOUR = 1;

    public function add($data, $strategies = null)
    {
        $this->validateData($data);
        $data['charset'] = $data['charset'] ?? 'UTF-8';
        $data['emailFrom'] = $data['from'];
        $data['retried'] = 0;
        $data['sendingDate'] = null;
        $data['createdAt'] = new DateTime();
        return parent::add($data);
    }

    private function validateData(array $data)
    {
        $required = ['content', 'subject', 'altText', 'addresses', 'from'];
        if (! $this->arrayKeysExists($required, $data)) {
            throw new \InvalidArgumentException('Invalid data provided', 422);
        }
        return true;
    }

    private function arrayKeysExists(array $keys, array $arr)
    {
        return ! array_diff_key(array_flip($keys), $arr);
    }

    public function patchScheduleEmail(ScheduleEmail $scheduleEmailEntity, array $data)
    {
        if (array_key_exists('sendAt', $data)) {
            $scheduleEmailEntity->setSendAt(new DateTime());
        }

        if (array_key_exists('isSending', $data)) {
            $date = null;
            if ($data['isSending']) {
                $date = new DateTime();
            }
            $scheduleEmailEntity->setSendingDate($date);
        }

        if (array_key_exists('retried', $data)) {
            $scheduleEmailEntity->setRetried($data['retried']);
        }

        $this->entityManager->flush($scheduleEmailEntity);
        return $scheduleEmailEntity;
    }

    public function markEmailAsSending($emailsToSend)
    {
        try {
            return $this->repository->markEmailAsSending($emailsToSend);
        } catch (Exception $e) {
            throw  $e;
        }
    }

    public function fetchAvailableScheduledEmails()
    {
        try {
            return $this->repository->fetchAvailableScheduledEmails(self::MAX_RETRIED);
        } catch (Exception $e) {
            throw  $e;
        }
    }

    public function processDelayedEmails()
    {
        try {
            $delayedTime = new DateTime();
            $hourPast = \sprintf('- %s hour', self::DELAYED_EMAIL_HOUR);
            $delayedTime->modify($hourPast);
            $delayedMinutes = self::DELAYED_EMAIL_HOUR * 60;
            return $this->repository->processDelayedEmails($delayedTime->format('Y-m-d H:i:s'), $delayedMinutes);
        } catch (Exception $e) {
            throw  $e;
        }
    }
}
