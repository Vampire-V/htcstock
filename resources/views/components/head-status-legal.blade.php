<div class="app-page-title fiexd-div-top" style="background-color: #495057; z-index:10">
    <style>
        .progress-meter {
            /* padding: 0; */
        }

        ol.progress-meter {
            /* padding-bottom: 9.5px; */
            list-style-type: none;
            padding-right: 7%;
        }

        ol.progress-meter li {
            display: inline-block;
            text-align: center;
            text-indent: -19px;
            /* height: 36px; */
            width: 20%;
            font-size: 12px;
            border-bottom-width: 4px;
            border-bottom-style: solid;
        }

        ol.progress-meter li:before {
            position: relative;
            float: left;
            text-indent: 0;
            left: -webkit-calc(50% - 9.5px);
            left: -moz-calc(50% - 9.5px);
            left: -ms-calc(50% - 9.5px);
            left: -o-calc(50% - 9.5px);
            left: calc(50% - 9.5px);
        }

        ol.progress-meter li.done {
            font-size: 12px;
        }

        ol.progress-meter li.done:before {
            content: "\2713";
            height: 19px;
            width: 19px;
            line-height: 21.85px;
            bottom: -28.175px;
            border: none;
            border-radius: 19px;
        }

        ol.progress-meter li.todo {
            font-size: 12px;
        }

        ol.progress-meter li.todo:before {
            content: "\2B24";
            font-size: 17.1px;
            bottom: -26.95px;
            line-height: 18.05px;
        }

        ol.progress-meter li.done {
            color: black;
            border-bottom-color: yellowgreen;
        }

        ol.progress-meter li.done:before {
            color: white;
            background-color: yellowgreen;
        }

        ol.progress-meter li.todo {
            color: silver;
            border-bottom-color: silver;
        }

        ol.progress-meter li.todo:before {
            color: silver;
        }
    </style>

    <ol class="progress-meter">
        @isset($status)
            @foreach ($status as $item)
            <li class="progress-point {{$legalContract->status === $item ? " done" : "todo"}}">{{$item}}</li>
            @endforeach
        @endisset
        {{-- <li class="progress-point done">05:20 mins</li>
        <li class="progress-point done">05:10 mins</li>
        <li class="progress-point done">Link</li>
        <li class="progress-point todo">Connect</li> --}}
    </ol>
    {{-- <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>Purchase Equipment <span class="badge badge-primary">{{$legalContract->status}}</span>
    <div class="page-title-subheading">This is an example dashboard created using
        build-in elements and components.
    </div>
</div>
</div>
<div class="page-title-actions">
    <div class="d-inline-block">
    </div>
</div>
</div> --}}
</div>