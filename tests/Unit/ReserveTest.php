<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ReserveService;
use App\Repositories\ReserveRepository;
use App\Models\Reserve;

class ReserveTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider dataProvicerForgetUserReserveTest
     */
    public function getUserReserveTest($userId, $expected)
    {
        $service = new ReserveService(new ReserveRepository(new Reserve()));
        $reserves = $service->getUserReserve($userId);
        $this->assertEquals($expected, count($reserves));
    }

    public function dataProvicerForgetUserReserveTest()
    {
        return [
            'ID1' => [
                1,
                6
            ],
            'ID2' => [
                2,
                7
            ],
            'ID3' => [
                3,
                7
            ]
        ];
    }

    /**
     * @test
     * @dataProvider dataProvicerForIsDuplicateReserve
     */
    public function isDuplicateReserveTest($expected, $params)
    {
        $service = new ReserveService(new ReserveRepository(new Reserve()));
        $result = $service->isDuplicateReserve($params);
        $this->assertEquals($expected, $result);
    }

    public function dataProvicerForIsDuplicateReserve()
    {
        return [
            '重複なし1' => [
                false,
                [
                    'start_time' => '2021-01-01 09:00:00',
                    'court_id' => 1,
                ]
            ],
            '重複なし2' => [
                false,
                [
                    'start_time' => '2020-12-06 22:00:00',
                    'court_id' => 1,
                ]
            ],
            '重複あり' => [
                false,
                [
                    'start_time' => '2020-12-06 09:00:00',
                    'court_id' => 1,
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForgetReserveById
     */
    public function getReserveByIdTest($expected, $reserveId)
    {
        if (!$expected) {
            $this->expectExceptionMessage('対象の予約番号がありません');
        }

        $service = new ReserveService(new ReserveRepository(new Reserve()));
        $result = $service->getReserveById($reserveId);

        if ($expected) {
            $this->assertEquals($reserveId, $result->id);
        }
    }

    public function dataProviderForgetReserveById()
    {
        return [
            '存在する' => [
                true,
                22
            ],
            '存在しない' => [
                false,
                99999999
            ]
            ];
    }
}
