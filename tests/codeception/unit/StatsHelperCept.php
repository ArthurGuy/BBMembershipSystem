<?php 
$I = new UnitTester($scenario);
$I->wantTo('confirm the stats helper works as expected');

$I->assertEquals(0, \BB\Helpers\StatsHelper::roundToNearest(2), '2 should round to 0');

$I->assertEquals(5, \BB\Helpers\StatsHelper::roundToNearest(4), '4 should round to 5');

$I->assertEquals(5, \BB\Helpers\StatsHelper::roundToNearest(5), '5 should stay as 5');

$I->assertEquals(5, \BB\Helpers\StatsHelper::roundToNearest(6), '6 should round down to 5');

$I->assertEquals(10, \BB\Helpers\StatsHelper::roundToNearest(7.5), '7.5 should round up to 10');

$I->assertEquals(10, \BB\Helpers\StatsHelper::roundToNearest(10), '10 should stay as 10');