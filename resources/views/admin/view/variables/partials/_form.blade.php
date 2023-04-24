<ul class="nav nav-tabs " id="custom-content-below-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active show" data-toggle="pill" href="#general" role="tab" aria-controls="general"
           aria-selected="false">{{__('admin_labels.tab_general')}}</a>
    </li>
</ul>

<div class="tab-content pt-4 pb-4 pl-2 pr-2">
    <div class="tab-pane active show" id="general">
        @include('admin.view.' . $module . '.tabs.general')
    </div>
</div>
