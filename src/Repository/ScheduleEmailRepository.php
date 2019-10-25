<?php

namespace Solcre\SolcreFramework2\Repository;

use DateTime;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Exception;
use Solcre\SolcreFramework2\Common\BaseRepository;

class ScheduleEmailRepository extends BaseRepository
{
    public function fetchAvailableScheduledEmails($retried)
    {
        try {
            $connection = $this->_em->getConnection();
            $connection->beginTransaction();
            $query = 'SELECT * FROM schedule_emails as se  WITH (TABLOCKX) WHERE se.send_at IS NULL AND se.retried < :retried AND se.sending_date IS NULL;';
            $stmt = $connection->executeQuery(
                $query,
                [
                    'retried' => $retried
                ]
            );
            $rsm = new ResultSetMappingBuilder($this->_em);
            $rsm->addRootEntityFromClassMetadata($this->_entityName, 'se');

            return $this
                ->_em
                ->newHydrator(Query::HYDRATE_OBJECT)
                ->hydrateAll($stmt, $rsm);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function markEmailAsSending($emailsIds)
    {
        try {
            $date = new DateTime();
            $connection = $this->_em->getConnection();
            $query = "UPDATE schedule_emails  SET schedule_emails.sending_date = :date WHERE schedule_emails.id IN(" . implode(',', $emailsIds) . ")";
            $rowAffected = $connection->executeUpdate(
                $query,
                [
                    'date' => $date->format('Y-m-d H:i:s')
                ]
            );
            if ($rowAffected === \count($emailsIds)) {
                return true;
            }
            $rollBackQuery = "UPDATE schedule_emails SET schedule_emails.sending_date = NULL WHERE schedule_emails.id IN(" . implode(',', $emailsIds) . ")";
            $connection->executeUpdate($rollBackQuery);
            return false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function processDelayedEmails($delayedTime, $delayedTimeMinutes)
    {
        $connection = $this->_em->getConnection();
        $query = 'UPDATE schedule_emails se SET se.sending_date = null WHERE se.send_At IS NULL AND TIMESTAMPDIFF(MINUTE, sending_date, :date) > :minutes';
        $connection->executeUpdate(
            $query,
            [
                'date'    => $delayedTime,
                'minutes' => $delayedTimeMinutes
            ]
        );
    }
}
