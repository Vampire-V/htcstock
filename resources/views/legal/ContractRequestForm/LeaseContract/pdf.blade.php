<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }


        .page-break {
            page-break-before: always;
        }

        body {}

        @page {
            margin: 110px 50px;
        }

        .header {
            position: fixed;
            left: 0px;
            top: -95px;
            right: 0px;
            height: 100px;
            text-align: center;
            border-bottom: 1px solid black;
        }

        .footer {
            position: fixed;
            left: 0px;
            bottom: -50px;
            right: 0px;
            height: 20px;
            text-align: center;
            /* border: 1px solid black; */
        }

        .footer .pagenum:before {
            content: counter(page);
        }

        .location {
            position: absolute;
            /* bottom: 0;
            right: 0; */
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        p {
            padding: 0px !important;
            margin: 0px !important;
        }

        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            padding: 0.0px;
            /* border: 1px solid black; */
        }

        hr {
            border-top: 0.2px solid black;
            padding: 0px;
            margin: 0px;
        }

        .text-rigth {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .border-cell {
            border: 1px solid black;
        }

        .li-none-type {
            list-style-type: none;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>CONTRACT REQUEST FORM</h2>
        <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td class="text-center">This form must be received in Legal Department</td>
                </tr>
                <tr>
                    <td class="text-center">
                        <font class="text-bold underline">ONE WEEK</font> PRIOR to commencement of the Contract Period
                    </td>
                </tr>
                <tr>
                    <td class="text-center">[ Excludes the contract price is less than 100,000 baht ]</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- <div class="footer">
        Page <span class="pagenum"></span>
    </div> --}}

    <div class="content">
        <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td colspan="2" class="text-center">
                        * = Required Information
                    </td>
                </tr>
                <tr>
                    <td class="text-rigth">
                        Action :
                    </td>

                    <td style="width: 80%; padding-left: 1%;">
                        <font class="underline">{{$contract->legalAction->name}}</font>
                    </td>
                </tr>
                <tr>
                    <td class="text-rigth">
                        General Agreements :
                    </td>
                    <td style="width: 80%; padding-left: 1%;">
                        <font class="underline">{{$contract->legalAgreement->name}}</font>
                    </td>
                </tr>
            </tbody>
        </table>
        <h4 class="text-center">CONTRACT INFORMATION</h4>
        <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td class="text-rigth" style="width: 30%;">
                        Full name (Company’s, Person’s) :
                    </td>
                    <td style="padding-left: 1%;">
                        <font class="underline">{{$contract->company_name}}</font>
                    </td>
                    <td style="text-align: right; width: 22%;">
                        Company Certificate :
                    </td>
                    <td>
                        <input type="checkbox" {{$contract->company_cer ? "checked" : ""}}>
                    </td>
                </tr>
                <tr>
                    <td class="text-rigth">
                        Legal Representative :
                    </td>
                    <td style="padding-left: 1%;">
                        <font class="underline">{{$contract->representative}}</font>
                    </td>
                    <td class="text-rigth">
                        Representative Certificate :
                    </td>
                    <td>
                        <input type="checkbox" {{$contract->representative_cer ? "checked" : ""}}>
                    </td>
                </tr>
                <tr>
                    <td class="text-rigth">
                        Address :
                    </td>

                    <td colspan="3" style="padding-left: 1%;">
                        <font class="underline">{{$contract->address}}</font>
                    </td>
                </tr>
            </tbody>

        </table>

        <h4 class="text-center">Lease Contract</h4>
        <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td class="text-center" style="width: 20%;">
                        <h5 class="underline">Supporting Documents</h5>
                    </td>
                    <td class="text-rigth" style="width: 13.5%;">Purchase Order :</td>
                    <td>
                        <font><input type="checkbox" {{$contract->legalContractDest->purchase_order ? "checked" : ""}}>
                        </font>
                    </td>
                    <td class="text-rigth" style="width: 9%;">Quotation :</td>
                    <td>
                        <font><input type="checkbox" {{$contract->legalContractDest->quotation ? "checked" : ""}}>
                        </font>
                    </td>
                    <td class="text-rigth" style="width: 20%;">AEC/Coparation Sheet :</td>
                    <td>
                        <font><input type="checkbox"
                                {{$contract->legalContractDest->coparation_sheet ? "checked" : ""}}></font>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" style="width: 20%;">
                        <h5 class="underline"></h5>
                    </td>
                    <td class="text-rigth" style="width: 13.5%;">Insurance Policy :</td>
                    <td>
                        <font><input type="checkbox" {{$contract->legalContractDest->insurance_policy ? "checked" : ""}}>
                        </font>
                    </td>
                    <td class="text-rigth" style="width: 20%;">Certificate Of Ownership :</td>
                    <td>
                        <font><input type="checkbox"
                                {{$contract->legalContractDest->cer_of_ownership ? "checked" : ""}}></font>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <h5 class="underline">Comercial Terms</h5>
                    </td>
                    <td class="text-rigth">Scope of Work :</td>
                    <td colspan="7" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-rigth">Location :</td>
                    <td colspan="7" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->location : ""}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-rigth">Purchase Order No. :</td>
                    <td colspan="7" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->purchase_order_no : ""}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-rigth">Quotation No. :</td>
                    <td colspan="2" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->quotation_no : ""}}
                        </font>
                    </td>
                    <td class="text-rigth">Dated:</td>
                    <td colspan="4" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->dated->format('Y-m-d') : ""}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-rigth">Contract Period :</td>
                    <td colspan="6" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->contract_period : ""}}
                        </font>
                    </td>
                    {{-- <td class="text-rigth">Untill:</td>
                    <td colspan="4" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->untill->format('Y-m-d') : ""}}
                        </font>
                    </td> --}}
                </tr>
            </tbody>
        </table>
        @if ($contract->legalComercialList->count() > 0)
        <table style="width: 95%; margin: 1 auto;">
            <thead>
                <tr>
                    <th class="border-cell">S/N</th>
                    <th class="border-cell">Description</th>
                    <th class="border-cell">Quantity</th>
                    <th class="border-cell">Unit Price </th>
                    <th class="border-cell">Price</th>
                    <th class="border-cell">Discount</th>
                    <th class="border-cell">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contract->legalComercialList as $key => $item)
                <tr>
                    <td class="text-center border-cell">
                        {{$key+1}}
                    </td>
                    <td class="text-center border-cell">
                        {{$item->description}}
                    </td>
                    <td class="text-center border-cell">
                        {{$item->qty}}
                    </td>
                    <td class="text-center border-cell">
                        {{$item->unit_price}}
                    </td>
                    <td class="text-center border-cell">
                        {{$item->price}}
                    </td>
                    <td class="text-center border-cell">
                        {{$item->discount}}
                    </td>
                    <td class="text-center border-cell">
                        {{$item->amount}}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5"></th>
                    <th>Total: </th>
                    <th class="text-center border-cell">{{$contract->legalComercialList->reduce(function ($a, $b) {
                    return $a + $b->amount;
                },0)}}</th>
                </tr>
            </tfoot>
        </table>
        @endif
        <table style="width: 95%; margin: 0 auto;">
            @if (isset($contract->legalContractDest->legalPaymentType))
            <tr>
                <td style="width: 13%; text-align: right; vertical-align: top; padding-right: 0.3em">
                    <h5 class="underline">Payment Terms</h5>
                </td>
                {{-- <td style="width: 5%;" class="text-center">
                    <font class="underline">
                        {{$contract->legalContractDest->legalPaymentType->name}}
                    </font>
                </td> --}}
                <td >
                    @switch($contract->legalContractDest->legalPaymentType->name)
                        @case("LF.")
                            {{-- <h5 class="underline">Payment Terms</h5> --}}
                            <span>
                                <font class="underline">
                                    {{$contract->legalContractDest->legalPaymentType->name}}
                                </font> Payment shall be made every third Friday of every month for bills placed to HTC every second Tuesday of the previous month.
                            </span>
                            @break
                        @case("LW.")
                            <span>
                                <font class="underline">
                                    {{$contract->legalContractDest->legalPaymentType->name}} 100 %
                                </font> of contract price as per monthly lease basis within 30 days of receipt of invoice.
                            </span>
                            @break
                        @case("LS.")
                            <span>
                                <font class="underline">
                                    {{$contract->legalContractDest->legalPaymentType->name}} 100 %
                                </font> of contract price as per monthly lease basis within 30 days of receipt of invoice.
                            </span>
                            @break
                        @case("LE.")
                            <span>
                                <font class="underline">
                                    {{$contract->legalContractDest->legalPaymentType->name}}
                                </font>
                                 {{$contract->legalContractDest->legalPaymentTerm->detail_payment_term}}
                            </span>
                            @break
                        @case("LIT.")
                            <span>
                                <font class="underline">
                                    {{$contract->legalContractDest->legalPaymentType->name}}
                                </font>Payment shall be made every third Friday of every month for bills placed to HTC every second Tuesday of the previous month.
                            </span>
                            @break
                        @default
                            <span>
                                {{-- Empty... --}}
                            </span>
                    @endswitch
                </td>
            </tr>
                {{-- @if ($contract->legalContractDest->legalPaymentType->name === 'Monthly')
                <tr>
                    <td style="width: 13%;" class="text-center">
                        <h5 class="underline">Payment Terms</h5>
                    </td>
                    <td style="width: 5%;" class="text-center">
                        <font class="underline">
                            {{$contract->legalContractDest->legalPaymentType->name}}
                        </font>
                    </td>
                    <td style="padding-left: 1%;">
                        <font class="underline">
                            {{$contract->legalContractDest->legalPaymentTerm->monthly}}
                        </font>
                    </td>
                </tr>
                @endif
                @if ($contract->legalContractDest->legalPaymentType->name === 'Other')
                <tr>
                    <td style="width: 13%;" class="text-center">
                        <h5 class="underline">Payment Terms</h5>
                    </td>
                    <td style="width: 5%;" class="text-center">
                        <font class="underline">
                            {{$contract->legalContractDest->legalPaymentType->name}}
                        </font>
                    </td>
                    <td style="width: 7%;" class="text-center">
                        <font class="underline">
                            {{$contract->legalContractDest->value_of_contract[0]}}%
                        </font>
                    </td>
                    <td>
                        <span>of the total value of a contract within 15 days from the date of signing of the contract
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">

                    </td>
                    <td style="width: 7%;" class="text-center">
                        <font class="underline">
                            {{$contract->legalContractDest->value_of_contract[1]}}%
                        </font>
                    </td>
                    <td>
                        <span>of the total value of a contract within 30 days from the date of delivered by Lessor and
                            inspected by HTC
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-center">
                        <font class="underline">
                            {{$contract->legalContractDest->value_of_contract[2]}}%
                        </font>
                    </td>
                    <td>
                        <span>of the total value of a contract within 15 days from the date of contract lapse</span>
                    </td>
                </tr>
                @endif --}}
            @endif
        </table>

        <div class="location">
            <h3 class="text-center" style="margin-top: 10%">LOCATION INFORMATION</h3>
            <table style="width: 95%; margin: 0 auto;">
                <tbody>
                    <tr>
                        <td class="text-rigth">
                            <h4>Requestor : </h4>
                        </td>

                        <td style="padding-left: 1%;">
                            <font class="underline">{{$contract->createdBy->name}}</font>
                        </td>
                        <td class="text-rigth">Date : </td>
                        <td style="padding-left: 1%;">
                            <font class="underline">{{$contract->created_at->format('Y-m-d')}}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-rigth">
                            <h4>Department : </h4>
                        </td>

                        <td style="padding-left: 1%;">
                            <font class="underline">{{$contract->createdBy->department->name}}</font>
                        </td>
                        <td class="text-rigth">Phone : </td>
                        <td style="padding-left: 1%;">
                            <font class="underline">{{$contract->createdBy->phone}}</font>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="width: 95%; margin: 0 auto;" class="border-cell">
                <tbody>
                    <tr>
                        <td class="border-cell"
                            style="height: 50px; width: 25%; vertical-align: text-top; padding-left: 1%; ">
                            Requestor by: </td>
                        <td class="border-cell"
                            style="height: 50px; width: 25%; vertical-align: text-top; padding-left: 1%; ">
                            Acknowledged by: </td>
                        <td colspan="2" class="border-cell"
                            style="height: 50px; width: 50%; vertical-align: text-top; padding-left: 1%; ">
                            Checked by: </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 95%; margin: 0 auto;" class="border-cell">
                <tbody>
                    <tr>
                        <td style="width: 28%;" class="border-cell text-center">Department</td>
                        <td style="width: 72%;" class="text-center">
                            <font>{{$contract->createdBy->department->name}}</font>
                        </td>
                        <td style="width: 28%;" class="border-cell text-center">Department</td>
                        <td style="width: 72%;" class="text-center">
                            <font>{{$contract->checkedBy->department->name}}</font>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
