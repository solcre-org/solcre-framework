<?php

namespace Solcre\SolcreFramework2\Service;

use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;
use Solcre\SolcreFramework2\Entity\EmailAddress;
use Solcre\SolcreFramework2\Entity\ScheduleEmail;

class SendScheduleEmailService
{
    private $scheduleEmailService;
    private $emailService;
    private $entityManager;
    private $logger;

    /**
     * SendScheduledEmailsController constructor.
     *
     * @param $entityManager
     * @param $scheduleEmailService
     * @param $emailService
     * @param $logger
     */
    public function __construct(EntityManager $entityManager, ScheduleEmailService $scheduleEmailService, EmailService $emailService, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->scheduleEmailService = $scheduleEmailService;
        $this->emailService = $emailService;
        $this->logger = $logger;
    }

    public function sendScheduledEmails(): ?bool
    {
        try {
            $scheduledEmailsToSend = $this->scheduleEmailService->fetchAvailableScheduledEmails();
            $result = false;
            if (! empty($scheduledEmailsToSend) && is_array($scheduledEmailsToSend)) {
                $result = $this->markEmailAsSending($scheduledEmailsToSend);

                if ($result) {
                    $this->entityManager->beginTransaction();
                    $result = $this->processEmails($scheduledEmailsToSend);
                    $this->entityManager->flush();
                    $this->entityManager->commit();
                }
            }
            return $result;
        } catch (\Exception $e) {
            if ($this->entityManager->isOpen()) {
                $this->entityManager->flush();
                $this->entityManager->commit();
            }
            $this->logToFile($e->getMessage());
            throw $e;
        }
    }

    private function markEmailAsSending(array &$emailsToSend): ?bool
    {
        $emailsToSendIds = \array_map(
            function ($emailToSend) {
                return $emailToSend->getId();
            },
            $emailsToSend
        );

        if (empty($emailsToSendIds)) {
            return false;
        }
        try {
            $result = $this->scheduleEmailService->markEmailAsSending($emailsToSendIds);
            $this->entityManager->getConnection()->commit();
            if (! $result) {
                return false;
            }
            foreach ($emailsToSend as $email) {
                $this->entityManager->refresh($email);
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function processEmails(array $emailsToSend)
    {
        $resultSend = false;
        /* @var $scheduleEmail ScheduleEmail */
        foreach ($emailsToSend as $scheduleEmail) {
            try {
                $addressesToEmail = $this->createAddresses($scheduleEmail->getAddresses());

                if (empty($addressesToEmail)) {
                    continue;
                }

                $from = $this->createEmailFrom($scheduleEmail->getEmailFrom());
                $resultSend = $this->sendEmail($from, $addressesToEmail, $scheduleEmail);

                if ($resultSend) {
                    $this->scheduleEmailService->patchScheduleEmail(
                        $scheduleEmail,
                        [
                            'sendAt' => true
                        ]
                    );
                } else {
                    $message = 'Email Schedule ID: ' . $scheduleEmail->getId();
                    $this->logToFile($message);
                    $this->scheduleEmailService->patchScheduleEmail(
                        $scheduleEmail,
                        [
                            'retried'   => $scheduleEmail->getRetried() + 1,
                            'isSending' => false,
                        ]
                    );
                }
            } catch (Exception $e) {
                $this->scheduleEmailService->patchScheduleEmail(
                    $scheduleEmail,
                    [
                        'retried'   => $scheduleEmail->getRetried() + 1,
                        'isSending' => false,
                    ]
                );
                unset($e);
            }
        }
        return $resultSend;
    }

    private function createAddresses(array $addresses)
    {
        $addressesToEmail = [];
        if (! empty($addresses) && \is_array($addresses)) {
            foreach ($addresses as $emailsAddress) {
                $addressesToEmail[] = new  EmailAddress($emailsAddress['email'], $emailsAddress['name'], $emailsAddress['type']);
            }
        }

        return $addressesToEmail;
    }

    private function createEmailFrom(array $fromEmail)
    {
        return new EmailAddress($fromEmail['email'], $fromEmail['name'] ?? null, $fromEmail['type']);
    }

    private function sendEmail(EmailAddress $from, array $addressesToEmail, ScheduleEmail $scheduleEmail)
    {
        try {
            return $this->emailService->send(
                $from,
                $addressesToEmail,
                $scheduleEmail->getSubject(),
                $scheduleEmail->getContent(),
                $scheduleEmail->getCharset(),
                $scheduleEmail->getAltText()
            );
        } catch (Exception $e) {
            return false;
        }
    }

    private function logToFile($msg): void
    {
        $this->logger->error($msg, ['SEND-SCHEDULE-EMAIL-SERVICE']);
    }
}
