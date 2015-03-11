<?php
use \UnitTester;
use \Mockery as m;
use \Carbon\Carbon as Carbon;

class CombineEquipmentLogTestCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
        m::close();
    }

    // one record to be updated
    public function confirmLogGetsCorrectlyUpdated(UnitTester $I)
    {
        $equipmentLogRepo = m::mock('BB\Repo\EquipmentLogRepository');
        $equipmentLogRepo->shouldReceive('getUnbilledRecords')->once()->andReturn($this->sampleData());
        $equipmentLogRepo->shouldReceive('getUnbilledRecords')->once()->andReturn($this->correctSampleData());

        $equipmentLogRepo->shouldReceive('update')->once()->withArgs([2, ['finished'=>'2014-12-13 16:57:10']]);

        $equipmentLogRepo->shouldReceive('delete')->once()->withArgs([4]);

        $combineEquipmentLogs = new \BB\Services\CombineEquipmentLogs($equipmentLogRepo);
        $combineEquipmentLogs->run();
    }

    //Two records need to be updated - confirm reason is respected
    public function confirmComplexLogGetsCorrectlyUpdated(UnitTester $I)
    {
        $equipmentLogRepo = m::mock('BB\Repo\EquipmentLogRepository');
        $equipmentLogRepo->shouldReceive('getUnbilledRecords')->once()->andReturn($this->complexSampleData());
        $equipmentLogRepo->shouldReceive('getUnbilledRecords')->once()->andReturn($this->complexMidCorrectSampleData());
        $equipmentLogRepo->shouldReceive('getUnbilledRecords')->once()->andReturn($this->complexCorrectSampleData());

        $equipmentLogRepo->shouldReceive('update')->once()->withArgs([2, ['finished'=>'2014-12-13 16:57:10']]);
        $equipmentLogRepo->shouldReceive('delete')->once()->withArgs([4]);

        $equipmentLogRepo->shouldReceive('update')->once()->withArgs([6, ['finished'=>'2014-12-13 17:59:00']]);
        $equipmentLogRepo->shouldReceive('delete')->once()->withArgs([7]);

        $combineEquipmentLogs = new \BB\Services\CombineEquipmentLogs($equipmentLogRepo);
        $combineEquipmentLogs->run();
    }

    private function sampleData() {
        return [
            ['id'=>1, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 15:45:10'), 'finished'=> Carbon::parse('2014-12-13 15:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>2, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>3, 'user_id'=>1, 'device'=>'welder', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>4, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:48:00'), 'finished'=> Carbon::parse('2014-12-13 16:57:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
        ];
    }
    private function correctSampleData() {
        return [
            ['id'=>1, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 15:45:10'), 'finished'=> Carbon::parse('2014-12-13 15:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>2, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:57:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>3, 'user_id'=>1, 'device'=>'welder', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
        ];
    }

    private function complexSampleData() {
        return [
            ['id'=>1, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 15:45:10'), 'finished'=> Carbon::parse('2014-12-13 15:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>2, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>3, 'user_id'=>1, 'device'=>'welder', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>4, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:48:00'), 'finished'=> Carbon::parse('2014-12-13 16:57:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>5, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:58:00'), 'finished'=> Carbon::parse('2014-12-13 17:30:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>'testing'],
            ['id'=>6, 'user_id'=>2, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 17:40:00'), 'finished'=> Carbon::parse('2014-12-13 17:55:00'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>7, 'user_id'=>2, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 17:56:00'), 'finished'=> Carbon::parse('2014-12-13 17:59:00'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
        ];
    }
    private function complexMidCorrectSampleData() {
        return [
            ['id'=>1, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 15:45:10'), 'finished'=> Carbon::parse('2014-12-13 15:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>2, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>3, 'user_id'=>1, 'device'=>'welder', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>5, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:58:00'), 'finished'=> Carbon::parse('2014-12-13 17:30:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>'testing'],
            ['id'=>6, 'user_id'=>2, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 17:40:00'), 'finished'=> Carbon::parse('2014-12-13 17:55:00'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>7, 'user_id'=>2, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 17:56:00'), 'finished'=> Carbon::parse('2014-12-13 17:59:00'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
        ];
    }
    private function complexCorrectSampleData() {
        return [
            ['id'=>1, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 15:45:10'), 'finished'=> Carbon::parse('2014-12-13 15:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>2, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>3, 'user_id'=>1, 'device'=>'welder', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:45:10'), 'finished'=> Carbon::parse('2014-12-13 16:47:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
            ['id'=>5, 'user_id'=>1, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 16:58:00'), 'finished'=> Carbon::parse('2014-12-13 17:30:10'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>'testing'],
            ['id'=>6, 'user_id'=>2, 'device'=>'laser', 'active'=>0, 'started'=> Carbon::parse('2014-12-13 17:40:00'), 'finished'=> Carbon::parse('2014-12-13 17:59:00'), 'removed'=>0, 'billed'=>0, 'processed'=>0, 'reason'=>''],
        ];
    }
}