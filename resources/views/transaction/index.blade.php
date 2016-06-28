@extends('layout')

@section('page-title')
Transactions
@endsection

@section('content')
<h1>Account Transactions</h1><br />
<?php #var_dump($records); ?>
<?php # echo '<pre>'; var_dump($dt); echo '</pre>'; ?>
<div class="" data-example-id="togglable-tabs">
  <ul id="" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
      <a href="#overview" role="tab" id="overview-tab" data-toggle="tab" aria-controls="overview" aria-expanded="false">Overview</a>
    </li>
    @if(\Auth::user()->isAdmin)
    <li role="presentation" class="">
      <a href="#admin" role="tab" id="admin-tab" data-toggle="tab" aria-controls="admin" aria-expanded="true">Admin </a>
    </li>
    @else
    <li role="presentation" class="">
      <a href="#earnings" role="tab" id="earnings-tab" data-toggle="tab" aria-controls="earnings" aria-expanded="false">Earnings</a>
    </li>
    <li role="presentation" class="">
      <a href="#expenses" role="tab" id="expenses-tab" data-toggle="tab" aria-controls="expenses" aria-expanded="true">Expenses</a>
    </li>
    @endif
  </ul>
  <div id="" class="tab-content">
    <div role="tabpanel" class="tab-pane fade active in" id="overview" aria-labelledby="overview-tab">
      <h3>Account Overview</h3>
      <dl class="dl-horizontal">
        <dt>Transaction(s) Made:</dt>
        <dd>{{ $totalTransactions }}</dd>
        @if(\Auth::user()->isAdmin)
        <dt>System Transaction(s):</dt>
        <dd>{{ $allTransaction }}</dd>
        @else
        <dt>&nbsp;</dt>
        <dd>&nbsp;</dd>
        <dt>Total Earnings:</dt>
        <dd>&#36;{{ number_format($totalEarnings, 2, '.', ',') }}</dd>
        <dt>Total Expenditure:</dt>
        <dd>&#36;{{ number_format($totalExpenses, 2, '.', ',') }}</dd>
        <dt>Largest Incoming:</dt>
        <dd>&#36;{{ number_format($maxEarnings, 2, '.', ',') }}</dd>
        <dt>Largest Outgoing:</dt>
        <dd>&#36;{{ number_format($maxExpenses, 2, '.', ',') }}</dd>
        <dt>Smallest Incoming:</dt>
        <dd>&#36;{{ number_format($minEarnings, 2, '.', ',') }}</dd>
        <dt>Smallest Outgoing:</dt>
        <dd>&#36;{{ number_format($minExpenses, 2, '.', ',') }}</dd>
        <dt>Average Earnings:</dt>
        <dd>&#36;{{ number_format($avgEarnings, 2, '.', ',') }}</dd>
        <dt>Average Expenses:</dt>
        <dd>&#36;{{ number_format($avgExpenses, 2, '.', ',') }}</dd>
        <dt>&nbsp;</dt>
        <dd>&nbsp;</dd>
        <dt>Overall Cashflow:</dt>
        <dd>
          <span style="color:@if(($totalEarnings-$totalExpenses)>=0) green @else red @endif; font-weight: 700;">
            @if(($totalEarnings-$totalExpenses)>=0)
            &#36;{{ number_format(abs($totalEarnings-$totalExpenses), 2, '.', ',') }}
            @else
            &#45;&#36;{{ number_format(abs($totalEarnings-$totalExpenses), 2, '.', ',') }}
            @endif
          </span>
        </dd>
        @endif
      </dl>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="earnings" aria-labelledby="earnings-tab">
      <?php # <h3>Earnings to show here</h3> ?><br />
      @if(count($rsIncoming)!=0)
      <div class="container-fluid">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Invoice Ref</th>
                  <th>From</th>
                  <th>Amount</th>
                  <th>Type</th>
                  <th>Date Transfered</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rsIncoming as $incomeRecord)
                <tr>
                  <td>{{$incomeRecord->invoiceNo}}</td>
                  <td style="text-transform: capitalize;">{{$incomeRecord->person}}</td>
                  <td>&#36;{{ number_format($incomeRecord->amount, 2, '.', ',')}}</td>
                  <td>{{$incomeRecord->type}}</td>
                  <td>{{$incomeRecord->createdAt}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @else
      <h3><small>You have no Incoming Records</small></h3>
      @endif
      <?php # echo '<pre>'; var_dump($rsIncoming); echo '</pre>';?>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="expenses" aria-labelledby="expenses-tab">
      <?php # <h3>Expenses to show here</h3> ?><br />
      @if(count($rsOutgoing)!=0)
      <div class="container-fluid">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Invoice Ref</th>
                  <th>To</th>
                  <th>Amount</th>
                  <th>Type</th>
                  <th>Date Transfered</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rsOutgoing as $expenseRecord)
                <tr>
                  <td>{{$expenseRecord->invoiceNo}}</td>
                  <td style="text-transform: capitalize;">{{$expenseRecord->person}}</td>
                  <td>&#36;{{ number_format($expenseRecord->amount, 2, '.', ',')}}</td>
                  <td>{{$expenseRecord->type}}</td>
                  <td>{{$expenseRecord->createdAt}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @else
      <h3><small>You have no outgoing records</small></h3>
      @endif
      <?php # echo '<pre>'; var_dump($rsOutgoing); echo '</pre>'; ?>
    </div>
    @if(\Auth::user()->isAdmin)
    <div role="tabpanel" class="tab-pane fade" id="admin" aria-labelledby="admin-tab">
      <?php # <h3>Admin stuff to show here</h3> ?><br/>
      <form class="form-inline" role="form" method="GET" action="">
        <div class="control-label">
          <div class="form-group">
            <input class="form-control" type="text" name="query" id="query" placeholder="Query here" />
          </div>
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
          <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target=".modal-create-transaction">
            Create Transaction
          </button>
        </div>
      </form>
      <p>&nbsp;</p>
      @if(count($rsAdmin)!=0)
      <div class="container-fluid">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Invoice Ref</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Amount</th>
                  <th>Type</th>
                  <th>Date Transfered</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php $count = 0; ?>
                @foreach ($rsAdmin as $record)
                <?php $count++; ?>
                <tr>
                  <td>{{$record->invoiceNo}}</td>
                  <td><a href="{{ url('user/' . $record->payer) }}" style="text-transform: capitalize;">{{$record->payer}}</a></td>
                  <td><a href="{{ url('user/' . $record->payee) }}" style="text-transform: capitalize;">{{$record->payee}}</a></td>
                  <td>&#36;{{ number_format($record->amount, 2, '.', ',')}}</td>
                  <td>{{$record->type}}</td>
                  <td>{{$record->createdAt}}</td>
                  <td>
                    <form class="form-inline" role="form" id="delete" action="/transactions/delete/" method="post">
                      {!! csrf_field() !!}
                      <input type="hidden" id="invoiceNo" name="invoiceNo" value="{{$record->invoiceNo}}"/>
                      <button type="button" class="btn btn-info" data-toggle="modal" data-target=".modal-edit-transaction-{{$count}}">
                        Edit
                      </button>
                      <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @else
      <h3><small>No Records Captured</small></h3>
      @endif
      <?php # echo '<pre>'; var_dump($rsOutgoing); echo '</pre>'; ?>
    </div>
    @endif
  </div>
</div>
@if(\Auth::user()->isAdmin)
<div class="modal modal-create-transaction" tabindex="-1" role="dialog" aria-labelledby="createTransactionModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fa fa-times"></i></span>
        </button>
        <h4 class="modal-title" id="gridSystemModalLabel">Create New Transaction Record</h4>
      </div>
      <form role="form" id="create" class="form-horizontal" action="/transactions/create">
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              {!! csrf_field() !!}
              <div class="form-group">
                <label class="col-sm-3 control-label">Invoice Number:</label>
                <div class="col-sm-9">
                  <input type="hidden" name="invoiceNo" value="{{$newInvoice}}" />
                  <p class="form-control-static">{{$newInvoice}}</p>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="fromUsr">From User:</label>
                <div class="col-sm-9">
                  <select class="form-control" id="fromUsr" name="fromUsr" form="create">
                    @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->username}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="toUsr">To User:</label>
                <div class="col-sm-9">
                  <select class="form-control" id="toUsr" name="toUsr" form="create">
                    @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->username}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="amt">Amount:</label>
                <div class="col-sm-9">
                  <div class="input-group">
                    <span class="input-group-addon" id="cost">&#36;</span>
                    <input class="form-control" id="amt" name="amt" type="number" min="0" max="9999" step="0.01" required>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="amt">Type:</label>
                <div class="col-sm-9">
                  <p class="form-control-static">BOOKING</p>
                </div>
              </div>
              <?php # https://eonasdan.github.io/bootstrap-datetimepicker/Installing/ ?>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div><!-- /.modal-content -->
      </form>
    </div>
  </div>
</div>
<?php $modalCounter = 0; ?>
@foreach($rsAdmin as $record)
<?php $modalCounter++; ?>
<div class="modal modal-edit-transaction-{{$modalCounter}}" tabindex="-{{$modalCounter+1}}" role="dialog"
     aria-labelledby="editTransactionModel{{$modalCounter}}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fa fa-times"></i></span>
        </button>
        <h4 class="modal-title" id="gridSystemModalLabel">Modify Transaction Record</h4>
      </div>
      <form role="form" id="edit{{$modalCounter}}" class="form-horizontal" action="/transactions/edit" method="post">
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              {!! csrf_field() !!}
              <div class="form-group">
                <label class="col-sm-3 control-label">Invoice Number:</label>
                <div class="col-sm-9">
                  <input type="hidden" name="invoiceNo" value="{{$record->invoiceNo}}" />
                  <p class="form-control-static">{{$record->invoiceNo}}</p>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="fromUsr">From User:</label>
                <div class="col-sm-9">
                  <select class="form-control" id="fromUsr" name="fromUsr" form="edit{{$modalCounter}}">
                    @foreach($users as $user)
                    @if($user->username == $record->payer)
                    <option value="{{$user->id}}" selected="selected">{{$user->username}}</option>
                    @else
                    <option value="{{$user->id}}" >{{$user->username}}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="toUsr">To User:</label>
                <div class="col-sm-9">
                  <select class="form-control" id="toUsr" name="toUsr" form="edit{{$modalCounter}}">
                    @foreach($users as $user)
                    @if($user->username == $record->payee)
                    <option value="{{$user->id}}" selected="selected">{{$user->username}}</option>
                    @else
                    <option value="{{$user->id}}" >{{$user->username}}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="amt">Amount:</label>
                <div class="col-sm-9">
                  <div class="input-group">
                    <span class="input-group-addon" id="cost">&#36;</span>
                    <input class="form-control" id="amt" name="amt" type="number" min="0" max="9999" step="0.01" required value="{{$record->amount}}">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="amt">Type:</label>
                <div class="col-sm-9">
                  <p class="form-control-static">BOOKING</p>
                </div>
              </div>
              <?php # https://eonasdan.github.io/bootstrap-datetimepicker/Installing/ ?>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div><!-- /.modal-content -->
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@endsection
