@extends('layouts.app')

@section('content')

<div class="main-content mt-0 hor-content">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">Welcome back, {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</h1>
            </div>
            <form id="filterForm" action="{{ route('dashboard.index') }}" method="GET">
                <div class="row row-sm">
                    <div class="col-xl-4 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <select id="filterByRefNo" name="filterByRef" class="form-control form-select select2" data-bs-placeholder="Select">
                                        <option value="" {{ request('filterByRef') === '' ? 'selected' : '' }}>ALL</option>
                                        @foreach($options as $value => $label)
                                            <option value="{{ $value }}" {{ request('filterByRef') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <select id="filterByMonth" name="month" class="form-control form-select select2">
                                        <option value="0">All</option>
                                        @php
                                            $months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                                            $requestedMonth = (int) request()->input('month'); // Get requested month as integer (if any)
                                            $currentMonth = date('m'); // Get current month as a numeric string (e.g., 05 for May)
                                            foreach ($months as $key => $month) {
                                                $selected = ($key + 1) == ($requestedMonth ?: $currentMonth) ? 'selected' : '';
                                                echo "<option value='" . ($key + 1) . "' $selected>$month</option>";
                                            }
                                        @endphp
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <select id="filterByYear" name="year" class="form-control form-select select2">
                                        @php
                                            $currentYear = date('Y');
                                        @endphp
                                        @for($year = $currentYear; $year >= $currentYear - 5; $year--)
                                            <option value="{{ $year }}" {{ request('year') === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- ROW-1 -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="">Offered</h6>
                                            <h2 class="mb-0 number-font">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($priceSum) }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <div class="chart-wrapper mt-1">
                                                <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="">Carrier expenses</h6>
                                            <h2 class="mb-0 number-font">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($carrierTotalSum) }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <div class="chart-wrapper mt-1">
                                                <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="">Agents expenses</h2>
                                            <h2 class="mb-0 number-font">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($agentFeesSum) }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <div class="chart-wrapper mt-1">
                                                <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-3">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="">Miscellaneous expenses</h6>
                                            <h2 class="mb-0 number-font">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($misFees) }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <div class="chart-wrapper mt-1">
                                                <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="">Profit</h6>
                                            <h2 class="mb-0 number-font">{{ session()->has('currency') ? session('currency') : '€' }} {{ number_format($totalSum) }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <div class="chart-wrapper mt-1">
                                                <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ROW-1 END -->
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
       // $('.select2').select2();

        const filterByRefNo = document.getElementById('filterByRefNo');
        const filterByMonth = document.getElementById('filterByMonth');
        const filterByYear = document.getElementById('filterByYear');

        if (filterByRefNo && filterByMonth && filterByYear) {
            filterByRefNo.addEventListener('change', submitFilterForm);
            filterByMonth.addEventListener('change', submitFilterForm);
            filterByYear.addEventListener('change', submitFilterForm);
        } else {
            console.error('One or more filter elements were not found');
        }

        function submitFilterForm() {
            const selectedRefNo = filterByRefNo.value;
            const selectedMonth = filterByMonth.value;
            const selectedYear = filterByYear.value;

            const queryString = `?filterByRef=${selectedRefNo}&month=${selectedMonth}&year=${selectedYear}`;

            window.location.href = '{{ route("dashboard.index") }}' + queryString;
        }
    });
</script>
@endpush
