@extends('layout.dashboard')

@section('content')
<div class="panel h-full">
    <div class="mb-5 flex items-center dark:text-white-light">
        <h5 class="text-lg font-semibold">Production Process Summary</h5>
    </div>
    <div class="space-y-9">
        @foreach($processData as $process)
        <div class="flex items-center">
            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                <div class="grid h-9 w-9 place-content-center rounded-full bg-success-light text-success dark:bg-success dark:text-success-light">
                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="..." stroke="currentColor" stroke-width="1.5"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="mb-2 flex font-semibold text-white-dark">
                    <h6>{{ $process['stage'] }}</h6>
                    <p class="ltr:ml-auto rtl:mr-auto">{{ $process['percentage'] }}% ({{ $process['count'] }} items)</p>
                </div>
                <div class="h-2 rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                    <div class="h-full rounded-full bg-gradient-to-r from-[#3cba92] to-[#0ba360]" style="width: {{ $process['percentage'] }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
