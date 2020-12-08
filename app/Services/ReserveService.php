<?php

namespace App\Services;

use DB;
use Log;
use Exception;
use App\Repositories\ReserveRepository;

class ReserveService
{
  protected $reserveRepository;

  public function __construct(ReserveRepository $reserveRepository)
  {
    $this->reserveRepository = $reserveRepository;
  }

  /**
   * ユーザーの予約情報を取得する
   * 
   * @param int $userId ユーザーID
   * @return array ユーザーの予約情報
   */
  public function getUserReserve($userId)
  {
    return $this->reserveRepository->getUserReserve($userId);
  }

  /**
   * 希望予約時間が空いているか確認する
   * 
   * @param Request $request 希望予約のリクエスト
   * @param boolean true：重複あり false：重複なし
   */
  public function isDuplicateReserve($request)
  {
    $time = explode(' ', $request['start_time']);
    $courtId = $request['court_id'];

    return $this->reserveRepository->checkDupulicateReserve($time, $courtId);
    
  }

  /**
   * 対象の予約番号が存在するか確認する
   * 
   * @param int $reserveId 予約番号
   * @return boolean true：存在する, false：存在しない
   */
  public function existsReserve($reserveId)
  {
    return $this->reserveRepository->exsitsReserve($reserveId);
  }

  /**
   * 予約を削除する
   * 
   * @param int $reserveId 予約ID
   * @throws Exception
   */
  public function deleteReserve($reserveId)
  {
    if ($this->existsReserve($reserveId)) {
      try {
        DB::beginTransaction();
        $this->reserveRepository->deleteReserve($reserveId);
        DB::commit();
      } catch (Exception $e) {
        DB::rollback();
        $message = '削除に失敗しました';
        Log::ERROR($message ."\n" .$e->getMessage());
        throw new Exception($message);
      }
      
      return;
    }

    $this->throwNotFoundException();
  }

  /**
   * 予約情報を取得する
   * 
   * @param int $reserveId 予約番号
   * @return Reserve 対象の予約情報
   */
  public function getReserveById($reserveId)
  {
    $reserve = $this->reserveRepository->getReserveById($reserveId);

    if (is_null($reserve)) {
      $this->throwNotFoundException();
    }

    return $reserve;
  }

  /**
   * 予約を変更する
   * 
   * @param Request $request 予約変更のリクエスト
   */
  public function updateReserve($request)
  {
    $reserveId = $request['id'];
    if ($this->isDuplicateReserve($request)) {
      $this->throwAlreadyReservedException();
    }

    if ($this->existsReserve($reserveId)) {
      try {
        DB::beginTransaction();
        $this->reserveRepository->updateReserve($reserveId, $request->all());
        DB::commit();
        return;
      } catch (Exception $e) {
        DB::rollback();
        $message = '変更に失敗しました';
        Log::ERROR($message ."\n" .$e->getMessage());
        throw new Exception($message);
      }
      
    }

    $this->throwNotFoundException();
  }

  /**
   * 予約を新規作成する
   * 
   * @param Request $request リクエスト
   * @param int $userId ユーザーID
   * @throws Exception
   */
  public function createReserve($request, $userId)
  {
    if ($this->isDuplicateReserve($request)) {
      $this->throwAlreadyReservedException();
    }

    $request = $request->all();
    $request['user_id'] = $userId;
    

    try {
      DB::beginTransaction();
      $this->reserveRepository->createReserve($request);
      DB::commit();
    } catch (Exception $e) {
      DB::rollback();
      $message = '作成に失敗しました';
      Log::ERROR($message ."\n" .$e->getMessage());
      throw new Exception($message);
    }

  }

  /**
   * 1週間の予約を取得する
   * 
   * @param string $start 検索対象の日付
   * @return Collection 予約情報
   */
  public function getWeekReserve($start = null)
  {
    $weekDay = 7;

    if (is_null($start)) {
      $start = date('Y-m-d');
    }

    $reserves = [];
    for ($i = 0; $i < $weekDay; $i++) {
      $date = date('Y-m-d', strtotime($start ."+${i} day"));
      $reserve = $this->reserveRepository->getReserveByDate($date);
      $reserves[$date] = $this->makeReserveTimeParams($reserve);
    }

    return $reserves;
  }

  /**
   * 予約時間をキーにした連想配列に整形する
   * 
   * @param Collection $reserve 予約時間のコレクション
   * @return array 予約時間をキーにした連想配列
   */
  public function makeReserveTimeParams($reserve)
  {
    $result = [];
    foreach ($reserve as $dateTime) {
      $timeKey = date('H:i', strtotime($dateTime));
      $result[$timeKey] = 1;
    }

    return $result;
  }

  /**
   * 日付とユーザーIDで予約を検索する
   * 
   * @param int ユーザーID
   * @param string $requestdate 検索する日付
   * @return Collection 予約情報
   */
  public function searchReserve($userId, $requestdate)
  {
    $format = 'Y-m-d';
    $date = date($format, strtotime($requestdate));
    
    $reserves = $this->reserveRepository->searchReserve($userId, $date);

    return $reserves;
  }

  /**
   * 対象の予約がない場合に例外をスローする
   * 
   * @throws Exception
   */
  public function throwNotFoundException()
  {
    throw new Exception('対象の予約番号がありません');
  }

  /**
   * すでに予約が入っている場合の例外をスローする
   * 
   * @throws Exception
   */
  public function throwAlreadyReservedException()
  {
    throw new Exception('ご指定の時間はすでに予約が入っています');
  }
}