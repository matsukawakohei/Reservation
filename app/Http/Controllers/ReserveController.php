<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ReserveService;

class ReserveController extends Controller
{
    protected $reserveService;

    public function __construct(ReserveService $reserveService)
    {
        $this->reserveService = $reserveService;
    }

    public function index($start = null)
    {
        if (is_null($start)) {
            $start = date('Y-m-d');
        }
        $reserves = $this->reserveService->getWeekReserve($start);

        return view('index', compact(['reserves', 'start']));
    }

    public function create(Request $request)
    {
        $userId = Auth::id();
        $start = date('Y-m-d', strtotime($request['start_time']));
        try {
            $this->reserveService->createReserve($request, $userId);
            session()->flash('message', '予約が完了しました');
        } catch (Exception $e) {
            session()->flash('message', 'もう一度やり直してください');
        }
        return redirect()->route('index', $start);
    }

    public function user()
    {
        $userId = Auth::id();
        $userReserves = $this->reserveService->getUserReserve($userId);

        return view('user_page', compact(['userReserves']));
    }

    public function delete(Request $request)
    {
        try {
            $this->reserveService->deleteReserve($request['reserve_id']);
            session()->flash('message', '予約を削除しました');
        } catch (Exception $e) {
            session()->flash('message', 'もう一度やり直してください');
        }
        
        return redirect()->route('user_page');
    }

    public function edit($id, $start = null)
    {
        $reserve = $this->reserveService->getReserveById($id);

        if (is_null($start)) {
            $start = date('Y-m-d');
        }
        $reserves = $this->reserveService->getWeekReserve($start);

        return view('edit', compact(['reserve', 'reserves', 'start']));

    }

    public function update(Request $request)
    {
        try {
            $this->reserveService->updateReserve($request);
            session()->flash('message', '予約を変更しました');
        } catch (Exception $e) {
            session()->flash('message', 'もう一度やり直してください');
        }

        return redirect()->route('user_page');
    }

    public function search(Request $request)
    {
        $userId = Auth::id();
        $requestdate = $request['year'] .'-' .$request['month'] .'-' .$request['day'];
        $userReserves = $this->reserveService->searchReserve($userId, $requestdate);
        session()->flash('message', $requestdate .'の検索結果');

        return view('user_page', compact(['userReserves']));
    }
}
