@extends('admin.layouts.listable')

@section('content')
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <ul class="nav nav-tabs " id="custom-content-below-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active"
                               data-toggle="pill" href="#{{\App\Enums\WeekTypes::FIRST->value}}" role="tab"
                               aria-controls="{{\App\Enums\WeekTypes::FIRST->value}}"
                               aria-selected="true">

                                {{__('admin_labels.week_types.' . \App\Enums\WeekTypes::FIRST->value)}}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#{{\App\Enums\WeekTypes::SECOND->value}}" role="tab" aria-controls="{{\App\Enums\WeekTypes::FIRST->value}}"
                               aria-selected="false">
                                {{__('admin_labels.week_types.' . \App\Enums\WeekTypes::SECOND->value)}}
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content pt-4 pb-4 pl-2 pr-2">
                        <div class="tab-pane fade active show" id="{{\App\Enums\WeekTypes::FIRST->value}}"
                             role="tabpanel">
                            @include('admin.view.students.partials.table', ['tableData' => ($schedules[\App\Enums\WeekTypes::FIRST->value] ?? [])])
                        </div>

                        <div class="tab-pane" id="{{\App\Enums\WeekTypes::SECOND->value}}">
                            @include('admin.view.students.partials.table', ['tableData' => ($schedules[\App\Enums\WeekTypes::SECOND->value] ?? [])])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

