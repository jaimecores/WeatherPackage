<h1>{{ __('weatherpackage::forecast.show.h1') }} {{ $location}}</h1>

<a style="text-decoration: none" href="{{ route('forecast.index') }}">< {{ __('weatherpackage::forecast.show.back') }}</a>

<table style="width: 100%; margin-top:25px">
    <thead>
        <tr>
            <th style="text-align: left">{{ __('weatherpackage::forecast.show.date') }}</th>
            <th style="text-align: left">{{ __('weatherpackage::forecast.show.time') }}</th>
            <th style="text-align: left">{{ __('weatherpackage::forecast.show.description') }}</th>
            <th></th>
        <tr>
    </thead>
    <tbody  style="text-align: left">
    @php
        $previousDate = null;
    @endphp
    @foreach ($forescastArray as $day)
        <tr>
            @php
                $datetime = explode(' ', $day->dt_txt);
                $date = $datetime[0];
                $time = $datetime[1];
            @endphp
            <input type="hidden" value="{{ $previousDate }}">
            <td>
            @if($date != $previousDate)
                {{ date('d-m-Y', strtotime($date))  }}
            @endif
            </td>
            <td>{{ $time }}</td>
            <td>{{ ucfirst($day->description) }}</td>
            <td><img src="{{ asset('http://openweathermap.org/img/wn/' . $day->icon . '@2x.png' )}}" alt="{{ $day->description }}"></td>
        </tr>
        @php
            $previousDate = $date;
        @endphp
        <input type="hidden" value="{{ $previousDate }}">
    @endforeach
    </tbody>
</table>