<?php

namespace App\Repositories;

use App\Models\Reserve;

class ReserveRepository
{
  protected $reserve;

  public function __construct(Reserve $reserve)
  {
    $this->reserve = $reserve;
  }

  /**
   * ユーザーの予約情報を取得する
   * 
   * @param int $userId ユーザーID
   * @return array ユーザーの予約情報
   */
  public function getUserReserve($userId)
  {
    return $this->reserve
      ->where('user_id', $userId)
      ->orderBy('start_time')
      ->get();
  }

  /**
   * 希望予約時間が空いているか確認する
   * 
   * @param array $time 時間の配列 0：日付, 1：時間
   * @param int $courtId コート番号
   * @return boolean true：重複あり false：重複なし
   */
  public function checkDupulicateReserve($time, $courtId)
  {
    return $this->reserve
      ->where('court_id', $courtId)
      ->whereDate('start_time', $time[0])
      ->whereTime('start_time', $time[1])
      ->exists();
  }

  /**
   * 予約を削除する
   * 
   * @param int $reserveId 予約ID
   */
  public function deleteReserve($reserveId)
  {
    $this->reserve
      ->where('id', $reserveId)
      ->delete();
  }

  /**
   * 対象の予約番号が存在するか確認する
   * 
   * @param int $reserveId 予約番号
   * @return boolean true：存在する, false：存在しない
   */
  public function exsitsReserve($reserveId)
  {
    return $this->reserve
      ->where('id', $reserveId)
      ->exists();
  }

  /**
   * 予約情報を取得する
   * 
   * @param int $reserveId 予約番号
   * @return Reserve 対象の予約情報
   */
  public function getReserveById($reserveId)
  {
    return $this->reserve->find($reserveId);
  }

  /**
   * 予約を変更する
   * 
   * @param int $reserveId 予約番号
   * @param array $request 開始時間と終了時間の配列
   * 
   */
  public function updateReserve($reserveId, $request)
  {
    $this->reserve
      ->where('id', $reserveId)
      ->first()
      ->fill($request)
      ->save();
  }

  /**
   * 予約を新規作成する
   * 
   * @param array $request リクエスト配列
   */
  public function createReserve($request)
  {
    $this->reserve->create($request);
  }

  /**
   * 日付で予約を検索する
   * 
   * @param string $date 検索対象の日付
   * @return Collection 予約情報
   */
  public function getReserveByDate($date)
  {
    return $this->reserve
      ->whereDate('start_time', $date)
      ->pluck('start_time');
  }

  /**
   * 日付とユーザーIDで予約を検索する
   * 
   * @param int ユーザーID
   * @param string $date 検索対象の日付
   * @return Collection 予約情報
   */
  public function searchReserve($userId, $date)
  {
    return $this->reserve
      ->where('user_id', $userId)
      ->whereDate('start_time', $date)
      ->get();
  }
}