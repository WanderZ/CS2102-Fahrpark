<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;


class TransactionController extends Controller
{
  public function __construct(Transaction $model)
  {
    $this->model = $model;
  }
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function index()
  {
    $user = \Auth::user();
    $currentUser = $user->id;
    $allTransactions = 0;
    $totalEarnings = 0;
    $totalExpenses = 0;
    $maxEarnings = 0;
    $maxExpenses = 0;
    $minEarnings = 0;
    $minExpenses = 0;
    $avgEarnings = 0;
    $avgExpenses = 0;

    if($user->isAdmin) {
      # Retrieve all number of all transactions
      $records = $this->model->getTransactionCount();
      foreach ($records as $record) {
        foreach($record as $val) {
          $allTransactions = $val;
        }
      }
    }

    $records = $this->model->getTransactionMade($currentUser);
    foreach ($records as $record) {
      foreach($record as $val) {
        $totalTransactions = $val;
      }
    }

    if($totalTransactions != 0) {
      # return $this->model->retrieveAllEarnings($currentUser);

      $rsSumIncome = $this->model->retrieveAllEarnings($currentUser);
      foreach ($rsSumIncome as $record) {
        foreach($record as $val) {
          $totalEarnings = $val;
        }
      }
      $rsSumOutgoing = $this->model->retrieveAllExpenses($currentUser);
      foreach ($rsSumOutgoing as $record) {
        foreach($record as $val) {
          $totalExpenses = $val;
        }
      }
      $rsMaxIncome = $this->model->retrieveLargestEarnings($currentUser);
      foreach ($rsMaxIncome as $record) {
        foreach($record as $val) {
          $maxEarnings = $val;
        }
      }
      $rsMaxExpense = $this->model->retrieveLargestExpenses($currentUser);
      foreach ($rsMaxExpense as $record) {
        foreach($record as $val) {
          $maxExpenses = $val;
        }
      }
      $rsMinIncome = $this->model->retrieveSmallestEarnings($currentUser);
      foreach ($rsMinIncome as $record) {
        foreach($record as $val) {
          $minEarnings = $val;
        }
      }

      $rsMinExpense = $this->model->retrieveSmallestExpenses($currentUser);
      foreach ($rsMinExpense as $record) {
        foreach($record as $val) {
          $minExpenses = $val;
        }
      }
      $rsAvgIncome = $this->model->retrieveAverageEarnings($currentUser);
      foreach ($rsAvgIncome as $record) {
        foreach($record as $val) {
          $avgEarnings = $val;
        }
      }
      $rsAvgExpense = $this->model->retrieveAverageExpenses($currentUser);
      foreach ($rsAvgExpense as $record) {
        foreach($record as $val) {
          $avgExpenses = $val;
        }
      }
    }

    $rsIncoming = $this->model->retrieveUserIncomingTransactionRecords($currentUser);
    $rsOutgoing = $this->model->retrieveUserOutgoingTransactionRecords($currentUser);
    $rsAdmin = $this->model->retrieveAllTransactionRecords();

    $nextInvoiceNo = $this->model->generateNextInvoiceNumber();

    # Retriving the users in the system
    $rsUsers = User::getUserNameIdPair();
    return view('transaction.index', [
      'totalTransactions' => $totalTransactions,
      'totalEarnings' => $totalEarnings,
      'totalExpenses' => $totalExpenses,
      'maxEarnings' => $maxEarnings,
      'maxExpenses' => $maxExpenses,
      'minEarnings' => $minEarnings,
      'minExpenses' => $minExpenses,
      'avgEarnings' => $avgEarnings,
      'avgExpenses' => $avgExpenses,
      'rsIncoming' => $rsIncoming,
      'rsOutgoing' => $rsOutgoing,
      'rsAdmin' => $rsAdmin,
      'users' => $rsUsers,
      'allTransaction' => $allTransactions,
      'newInvoice' => $nextInvoiceNo
    ]);
    #return view('transaction.index');
  }

  /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function create()
  {
    //
  }

  /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function store(Request $request)
  {
    $user = \Auth::user();
    if ($user) {
      $userId = $user->id;
      $data = $request->all();
      $data['type'] = "BOOKING";

      if ($user->isAdmin) {
        $transaction =  Transaction::doInsert(
          ['invoiceNo' => $data['invoiceNo'],
           'from' => $data['fromUsr'],
           'to' => $data['toUsr'],
           'amount' => $data['amt'],
           'type' => $data['type']
          ]);

        if ($transaction) {
          $request->session()->flash('success', 'Transaction created successful!');
          return Redirect::back();
        } else {
          $request->session()->flash('error', 'Invaild Input!');
          return Redirect::back();
        }
      }
    } else {
      $request->session()->flash('error', 'Please Login In!');
      return redirect('/auth/login');
    }
  }

  /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function show($id)
  {
    //
  }

  /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function edit($id)
  {
    //
  }

  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  public function update(Request $request)
  {
    $user = \Auth::user();
    if ($user && $user->isAdmin) {
      $data = $request->all();
      $data['type'] = "BOOKING";

      $attributes =  [
        'invoiceNo' => $data['invoiceNo'],
        'from' => $data['fromUsr'],
        'to' => $data['toUsr'],
        'amount' => $data['amt'],
        'type' => $data['type']
      ];
      $whereQuery = [
        'invoiceNo' => $data['invoiceNo'],
      ];

      $updateStmt = "UPDATE Transactions SET `invoiceNo`=\"".$attributes['invoiceNo']."\",";
      $updateStmt .= "`from`=".$attributes['from']." ,";
      $updateStmt .= "`to`=".$attributes['to']." ,";
      $updateStmt .= "`amount`=".$attributes['amount']." ,";
      $updateStmt .= "`type`=\"BOOKING\" ";
      $updateStmt .= "WHERE invoiceNo=\"".$attributes['invoiceNo']."\";";


      $result = \DB::unprepared($updateStmt);//\App\Models\Transaction::doUpdate($whereQuery, $attributes);

      if ($result) {
        $request->session()->flash('success', 'Transaction edited successfully!');
        return redirect("/transactions");
      } else {
        $request->session()->flash('error', 'Invalid or no Changes Detected!');
        return redirect("/transactions");
      }
    } else {
      //Wrong User cannot Edit
      $request->session()->flash('error', 'Please Login to Edit!');
      return redirect('/auth/login');
    }
  }

  /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
  public function destroy(Request $request)
  {
    $data = $request->all();
    $attributes =  [
      'invoiceNo'=>$data['invoiceNo'],
    ];

    $results = \App\Models\Transaction::doDelete($attributes);

    if ($results) {
      $request->session()->flash('success', 'Transaction deleted successfully!');
      return redirect('/transactions');
    }else {
      $request->session()->flash('error', 'Transaction deleted unsuccessfully!');
      return redirect('/transactions');
    }
  }


}
