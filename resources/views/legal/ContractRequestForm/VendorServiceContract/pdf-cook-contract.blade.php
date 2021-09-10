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


        /* Style the top navigation bar */
        /* .header {
            overflow: hidden;
            text-align: center;
            border-top: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
        } */

        /* Style the content */
        .content {}

        /* Style the footer */
        /* .footer {
            padding-bottom: 3%;
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            border-right: 1px solid black;
            position: absolute;
            bottom: 0;
        } */

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

        <h4 class="text-center">Vendor Service Contract</h4>
        <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td class="text-center" style="width: 20%;">
                        <h5 class="underline">Sub-type Contract</h5>
                    </td>
                    <td style="padding-left: 1%;">
                        <font class="underline">
                            {{$contract->legalContractDest->legalSubtypeContract->name}}
                        </font>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td class="text-center" style="width: 20%;">
                        <h5 class="underline">Supporting Documents</h5>
                    </td>
                    <td class="text-rigth" style="width: 9%;">Quotation :</td>
                    <td>
                        <font><input type="checkbox" {{$contract->legalContractDest->quotation ? "checked" : ""}}>
                        </font>
                    </td>
                    <td class="text-rigth" style="width: 19%;">AEC/Coparation Sheet :</td>
                    <td>
                        <font><input type="checkbox"
                                {{$contract->legalContractDest->coparation_sheet ? "checked" : ""}}></font>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td class="text-center" style="width: 20%;">
                        <h5 class="underline">Comercial Terms</h5>
                    </td>
                    <td class="text-rigth" style="width: 15%;">Scope of Work :</td>
                    <td colspan="7" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->scope_of_work : ""}}
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
                    <td colspan="2" class="text-rigth">Contract period :</td>
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
                <tr>
                    <td colspan="2" class="text-rigth">Number of cook :</td>
                    <td colspan="7" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->number_of_cook : ""}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-rigth">Working Day :</td>
                    <td colspan="2" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_day : ""}}
                        </font>
                    </td>
                    <td class="text-rigth">Working Time :</td>
                    <td colspan="4" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->working_time : ""}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-rigth">OT :</td>
                    <td colspan="7" style="padding-left: 1%;">
                        <font class="underline">
                            {{isset($contract->legalContractDest->legalComercialTerm) ? $contract->legalContractDest->legalComercialTerm->comercial_ot : ""}}
                        </font>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 95%; margin: 0 auto;">
            <tbody>
                <tr>
                    <td class="text-center" style="width: 20%;">
                        <h5 class="underline">Payment Terms</h5>
                    </td>
                    <td style="width: 15%;" class="text-rigth">
                        Monthly :
                    </td>
                    <td>
                        <font class="underline">
                            {{$contract->legalContractDest->legalPaymentTerm->monthly}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;">
                    </td>
                    <td style="width: 15%;" class="text-rigth">
                        Other Expense :
                    </td>
                    <td>
                        <font class="underline">
                            {{$contract->legalContractDest->legalPaymentTerm->other_expense}}
                        </font>
                    </td>
                </tr>
            </tbody>
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
                        <td colspan="2" class="border-cell"
                            style="height: 50px; width: 50%; vertical-align: text-top; padding-left: 1%; ">
                            Requestor by: </td>
                        <td colspan="2" class="border-cell"
                            style="height: 50px; width: 50%; vertical-align: text-top; padding-left: 1%; ">
                            Checked by: </td>
                    </tr>
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