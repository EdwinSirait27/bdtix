<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Tickets;
use Carbon\Carbon;

class ResolveTicketController extends Controller
{
    public function allReview()
    {

        $todaysticket = Tickets::where('executor_id', auth()->id())
            ->whereDate('created_at', Carbon::today())
            ->count();

        $highprior = Tickets::where('executor_id', auth()->id())
            ->where('priority', 'High')
            ->count();

        $onprogressticket = Tickets::where('executor_id', auth()->id())
            ->where('status', 'Progress')
            ->count();

        return view('pages.resolvetickets', compact('todaysticket', 'onprogressticket', 'highprior'));
    }

    public function getReviewtickets(Request $request)
    {
        $query = Tickets::with(['user.employee', 'executor.employee', 'review'])
            ->where('executor_id', auth()->id())
            ->whereIn('status', ['Closed', 'Overdue'])
            ->select([
                'id',
                'queue_number',
                'title',
                'user_id',
                'executor_id',
                'priority',
                'finished',
                'estimation',
                'description',
                'category',
                'created_at',
                'status',
            ]);
        return DataTables::eloquent($query)
             ->addColumn('employee_name', function ($ticket) {
        return optional($ticket->user?->employee)->employee_name ?? '-';
      })
      ->orderColumn('employee_name', function ($query, $order) {})
            ->addColumn('action', function ($ticket) {
                $idHashed = substr(hash('sha256', $ticket->id . env('APP_KEY')), 0, 8);
                $showBtn = '
        <a href="' . route('showtickets', $idHashed) . '"
           class="inline-flex items-center justify-center p-2
                  text-slate-500 hover:text-emerald-600
                  hover:bg-emerald-50 rounded-full transition"
           title="Show Ticket: ' . e($ticket->title) . '">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 12s3.75-6.75 9.75-6.75
                         S21.75 12 21.75 12
                         18 18.75 12 18.75
                         2.25 12 2.25 12z" />
                <circle cx="12" cy="12" r="3.25" />
            </svg>
        </a>';

                // ================= REVIEW BUTTON =================
                $reviewBtn = '';

                if (in_array($ticket->status, ['Closed', 'Overdue'])) {
                    $reviewBtn = '
    <a href="' . route("reviewtickets", $idHashed) . '"
       class="inline-flex items-center justify-center p-2
              text-slate-400 hover:text-yellow-500
              hover:bg-yellow-50 rounded-full transition"
       title="Review Ticket">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-6 h-6"
             viewBox="0 0 20 20"
             fill="currentColor">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286
                     3.966a1 1 0 00.95.69h4.173c.969 0
                     1.371 1.24.588 1.81l-3.377
                     2.455a1 1 0 00-.364 1.118l1.287
                     3.966c.3.921-.755 1.688-1.54
                     1.118l-3.377-2.455a1 1 0 00-1.175
                     0l-3.377 2.455c-.784.57-1.838
                     -.197-1.539-1.118l1.287-3.966a1
                     1 0 00-.364-1.118L2.98
                     9.393c-.783-.57-.38-1.81.588
                     -1.81h4.173a1 1 0 00.95-.69
                     l1.286-3.966z"/>
        </svg>
    </a>';
                }

                return $showBtn . $reviewBtn;
            })
            ->editColumn('created_at', function ($ticket) {
                return optional($ticket->created_at)
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i');
            })
            ->editColumn('estimation', function ($ticket) {
                return $ticket->estimation
                    ? $ticket->estimation
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i')
                    : 'empty';
            })
            ->editColumn('finished', function ($ticket) {
                return $ticket->finished
                    ? $ticket->finished
                    ->timezone('Asia/Makassar')
                    ->format('d-m-Y H:i')
                    : 'empty';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
