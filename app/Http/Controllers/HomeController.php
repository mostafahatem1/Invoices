<?php

namespace App\Http\Controllers;

use App\invoices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.status');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sumTotal=invoices::sum('total');
        $countInvoices=invoices::count();

        ////////////////////////////////
        $sumUnpaid=invoices::where('Value_Status',2)->sum('total');
        $countUnpaid=invoices::where('Value_Status',2)->count();
        $percentageUnpaid=($countUnpaid/$countInvoices)*100;

        //////////////////////////////////
        $sumPaid=invoices::where('Value_Status',1)->sum('total');
        $countPaid=invoices::where('Value_Status',1)->count();
        $percentagePaid=($countPaid/$countInvoices)*100;

        /////////////////////////
        $sumPartial=invoices::where('Value_Status',3)->sum('total');
        $countPartial=invoices::where('Value_Status',3)->count();
        $percentagePartial=($countPartial/$countInvoices)*100;

        /////////////////////////      chart js      /////////////////////////////////////////
        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [round($percentageUnpaid,2)]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [round($percentagePaid,2)]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [round($percentagePartial,2)]
                ],
            ])->optionsRaw([
                'legend' => [
                    'display' => true,
                    'labels' => [
                        'fontFamily' => 'Cairo',
                        'fontStyle' => 'bold',
                    ]
                ],

            ]);;


        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214','#ff9642'],
                    'data' => [round($percentageUnpaid,2), round($percentagePaid,2),round($percentagePartial,2)]
                ]
            ])
            ->options([]);



        return view('home',
            compact(
                'sumTotal','countInvoices',

                'sumUnpaid','countUnpaid','percentageUnpaid',

                'sumPaid','countPaid','percentagePaid',

                'sumPartial','countPartial','percentagePartial',

                'chartjs','chartjs_2'
            ));
    }
}
