<?php namespace BB\Services;

use BB\Repo\EquipmentLogRepository;

class CombineEquipmentLogs
{


    /**
     * @var EquipmentLogRepository
     */
    private $equipmentLogRepository;

    /**
     * @var mixed
     */
    private $logEntries;

    public function __construct(EquipmentLogRepository $equipmentLogRepository)
    {
        $this->equipmentLogRepository = $equipmentLogRepository;
    }

    public function run()
    {
        $this->logEntries = $this->equipmentLogRepository->getUnbilledRecords();

        $updating = true;

        while ($updating) {
            $updating = $this->updateLogEntries();
        }
    }

    /**
     * @return bool
     */
    private function updateLogEntries()
    {
        foreach ($this->logEntries as $entry) {

            //look all the subsequent records for related entries

            //See if there is a record we can join with
            $nextRecord = $this->fetchNextRecord(
                $entry['user_id'],
                $entry['device'],
                $entry['reason'],
                $entry['finished']
            );

            if ($nextRecord) {

                $this->equipmentLogRepository->update($entry['id'], ['finished' => $nextRecord['finished']]);
                $this->equipmentLogRepository->delete($nextRecord['id']);

                //The array is now dirty - re-fetch it and return to restart the check
                $this->logEntries = $this->equipmentLogRepository->getUnbilledRecords();
                return true;
            }

        }
        return false;
    }

    /**
     * @param int       $userId
     * @param string    $device
     * @param string    $reason
     * @param \DateTime $finishedDate
     * @return bool
     */
    private function fetchNextRecord($userId, $device, $reason, \DateTime $finishedDate)
    {
        foreach ($this->logEntries as $entry) {
            if (($entry['user_id'] == $userId) && ($entry['device'] == $device) && ($entry['reason'] == $reason)) {
                if (($entry['started']->gt($finishedDate)) && ($entry['started']->diffInSeconds($finishedDate) <= 60)) {
                    return $entry;
                }
            }
        }
        return false;
    }
}